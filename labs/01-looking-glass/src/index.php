<?php
session_start();
require __DIR__ . '/config.php';

if (!empty($_SESSION['auth'])) {
    header('Location: admin.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    // Authenticate the operator.
    // Username must match exactly; password is checked against the stored MD5 hash.
    if ($user === $ADMIN_USER && md5($pass) == $ADMIN_PASS_HASH) {
        $_SESSION['auth'] = true;
        $_SESSION['user'] = $user;
        header('Location: admin.php');
        exit;
    }
    $error = 'Invalid credentials.';
}
?>
<!doctype html>
<html lang="en">
<meta charset="utf-8">
<title>NetOps Looking Glass</title>
<style>
  body{font-family:system-ui,sans-serif;background:#0d1117;color:#e6edf3;display:grid;place-items:center;height:100vh;margin:0}
  .card{background:#161b22;border:1px solid #30363d;border-radius:10px;padding:28px 30px;width:320px}
  h1{font-size:1.2rem;margin:0 0 4px}
  p.sub{color:#8b949e;margin:0 0 18px;font-size:.85rem}
  label{display:block;font-size:.8rem;color:#8b949e;margin:12px 0 4px}
  input{width:100%;padding:9px 10px;border-radius:6px;border:1px solid #30363d;background:#0d1117;color:#e6edf3;box-sizing:border-box}
  button{margin-top:18px;width:100%;padding:10px;border:0;border-radius:6px;background:#238636;color:#fff;font-weight:600;cursor:pointer}
  .err{color:#f85149;font-size:.82rem;margin-top:12px}
</style>
<div class="card">
  <h1>NetOps Looking Glass</h1>
  <p class="sub">Operator sign-in</p>
  <form method="post" action="index.php">
    <label>Username</label>
    <input name="username" autocomplete="off" autofocus>
    <label>Password</label>
    <input name="password" type="password" autocomplete="off">
    <button type="submit">Sign in</button>
    <?php if ($error): ?><div class="err"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  </form>
</div>
