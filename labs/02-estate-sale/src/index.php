<?php
session_start();
require __DIR__ . '/classes.php';

// "Remember me": restore the visitor's session from the remember cookie.
if (isset($_COOKIE['remember'])) {
    $token = unserialize(base64_decode($_COOKIE['remember']));
    if ($token instanceof RememberToken) {
        $_SESSION['user'] = $token->username;
    }
}

// Flavour login (not the interesting part).
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['username'] ?? '') === 'bidder' && ($_POST['password'] ?? '') === 'auction2026') {
        $_SESSION['user'] = 'bidder';
        // Issue a 30-day remember cookie.
        setcookie('remember', base64_encode(serialize(new RememberToken('bidder'))), time() + 2592000);
        header('Location: index.php');
        exit;
    }
    $error = 'Invalid credentials.';
}

$user = $_SESSION['user'] ?? null;
?>
<!doctype html>
<html lang="en">
<meta charset="utf-8">
<title>Estate Sale — Online Auctions</title>
<style>
  body{font-family:system-ui,sans-serif;background:#faf7f2;color:#2b2622;margin:0;padding:40px;display:grid;place-items:center}
  .card{background:#fff;border:1px solid #e4ddd2;border-radius:12px;padding:30px 34px;width:360px;box-shadow:0 6px 24px rgba(0,0,0,.06)}
  h1{font-size:1.25rem;margin:0 0 4px}
  p.sub{color:#8a8178;margin:0 0 18px;font-size:.85rem}
  label{display:block;font-size:.8rem;color:#8a8178;margin:12px 0 4px}
  input{width:100%;padding:9px 10px;border-radius:6px;border:1px solid #e4ddd2;box-sizing:border-box}
  button{margin-top:18px;width:100%;padding:10px;border:0;border-radius:6px;background:#9a6b3f;color:#fff;font-weight:600;cursor:pointer}
  .err{color:#c0392b;font-size:.82rem;margin-top:12px}
  .hi{color:#5c7a45;font-weight:600}
</style>
<div class="card">
  <h1>Estate Sale</h1>
  <p class="sub">Members-only auction house</p>
  <?php if ($user): ?>
    <p>Welcome back, <span class="hi"><?= htmlspecialchars($user) ?></span>.</p>
    <p class="sub">Today's lots are being appraised. Check back soon.</p>
    <p><a href="index.php?logout=1">sign out</a></p>
    <?php if (isset($_GET['logout'])) { session_destroy(); setcookie('remember','',time()-1); echo '<meta http-equiv="refresh" content="0;url=index.php">'; } ?>
  <?php else: ?>
    <form method="post" action="index.php">
      <label>Username</label>
      <input name="username" autocomplete="off" autofocus>
      <label>Password</label>
      <input name="password" type="password" autocomplete="off">
      <label><input type="checkbox" name="remember" style="width:auto"> remember me</label>
      <button type="submit">Sign in</button>
      <?php if ($error): ?><div class="err"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    </form>
  <?php endif; ?>
</div>
