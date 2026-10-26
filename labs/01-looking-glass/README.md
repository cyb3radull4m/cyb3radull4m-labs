# Lab 01 — Looking Glass

> A legacy network "looking glass" console for operators. Sign in, probe a host. What could go wrong?

| | |
|---|---|
| **Stack** | PHP 8 / Apache |
| **Difficulty** | ★☆☆☆☆ (intro) |
| **Est. time** | 30–60 min |
| **Techniques** | PHP type juggling (MD5 magic hashes), command injection, blacklist bypass |
| **Goal** | Authentication bypass → authenticated RCE → capture **FLAG1** (access) + **FLAG2** (proof via RCE) |

## Run it

```bash
docker compose up --build
# app on http://127.0.0.1:8081
```

Stop and clean up:
```bash
docker compose down
```

## Your mission

You have the **full source** in `src/`. Read it, find the chain, and prove RCE by capturing both flags.
No brute force, no external wordlists — this is white-box.

1. Get in **without the real password**.
2. From the operator console, get **command execution** on the server.
3. Read `/proof.txt`.

Write your own `exploit.py` that does all of it in one run. Compare with the reference in `exploit/`
only after you've tried.

## Stuck?

- `walkthrough/HINTS.md` — progressive nudges.
- `walkthrough/WALKTHROUGH.md` — full solution (spoilers).

## ⚠️ Intentionally vulnerable — run locally and offline only.
