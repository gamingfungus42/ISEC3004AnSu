<?php

$pdo = require 'config.php';

$errors = [];
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($username === '') {
        $errors[] = 'Username is required.';
    }

    if ($email === '') {
        $errors[] = 'Email is required.';
    } 

    if ($password === '') {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username OR email = :email LIMIT 1');
        $stmt->execute(['username' => $username, 'email' => $email]);

        if ($stmt->fetch()) {
            $errors[] = 'That username or email is already taken.';
        }
    }

    if (empty($errors)) {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password' )";
        $pdo->exec($sql);
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['username'] = $username;

        header('Location: home.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Create an Account</h1>

<?php if (!empty($errors)): ?>
    <ul class="errors">
        <?php foreach ($errors as $error): ?>
            <li><?= $error ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post" action="">
    <label for="username">Username</label>
    <input
        type="text"
        id="username"
        name="username"
        value="<?= $username ?>"
        required
    >

    <label for="email">Email</label>
    <input
        type="email"
        id="email"
        name="email"
        value="<?= $email ?>"
        required
    >

    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>

    <label for="confirm_password">Confirm Password</label>
    <input type="password" id="confirm_password" name="confirm_password" required>

    <button type="submit">Register</button>
</form>

<p>Already have an account? <a href="login.php">Log in</a></p>

</body>
</html>