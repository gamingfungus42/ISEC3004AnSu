<?php

$config = require __DIR__ . '/config.php';
try {
    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['user'], $config['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    die('Database connection failed. Check config.php.');
}

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

$totalPosts = (int) $pdo->query('SELECT COUNT(*) FROM posts')->fetchColumn();

if ($query === '') {
    $sql = 'SELECT id, username, description, post_date FROM posts ORDER BY post_date DESC';
} else {
    $sql = "
        SELECT id, username, description, post_date
        FROM posts
        WHERE username LIKE '%$query%'
           OR description LIKE '%$query%'
        ORDER BY post_date DESC
    ";
}

$results = $pdo->query($sql)->fetchAll();

function highlight($text, $query) {
    if ($query === '') return $text;
    $pattern = '/' . preg_quote($query, '/') . '/i';
    return preg_replace($pattern, '<mark>$0</mark>', $text);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Journal</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Journal</h1>
<p class="count"><?= $totalPosts ?> posts</p>

<form class="search-box" method="get" action="">
    <input
        type="text"
        name="q"
        value="<?= $query ?>"
        placeholder="Search by username or description…"
        autofocus
    >
</form>

<?php if (empty($results)): ?>
    <p class="no-results">Good work idiot, no posts match "<?= $query ?>"! Try a different term.</p>
<?php else: ?>
    <?php foreach ($results as $post): ?>
        <article>
            <h2><?= highlight($post['username'], $query) ?></h2>
            <p><?= highlight($post['description'], $query) ?></p>
            <div class="meta">
                <span>📅 <?= date('M j, Y', strtotime($post['post_date'])) ?></span>
            </div>
        </article>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>