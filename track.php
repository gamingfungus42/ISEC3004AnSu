<?php
$pdo = require 'config.php';

/*
Continued from blogsearch.php
This section trims just the searchTerms/query portion from the src URL
and inserts said term into the database, using a prepared statement to
prevent SQL injection attacks
the echo base64_decode creates the 1x1 px transparent GIF image to be
displayed
*/

$searchTerm = isset($_GET['searchTerms']) ? trim($_GET['searchTerms']) : '';
if ($searchTerm !== '') {
    $sql = "INSERT INTO search_logs (search_term) VALUES (?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$searchTerm]);
}
header('Content-Type: image/gif');
echo base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
