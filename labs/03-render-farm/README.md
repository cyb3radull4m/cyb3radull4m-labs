# Lab 03 — Render Farm

> A reporting service that lets admins preview custom report templates.

| | |
|---|---|
| **Stack** | Node.js / Express (EJS) |
| **Difficulty** | ★★☆☆☆ |
| **Est. time** | 45–90 min |
| **Techniques** | Broken access control (client-trusted role), server-side template injection (SSTI) |
| **Goal** | Reach the admin-only preview → SSTI → RCE → read `FLAG{...}` |

## Run it
```bash
docker compose up --build        # http://127.0.0.1:8083
docker compose down              # stop + clean up
```

## Your mission
Full source in `src/`. The template preview is admin-only — or is it?

1. Get past the admin check.
2. Find where your input is treated as a template.
3. Turn that into code execution and read `/flag.txt`.

Write your own one-click `exploit.py`, then compare with `exploit/`.

## Stuck?
- `walkthrough/HINTS.md` — progressive nudges.
- `walkthrough/WALKTHROUGH.md` — full solution (spoilers).

## ⚠️ Intentionally vulnerable — run locally and offline only.
