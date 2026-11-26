<?php
require_once __DIR__ . '/../includes/functions.php';
$error = '';
if (!empty($_SESSION['user'])) { header('Location: ../pages/dashboard.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = strtolower(trim($_POST['username'] ?? ''));
    $pass = $_POST['password'] ?? '';
    if (!in_array($user, ROLES, true)) {
        $error = 'Unknown user. Use "manager" or "trainer".';
    } else {
        try {
            $c = db_connect($user, $pass);          // real Oracle login
            oci_close($c);
            session_regenerate_id(true);
            $_SESSION['user'] = $user;
            $_SESSION['pass'] = $pass;
            header('Location: ../pages/dashboard.php'); exit;
        } catch (Exception $e) {
            $error = 'Login failed: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login - Gym Management</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body class="login-page">
<form class="login-box" method="post">
    <div class="logo">🏋️</div>
    <h1>Gym Management</h1>
    <p>Sign in with your Oracle user</p>
    <?php if ($error): ?><div class="alert error"><?= h($error) ?></div><?php endif; ?>
    <div class="field"><label for="username">Username</label>
        <input id="username" name="username" type="text" required autofocus value="<?= h($_POST['username'] ?? '') ?>"></div>
    <div class="field"><label for="password">Password</label>
        <input id="password" name="password" type="password" required></div>
    <button class="btn" type="submit">Login</button>
    <div class="hint">Users: manager (full access) &middot; trainer (view only)</div>
</form>
</body>
</html>
