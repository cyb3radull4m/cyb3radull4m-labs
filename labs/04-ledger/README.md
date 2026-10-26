# Lab 04 — Ledger

> A small finance app with an account-status API and saved user preferences.

| | |
|---|---|
| **Stack** | Python / Flask / SQLite |
| **Difficulty** | ★★★★☆ |
| **Est. time** | 2–3 h |
| **Techniques** | Blind (boolean) SQL injection, scripted oracle, insecure deserialization (pickle) |
| **Goal** | Exfiltrate the signing secret → forge a pickle → RCE → read `FLAG{...}` |

## Run it
```bash
docker compose up --build        # http://127.0.0.1:8084
docker compose down              # stop + clean up
```

## Your mission
Full source in `src/`. The account-status API gives away more than a yes/no if you ask correctly.

1. Find the injection and realise it's **blind** — you'll need to script an oracle.
2. Exfiltrate the server's signing secret.
3. Use it to reach the deserialization sink and get RCE. Read `/flag.txt`.

This is the scripting-heavy lab. Write your own oracle before reading `exploit/`.

## Stuck?
- `walkthrough/HINTS.md` — progressive nudges.
- `walkthrough/WALKTHROUGH.md` — full solution (spoilers).

## ⚠️ Intentionally vulnerable — run locally and offline only.
