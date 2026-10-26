# Lab 05 — Polyfill  (capstone)

> A customisable dashboard that saves user settings and can run a health check.

| | |
|---|---|
| **Stack** | Node.js / Express |
| **Difficulty** | ★★★★★ |
| **Est. time** | 2–3 h |
| **Techniques** | Prototype pollution, gadget hunting, command injection via inherited property |
| **Goal** | Pollute the prototype → reach a command gadget → RCE → capture **FLAG1** (access) + **FLAG2** (proof via RCE) |

## Run it
```bash
docker compose up --build        # http://127.0.0.1:8085
docker compose down              # stop + clean up
```

## Your mission
Full source in `src/`. Saving settings is more powerful than it looks.

1. Find the unsafe merge and prove prototype pollution.
2. Hunt a **gadget** — code that reads a property off a fresh object and does something dangerous.
3. Connect the two for RCE. Read `/proof.txt`.

The capstone. Write your own one-click `exploit.py`, then compare with `exploit/`.

## Stuck?
- `walkthrough/HINTS.md` — progressive nudges.
- `walkthrough/WALKTHROUGH.md` — full solution (spoilers).

## ⚠️ Intentionally vulnerable — run locally and offline only.
