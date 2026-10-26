# Lab 02 — Estate Sale

> A members-only online auction house with a convenient "remember me" option.

| | |
|---|---|
| **Stack** | PHP 8 / Apache |
| **Difficulty** | ★★★☆☆ |
| **Est. time** | 1–2 h |
| **Techniques** | PHP object injection, POP-chain construction, `unserialize()` abuse, arbitrary file write |
| **Goal** | Object injection → webshell → RCE → read `FLAG{...}` |

## Run it
```bash
docker compose up --build        # http://127.0.0.1:8082
docker compose down              # stop + clean up
```

## Your mission
Full source is in `src/`. The "remember me" feature is more generous than intended.

1. Find where attacker-controlled data reaches a dangerous sink.
2. Find a class whose magic method you can abuse (a POP gadget).
3. Turn it into code execution and read `/flag.txt`.

No login required. Write your own one-click `exploit.py`, then compare with `exploit/`.

## Stuck?
- `walkthrough/HINTS.md` — progressive nudges.
- `walkthrough/WALKTHROUGH.md` — full solution (spoilers).

## ⚠️ Intentionally vulnerable — run locally and offline only.
