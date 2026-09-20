<?php

$pdo = require 'config.php';

if (empty($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Creates unpredictable CSRF token stored in the user's session
// This token must be included when the form is submitted
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // stores token in user's server side session
}

$errors = [];
$description = '';
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submiitedToken = $_POST['csrf_token'] ?? '';

    // Rejects POST request if it doesn't contain the correct session token
    if (!is_string($submittedToken) || !hash_equals($_SESSION['csrf_token'], $submittedToken)) {
        http_response_code(403);
        $errors[] = 'Invalid request. Please refresh the page and try again.';
    } else {
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

            // replaces unpredictable token after every successfull form submission
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            
            header('Location: landing.php');
            exit;
        }
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
