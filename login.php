<?php
require 'config.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Lock out after 5 failed attempts for 60 seconds
    if ($_SESSION['login_attempts'] >= 5 && (time() - $_SESSION['last_attempt']) < 60) {
        $errors[] = 'Too many failed attempts. Please wait a minute and try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $errors[] = 'Please enter both username and password.';
        } else {
            $stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE username = ? OR email = ?');
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['login_attempts'] = 0;

                header('Location: dashboard.php');
                exit;
            } else {
                $_SESSION['login_attempts']++;
                $_SESSION['last_attempt'] = time();
                $errors[] = 'Invalid username or password.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Log In</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="auth-box">
    <h1>Log In</h1>

    <form method="post" action="login.php">
        <label>Username or Email
            <input type="text" name="username" value="<?=($_POST['username'] ?? '') ?>" required>
        </label>
        <label>Password
            <input type="password" name="password" required>
        </label>
        <button type="submit">Log In</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register</a></p>
</div>
</body>
</html>