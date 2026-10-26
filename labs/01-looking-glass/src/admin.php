<?php
session_start();
if (empty($_SESSION['auth'])) {
    header('Location: index.php');
    exit;
}

// Access flag — shown only to an authenticated operator. Value lives outside the webroot.
$access_flag = @file_get_contents('/opt/flags/access.txt');

$output = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $target = $_POST['target'] ?? '';
    // Security filter: strip shell metacharacters before running the probe.
    $target = str_replace([';', '&', '`'], '', $target);
    $output = shell_exec('ping -c 1 ' . $target . ' 2>&1');
}
?>
<!doctype html>
<html lang="en">
<meta charset="utf-8">
<title>Looking Glass — Console</title>
<style>
  body{font-family:system-ui,sans-serif;background:#0d1117;color:#e6edf3;margin:0;padding:30px}
  .bar{display:flex;justify-content:space-between;align-items:center;max-width:760px;margin:0 auto 20px}
  h1{font-size:1.1rem;margin:0}
  a{color:#58a6ff;font-size:.85rem}
  .panel{max-width:760px;margin:0 auto;background:#161b22;border:1px solid #30363d;border-radius:10px;padding:22px}
  .flag{background:#0d1117;border:1px solid #2ea04355;border-radius:6px;padding:10px 12px;margin-bottom:16px;color:#2ea043;font-family:ui-monospace,monospace;font-size:.85rem}
  input{width:70%;padding:9px 10px;border-radius:6px;border:1px solid #30363d;background:#0d1117;color:#e6edf3}
  button{padding:9px 16px;border:0;border-radius:6px;background:#238636;color:#fff;font-weight:600;cursor:pointer}
  pre{background:#0d1117;border:1px solid #30363d;border-radius:6px;padding:14px;overflow:auto;white-space:pre-wrap;margin-top:18px}
</style>
<div class="bar">
  <h1>Looking Glass — signed in as <?= htmlspecialchars($_SESSION['user']) ?></h1>
  <a href="logout.php">sign out</a>
</div>
<div class="panel">
  <div class="flag">access granted — <?= htmlspecialchars(trim($access_flag)) ?></div>
  <form method="post" action="admin.php">
    <input name="target" placeholder="host or IP to probe" autocomplete="off" autofocus>
    <button type="submit">Run probe</button>
  </form>
  <?php if ($output !== ''): ?><pre><?= htmlspecialchars($output) ?></pre><?php endif; ?>
</div>
