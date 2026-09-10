<?php
// config.php - database connection settings
// Update these to match your database

$host = 'localhost';
$dbname = 'login_demo';
$username = 'root';
$password = 'Anakinlee1!';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Start session on every page that includes this file
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}