# Hints — Looking Glass

Open these one at a time. Try the step yourself before reading the next.

<details><summary>Hint 1 — where to start</summary>

This is white-box. Read `src/` before you touch the app. There are only four PHP files. Start with how
the login decision is made in `src/index.php` and `src/config.php`.
</details>

<details><summary>Hint 2 — the login check</summary>

Look at the comparison operator used for the password. Is it `===` or `==`? In PHP those behave very
differently. Now look at the *shape* of the stored hash in `config.php`. What does it start with?
</details>

<details><summary>Hint 3 — magic hashes</summary>

PHP's loose `==` converts strings that look like `0e` followed by only digits into the float `0`. The
stored hash starts with `0e`. If you can supply a password whose MD5 **also** matches `0e` + digits,
`0 == 0` is true. Search for "PHP magic hash MD5 0e". A well-known one: `240610708`.
</details>

<details><summary>Hint 4 — after you're in</summary>

The console runs a `ping` probe on the host you type. Read `src/admin.php` and look at exactly which
characters the filter removes before the command runs. Which common shell metacharacter did it forget?
</details>

<details><summary>Hint 5 — the injection</summary>

`;`, `&` and the backtick are stripped. The pipe `|` is not. So `127.0.0.1 | cat /proof.txt` runs your
command after the ping. The flag is at `/proof.txt`.
</details>
