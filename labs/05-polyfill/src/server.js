// ---------------------------------------------------------------------------
// Polyfill — user-customisable dashboard config, an account view, a health runner
// ---------------------------------------------------------------------------
const express = require('express');
const { execSync } = require('child_process');
const fs = require('fs');

const app = express();
app.use(express.text({ type: '*/*' }));

const config = { theme: 'light', widgets: ['clock'] };

function merge(target, source) {
  for (const key in source) {
    const val = source[key];
    if (val && typeof val === 'object') {
      if (typeof target[key] !== 'object' || target[key] === null) target[key] = {};
      merge(target[key], val);
    } else {
      target[key] = val;
    }
  }
  return target;
}

app.get('/', (req, res) => {
  res.type('html').send(`
    <!doctype html><meta charset="utf-8"><title>Polyfill</title>
    <h1>Polyfill dashboard</h1>
    <p>Save settings: <code>POST /config</code> (JSON). View account: <code>GET /account</code>.
    Health check: <code>GET /health</code>.</p>`);
});

// Save user dashboard settings.
app.post('/config', (req, res) => {
  let body = {};
  try { body = JSON.parse(req.body || '{}'); } catch (e) { return res.status(400).json({ error: 'bad json' }); }
  merge(config, body);
  res.json({ ok: true, theme: config.theme });
});

// Account view. Admins see the access flag.
app.get('/account', (req, res) => {
  const u = {};                                   // fresh object
  const role = u.role || 'guest';                 // inherits a polluted prototype property
  if (role === 'admin') {
    const accessFlag = fs.readFileSync('/opt/flags/access.txt', 'utf8').trim();
    return res.type('text').send('admin — ' + accessFlag);
  }
  res.type('text').send('guest');
});

// Run the configured health-check command (defaults to a harmless echo).
app.get('/health', (req, res) => {
  const opts = {};
  const cmd = opts.healthCmd || 'echo healthy';   // inherits a polluted prototype property
  try { res.type('text').send(execSync(cmd).toString()); }
  catch (e) { res.status(500).type('text').send(String(e)); }
});

app.listen(80, () => console.log('polyfill listening on :80'));
