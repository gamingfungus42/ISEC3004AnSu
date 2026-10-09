<?php

$pdo = require 'config.php';

$query = $_GET['q'] ?? '';

$totalPosts = (int) $pdo->query('SELECT COUNT(*) FROM posts')->fetchColumn();

if ($query === '') {
    $sql = 'SELECT id, username, description, post_date FROM posts ORDER BY post_date DESC';
    $results = $pdo->query($sql)->fetchAll();
} else {
    // parameterised query - bound as data never concatenated into SQL.
    $escapedText = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $query);

    $sql = "
        SELECT id, username, description, post_date
        FROM posts
        WHERE description LIKE :term ESCAPE '\\\\'
        ORDER BY post_date DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['term' => '%' . $escapedText . '%']);
    $results = $stmt->fetchAll();
}

/**
 * $text is HTML escaped first (convertedText), then <mark> tags are added around
 * matches found in the escaped text to guarantee nothing from the 
 * database (or from query) is rendered as live HTML, also preventing stored attacks
 */

function highlight(string $text, string $query): string
{
    $convertedText = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

    if ($query === '') {
        return $convertedText;
    }

    $escapedQuery = htmlspecialchars($query, ENT_QUOTES, 'UTF-8');
    $pattern = '/' . preg_quote($escapedQuery, '/') . '/i';

    return preg_replace($pattern, '<mark>$0</mark>', $convertedText);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Blog</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="blog-container">
<h1>Blog</h1>
<p class="count"><?= $totalPosts ?> posts</p>

<form class="search-box" method="get" action="">
    <input
        type="text"
        name="q"
        value="<?= htmlspecialchars($query, ENT_QUOTES, 'UTF-8') ?>"
        placeholder="Search by description…"
        autofocus
    >
</form>

<?php if (empty($results)): ?>
    <p class="no-results">Good job idiot, no posts match "<?= htmlspecialchars($query, ENT_QUOTES, 'UTF-8') ?>"! Try a different term.</p>
    <nav class="landing-links">
    <a class="btn-primary" href="blogadd.php">Add to the blog</a>
    <a class="btn-secondary" href="landing.php">Home</a>
    </nav>
<?php else: ?>
    <?php foreach ($results as $post): ?>
        <article>
            <h2><?= highlight($post['username'], $query) ?></h2>
            <p><?= highlight($post['description'], $query) ?></p>
            <div class="meta">
                <span>📅 <?= htmlspecialchars(date('M j, Y', strtotime($post['post_date'])), ENT_QUOTES, 'UTF-8') ?></span>
            </div>
        </article>
    <?php endforeach; ?>
    <nav class="landing-links">
    <a class="btn-primary" href="blogadd.php">Add to the blog</a>
    <a class="btn-secondary" href="landing.php">Home</a>
    </nav>
<?php endif; ?>
</div>

<script>
function trackSearch(query) {
    if (typeof query !== 'string' || query.length > 200) {
        return;
    }

    const img = new Image();
    img.src = 'track.php?' + new URLSearchParams({
        searchTerms: query
    }).toString();
}

const query = new URLSearchParams(window.location.search).get('q');

if (query) {
    trackSearch(query);
}
</script>

</body>
</html>