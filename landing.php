<?php
$pdo = require 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Landing</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="landing">
    <h1>Welcome to the Simple Blog</h1>
    <p>A place to write and share your posts</p>
 
    <?php if (!empty($_SESSION['username'])): ?>
        <p>You're logged in as <?= ($_SESSION['username']) ?>.</p>
        <nav class="landing-links">
            <a href="blogadd.php">Add to the blog</a>
            <a href="blogsearch.php">Search the blog</a>
        </nav>
    <?php else: ?>
        <nav class="landing-links">
            <a href="login.php">Log In</a>
            <a href="register.php">Register</a>
            <a href="blogadd.php">Add to the blog</a>
            <a href="blogsearch.php">Search the blog</a>
        </nav>
    <?php endif; ?>
</div>
 
</body>
</html>
