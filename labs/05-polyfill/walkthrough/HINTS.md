# Hints — Polyfill

<details><summary>Hint 1 — the merge</summary>

Read `src/server.js`. `POST /config` calls `merge(config, yourJSON)`. Look at how `merge` recurses.
Does it ever guard against a key named `__proto__`?
</details>

<details><summary>Hint 2 — prototype pollution</summary>

It doesn't. A recursive merge that follows `__proto__` lets you write onto `Object.prototype`. Send
`{"__proto__": {"x": "y"}}` and now *every* object has `x === "y"`.
</details>

<details><summary>Hint 3 — find the gadget</summary>

Polluting is only half the job. You need a property that something later *reads*. Look at `GET /health`:
it builds `const opts = {}` and reads `opts.healthCmd` with a fallback. `opts` has no own `healthCmd`...
</details>

<details><summary>Hint 4 — connect them</summary>

Pollute `Object.prototype.healthCmd`, and `opts.healthCmd` inherits it. `/health` then runs it through
`execSync`. Set it to `cat /proof.txt`:
```
POST /config   {"__proto__":{"healthCmd":"cat /proof.txt"}}
GET  /health
```
</details>
