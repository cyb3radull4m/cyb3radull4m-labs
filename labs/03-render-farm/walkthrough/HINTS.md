# Hints — Render Farm

<details><summary>Hint 1 — the guard</summary>

Read `src/server.js`. The `/preview` route is "admins only". How does it decide you're an admin? Where
does that value come from — the server, or you?
</details>

<details><summary>Hint 2 — the access-control flaw</summary>

`if (req.cookies.role !== 'admin')`. The role is just a cookie you send. Set `role=admin` and you pass
the check. Client-controlled authorization is no authorization.
</details>

<details><summary>Hint 3 — what happens to your input</summary>

Once you're past the check, your `template` field goes straight into `ejs.render(template, ...)`. EJS
templates are compiled to JavaScript and run on the server. That's server-side template injection.
</details>

<details><summary>Hint 4 — SSTI to RCE in EJS</summary>

Inside an EJS tag you can run JS. `process` is a global. So:
```
<%= process.mainModule.require('child_process').execSync('cat /flag.txt') %>
```
Send that as the template with the `role=admin` cookie.
</details>
