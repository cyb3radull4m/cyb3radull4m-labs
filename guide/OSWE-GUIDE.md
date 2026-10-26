# How I Passed the OSWE — a white-box web exploitation guide

> By cyb3radull4m. Last updated: _[ PLACEHOLDER — month / year you passed ]_.
> Independent write-up. Not affiliated with or endorsed by OffSec. See repo `DISCLAIMER.md`.

---

## 1. Your story

> _Draft from my own words — edit freely._

I passed the OffSec Web Expert (OSWE) exam in October 2026. The format is brutal in the best way:
48 hours of white-box source-code review, chaining vulnerabilities and writing full exploit scripts,
then another 24 hours to write the report. It pushed me harder than anything I'd done so far, and I
genuinely enjoyed the course. I set out to earn it alongside my brother Jeremiah Kolawole before the
year ended — we had moments where we almost gave up, but we kept pushing, and we made it together.

<!-- Add a line on your background going in (OSCP/OSEP already held, dev/scripting experience) and the
     one thing that made the biggest difference, so readers can calibrate. -->

---

## 2. Your prep

> 📝 **[ PLACEHOLDER — YOUR PREP ]**
> _The order you studied the topics, roughly how many hours, and the specific resources that actually
> helped — and any that weren't worth the time. Rank them if you can._

> These **cyb3radull4m-labs** map directly to the exam's technique areas — start with
> `01-looking-glass` for the type-juggling + command-injection warm-up, then work the track.

---

## 3. Exam approach

> 📝 **[ PLACEHOLDER — EXAM APPROACH ]**
> _How you split the time, your routine (sleep, breaks, when you wrote the report), and two or three
> honest "I wish I'd known" lessons. No NDA-breaking detail — just approach._

---

## Reference — what the OSWE tests (written, keep or trim)

The OSWE (via the WEB-300 course) is a **white-box** web exploitation certification. You get source
code and you're expected to:
- review code to find the vulnerability (not black-box fuzzing),
- chain bugs — almost always **authentication bypass → remote code execution**,
- and **write a single, reliable exploit script** that runs the whole chain start to finish.

Core technique areas: PHP type juggling, SQL injection (especially **blind**, and second-order),
insecure deserialization (PHP/Java/.NET/Node), server-side template injection, XXE, command/argument
injection, file-upload bypasses, prototype pollution, and auth logic flaws (JWT, password reset).

Things worth having ready before the exam:
- a Python requests/urllib skeleton that handles sessions, CSRF tokens, and errors cleanly,
- a reusable **blind-SQLi oracle** (boolean and time based) you can point at any endpoint,
- snippets for building serialized payloads per language,
- a reliable way to catch a shell / prove RCE.

---

### Practise the techniques
Every technique above has a hands-on lab in this repo. Clone it, read the source, write the exploit:
```bash
git clone https://github.com/cyb3radull4m/cyb3radull4m-labs
cd cyb3radull4m-labs/labs/01-looking-glass && docker compose up --build
```
