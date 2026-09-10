<?php
$pdo = require 'config.php';
session_start();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $errors[] = 'Please enter both username and password.';
        } else {
            $sql = "SELECT id, username, password FROM users WHERE username = '$username' OR email = '$username'";
            $user = $pdo->query($sql)->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                header('Location: home.php');
                exit;
            } else {
                $errors[] = 'Invalid username or password.';
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

    <?php if (!empty($errors)): ?>
        <p class="errors"><?= implode(' ', $errors) ?></p>
    <?php endif; ?>

    <form method="post" action="login.php">
        <label>Username or Email
            <input type="text" name="username" value="<?= $_POST['username'] ?? '' ?>" required>
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