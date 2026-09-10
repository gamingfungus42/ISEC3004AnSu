<?php
$pdo = require 'config.php';

$searchTerm = isset($_GET['searchTerms']) ? trim($_GET['searchTerms']) : '';
if ($searchTerm !== '') {
    $sql = "INSERT INTO search_logs (search_term) VALUES (?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$searchTerm]);
}
header('Content-Type: image/gif');
echo base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
