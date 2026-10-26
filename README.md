# cyb3radull4m-labs — white-box web exploitation labs

Free, source-included, intentionally vulnerable web apps for practising **white-box web
exploitation** — the skill set behind exam-style web security certifications. Every lab gives you the
**full source code**, a realistic app, and one goal: read the code, find the chain, and write a
**one-click exploit** that ends in remote code execution.

> Built and maintained by **cyb3radull4m**. Independent project. Not affiliated with, sponsored by, or
> endorsed by OffSec. "OSWE" is a trademark of OffSec; these labs are original works for exam-*style*
> practice only. See `DISCLAIMER.md`.

## How the labs work

Each lab is a self-contained Docker app. You get the code; your job is to exploit it.

```bash
cd labs/01-looking-glass
docker compose up --build        # start the lab
# ... read src/, find the chain, write your exploit ...
python3 exploit/exploit.py http://127.0.0.1:8081   # the reference one-click PoC
```

Every lab folder contains:

| File / dir            | What it is                                                        |
|-----------------------|-------------------------------------------------------------------|
| `src/`                | the full application source — **this is your white-box view**     |
| `Dockerfile`, `docker-compose.yml` | run the lab with one command                         |
| `README.md`           | objective, difficulty, time estimate, how to run                  |
| `walkthrough/HINTS.md`| progressive hints — use only when stuck                           |
| `walkthrough/WALKTHROUGH.md` | the full methodology + solution (spoilers)                 |
| `exploit/exploit.py`  | the reference one-click auth-bypass → RCE script                  |

**Proof of success:** each app holds a `FLAG{...}` reachable only by achieving RCE. Your exploit should
retrieve it automatically.

## The track

See `CURRICULUM.md` for all labs, the technique each one teaches, and the difficulty order.

## ⚠️ Safety

These apps are **deliberately vulnerable**. Run them **locally and offline only**. Never expose a lab
to the internet or a shared network. Everything is throwaway; destroy the container when done
(`docker compose down`).

## License

Lab code is released under the MIT License (see `LICENSE`) so you can study, modify, and extend it.
