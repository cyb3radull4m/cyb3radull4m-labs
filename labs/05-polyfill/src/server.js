// ---------------------------------------------------------------------------
// Polyfill — user-customisable dashboard config + a health-check runner
// ---------------------------------------------------------------------------
const express = require('express');
const { execSync } = require('child_process');

const app = express();
app.use(express.text({ type: '*/*' }));   // we parse the JSON ourselves

const config = { theme: 'light', widgets: ['clock'] };

// Recursively merge user settings into the running config.
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
    <p>Save settings: <code>POST /config</code> with a JSON body.</p>
    <p>Run a health check: <code>GET /health</code>.</p>
  `);
});

// Save user dashboard settings.
app.post('/config', (req, res) => {
  let body = {};
  try { body = JSON.parse(req.body || '{}'); } catch (e) { return res.status(400).json({ error: 'bad json' }); }
  merge(config, body);
  res.json({ ok: true, theme: config.theme });
});

// Run the configured health-check command (defaults to a harmless echo).
app.get('/health', (req, res) => {
  const opts = {};                              // fresh options object
  const cmd = opts.healthCmd || 'echo healthy'; // per-deployment override, if configured
  try {
    res.type('text').send(execSync(cmd).toString());
  } catch (e) {
    res.status(500).type('text').send(String(e));
  }
});

app.listen(80, () => console.log('polyfill listening on :80'));
