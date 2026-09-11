<?php
$pdo = require 'config.php';

if (empty($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Home</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="landing">
    <h1>You're logged in!</h1>
    <p>Welcome back, <?= ($_SESSION['username']) ?>.</p>

    <nav class="landing-links">
        <a href="landing.php">Landing</a>
        <a href="blogadd.php">Add to the blog</a>
        <a href="blogsearch.php">Search the blog</a>
    </nav>
</div>

</body>
</html>