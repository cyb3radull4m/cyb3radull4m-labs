// ---------------------------------------------------------------------------
// Render Farm — custom report template preview service
// ---------------------------------------------------------------------------
const express = require('express');
const cookieParser = require('cookie-parser');
const ejs = require('ejs');
const fs = require('fs');

const app = express();
app.use(express.urlencoded({ extended: true }));
app.use(cookieParser());

app.get('/', (req, res) => {
  res.cookie('role', req.cookies.role || 'user');
  res.type('html').send(`
    <!doctype html><meta charset="utf-8"><title>Render Farm</title>
    <style>body{font-family:system-ui,sans-serif;background:#0f1115;color:#e6e6e6;padding:40px;max-width:720px;margin:auto}
    code{background:#1b1e24;padding:2px 6px;border-radius:4px}</style>
    <h1>Render Farm</h1>
    <p>Report template preview. Your role: <code>${(req.cookies.role || 'user')}</code>.</p>
    <p>Only <b>admins</b> may preview custom templates (<code>POST /preview</code>).</p>`);
});

// Preview a custom report template. Restricted to admins.
app.post('/preview', (req, res) => {
  // Authorization: only admins may render custom templates.
  if (req.cookies.role !== 'admin') {
    return res.status(403).type('html').send('<h1>403</h1><p>Admins only.</p>');
  }

  // Access flag — shown to anyone who reaches the admin preview. Value lives outside the app dir.
  const accessFlag = fs.readFileSync('/opt/flags/access.txt', 'utf8').trim();

  const template = req.body.template || '';
  try {
    const output = ejs.render(template, { company: 'Render Farm Inc.' });
    res.type('html').send(`<p>admin preview unlocked — ${accessFlag}</p><h3>Preview</h3><pre>${output}</pre>`);
  } catch (e) {
    res.status(500).type('html').send(`<p>admin preview unlocked — ${accessFlag}</p>Template error: ` + e.message);
  }
});

app.listen(80, () => console.log('render-farm listening on :80'));
