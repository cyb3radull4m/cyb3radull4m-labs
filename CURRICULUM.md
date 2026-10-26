# Curriculum — the white-box exploitation track

Five labs, rising in difficulty, spanning the stacks and techniques exam-style web exploitation tests:
PHP, Node.js and Python; type juggling, object injection, SSTI, blind SQLi and prototype pollution.
Every lab is white-box (you have the source) and every lab ends in **RCE**, driven by a Python
exploit you write.

| # | Lab | Stack | Core techniques | The chain | Difficulty | Status |
|---|-----|-------|-----------------|-----------|------------|--------|
| 01 | **Looking Glass** | PHP 8 / Apache | PHP type juggling (magic hashes), command injection, blacklist bypass | loose-comparison auth bypass → authenticated command injection → RCE | ★☆☆☆☆ | ✅ built |
| 02 | **Estate Sale** | PHP 8 / Apache | PHP object injection, POP-chain construction, `unserialize()` | cookie object injection → POP chain → arbitrary file write → RCE | ★★★☆☆ | ✅ built |
| 03 | **Render Farm** | Node.js / Express | Auth logic flaw, server-side template injection (SSTI) | broken access control → SSTI in a template engine → RCE | ★★☆☆☆ | ✅ built |
| 04 | **Ledger** | Python / Flask | Blind SQL injection, scripted boolean/time oracle, insecure deserialization | blind SQLi → admin secret exfil → pickle deserialization → RCE | ★★★★☆ | ✅ built |
| 05 | **Polyfill** | Node.js / Express | Prototype pollution, gadget hunting | prototype pollution → polluted gadget → RCE (capstone) | ★★★★★ | ✅ built |

## Why this order

1. **Looking Glass** teaches the core habit — *read the source, spot the flawed check* — on two of the
   most iconic PHP bugs, with a gentle chain.
2. **Estate Sale** steps up to object injection and the art of building a POP chain by hand, the
   deserialization skill exam-style tests lean on heavily.
3. **Render Farm** moves you into Node and SSTI, and makes you chain an access-control flaw first.
4. **Ledger** is the scripting test: a blind SQLi you can only beat by writing a proper oracle, then a
   deserialization finish.
5. **Polyfill** is the capstone — prototype pollution to RCE, the hardest class to both find and weaponise.

## Specs for labs 02–05

Each spec below defines the app, the intended vulnerability, and the intended chain, so the lab can be
built to a fixed target. (Lab 01 is already fully implemented as the template.)

### 02 — Estate Sale (PHP object injection)
A small auction/marketplace app. A "remember me" cookie contains a base64 **serialized PHP object**
that the app `unserialize()`s on every request. A class in the codebase defines a `__destruct()` (or
`__wakeup()`) that writes to a file path built from object properties. The learner reads the source,
finds a usable POP gadget, crafts a serialized object that writes a PHP webshell into the webroot, then
executes it. **Lesson:** never `unserialize()` attacker input; how to assemble a POP chain from
available classes.

### 03 — Render Farm (Node SSTI)
An Express app that renders user-named "report templates" with a server-side template engine. An
access-control flaw (an IDOR or a trusting-the-client role check) lets a normal user reach the admin
template editor, where input is compiled into the template unsafely → SSTI → RCE via the engine's
sandbox escape. **Lesson:** SSTI discovery and exploitation in a JS template engine; chaining an authz
flaw to reach the sink.

### 04 — Ledger (Python blind SQLi → deserialization)
A Flask finance app with a lookup endpoint vulnerable to **blind** (boolean- and time-based) SQL
injection — no data is returned directly, so the learner must **write a Python oracle** to exfiltrate
the admin's session-signing secret character by character. With the secret, they forge an admin session
whose payload is **pickle-deserialized** server-side → RCE. **Lesson:** scripting a blind-SQLi oracle
(the signature skill); insecure deserialization in Python.

### 05 — Polyfill (Node prototype pollution → RCE)
An Express app that deep-merges user JSON into a config object, allowing **prototype pollution**. The
learner reads the source to find a gadget — a later `child_process` call, template render, or option
object — whose behaviour they can hijack via a polluted prototype property, achieving RCE. **Lesson:**
finding and weaponising prototype pollution, the hardest class in the track.
