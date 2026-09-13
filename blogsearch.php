<?php

$pdo = require 'config.php';

$query = $_GET['q'] ?? '' ;

$totalPosts = (int) $pdo->query('SELECT COUNT(*) FROM posts')->fetchColumn();

if ($query === '') {
    $sql = 'SELECT id, username, description, post_date FROM posts ORDER BY post_date DESC';
} else {
    $sql = "
        SELECT id, username, description, post_date
        FROM posts
        WHERE description LIKE '%$query%'
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
        value="<?= $query ?>"
        placeholder="Search by description…"
        autofocus
    >
</form>

<?php if (empty($results)): ?>
    <p class="no-results">Good work idiot, no posts match "<?= $query ?>"! Try a different term.</p>
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
                <span>📅 <?= date('M j, Y', strtotime($post['post_date'])) ?></span>
            </div>
        </article>
    <?php endforeach; ?>
    <nav class="landing-links">
    <a class="btn-primary" href="blogadd.php">Add to the blog</a>
    <a class="btn-secondary" href="landing.php">Home</a>
    </nav>
<?php endif; ?>
</div>

/* DOM-based XSS vulnerable code
Purpose: this section of javascript is used to track user
searches and add them to a database for tracking, allowing the
owner to see what is being searched/what is popular

First, query is retrieved via window.location.search performed on 'q',
the input that is entered into the search bar. 
trackSearch() then is called on query, which creates a new Image
(1x1 px that is impossible for the viewer to see), writing in the form of
an img src URL + the query string which is necessary for track.php 
(description continued there)

However, this introduces a major vulnerablility to DOM-based XSS attacks -
First, the attacker can manipulate the url, accessed in the code via the 
window.location object. As the query sits unprotected in document.write,
the attacker can then paste their payload in the form of a query string
to be written to the page and executed.
As such, any javacsript-based payload can be used, ranging from redirecting,
writing elements to the page or reading the session token, among many others.
*/

<script>
function trackSearch(query) {
    var img = new Image();
    document.write('<img src="track.php?searchTerms='+query+'">')
}

var query = (new URLSearchParams(window.location.search)).get('q');
if(query) {
    trackSearch(query);
}
</script>

</body>
</html>