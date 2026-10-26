# Hints — Ledger

<details><summary>Hint 1 — find the injection</summary>

Read `src/app.py`. One query is built with Python string formatting instead of parameters. Which
endpoint, and which parameter? Note what the endpoint returns — it's not the data, just a boolean.
</details>

<details><summary>Hint 2 — it's blind</summary>

`/api/status?username=` returns only `{"active": true|false}`. That's a boolean oracle. You can't read
data directly — you have to *ask yes/no questions* and script the answers.
</details>

<details><summary>Hint 3 — what to steal</summary>

There's a `config` table with a `secret`. The same secret signs the `prefs` cookie (see the `/` route).
Exfiltrate it with the blind oracle: binary-search each character using
`unicode(substr(value,pos,1)) > N`.
</details>

<details><summary>Hint 4 — the second bug</summary>

The `prefs` cookie is HMAC-checked, then `pickle.loads()`'d. pickle is a code-execution sink. Once you
have the secret you can forge a validly-signed pickle the server will execute.
</details>

<details><summary>Hint 5 — RCE via pickle</summary>

A class with `__reduce__` returning `(os.system, ("<cmd>",))` runs your command on load. Base64 it,
HMAC-sign it with the leaked secret, send it as `prefs`. Have it write the flag somewhere you can read,
e.g. `cat /proof.txt > /app/static/leak.txt`, then GET `/static/leak.txt`.
</details>
