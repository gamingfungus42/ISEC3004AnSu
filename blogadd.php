<?php

$pdo = require 'config.php';


if (empty($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
 
$errors = [];
$description = '';
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $description = trim($_POST['description'] ?? '');
 
    if ($description === '') {
        $errors[] = 'Description is required.';
    }
 
    if (empty($errors)) {
        $stmt = $pdo->prepare(
            'INSERT INTO posts (username, description, post_date) VALUES (:username, :description, CURDATE())'
        );
        $stmt->execute([
            'username'    => $_SESSION['username'],
            'description' => $description,
        ]);
 
        header('Location: landing.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>New Post</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
 
<div class="auth-box">
    <h1>New Post</h1>
 
    <?php if (!empty($errors)): ?>
        <p class="errors"><?= (implode(' ', $errors)) ?></p>
    <?php endif; ?>
 
    <form method="post" action="">
        <label>Description
            <textarea name="description" rows="5" required><?= $description ?></textarea>
        </label>
        <button type="submit">Post</button>
    </form>
 
<nav class="landing-links">
    <a class="btn-primary" href="landing.php">Home</a>
</nav>
</div>
 
</body>
</html>