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
            <a class="btn-primary" href="blogadd.php">Add to the blog</a>
            <a class="btn-primary" href="blogsearch.php">Search the blog</a>
            <a class="btn-secondary" href="logout.php">Log Out</a>
        </nav>
    <?php else: ?>
       <nav class="landing-links">
        <a href="login.php" class="btn-primary">Log In</a>
        <a href="register.php" class="btn-secondary">Register</a>
        <a href="blogadd.php" class="btn-secondary">Add to the blog</a>
        <a href="blogsearch.php" class="btn-secondary">Search the blog</a>
        </nav>
    <?php endif; ?>
</div>
 
</body>
</html>
