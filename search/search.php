<?php
require_once 'database-access.php';

$results = [];
$search = '';

$result_order = 0;

if (isset($_GET['query']) && !empty(trim($_GET["query"]))){
    $search = trim($_GET['query']);
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM test WHERE content LIKE :query");
    $stmt->execute(['query' => '%' . $search . '%']);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Website Search</title>
    <link rel="stylesheet" href="/search/search.css">
</head>
<body>
    <h1>Search</h1>
    <p>Rows can be added to this database<br>by going to the page linked <a href="/insert/insert.php">here</a>.</p>
    <form class="form" action="/search/search.php" method="GET">
        <input type="text" name="query" required placeholder="Search terms" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>
    <?php if ($search !== ''):?>
        <?php echo "<h3 class='result_title'>Results for <q>$search</q></h3>"?>
        <?php foreach ($results as $result): ?>
            <?php $id = $result['id']; ?>
            <?php $content = $result['content']; ?>
            <?php echo "<section class='allResults'>"; ?>
            <?php echo "<p class='results-title' id='result-" . $result_order . "-id'>Id: " . $id . "</p>"; ?>
            <?php echo "<p class='results-content' id='result-" . $result_order . "-content'><strong>Content:</strong> " . $content . "</p>"; ?>
            <?php echo "</section>"; ?>
            <?php $result_order++; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <br>
    <button onclick="gotoLink('../index.html')" type="button" id="button1">Back to home page</button>
    <script type="text/javascript" src="../../button1.js"></script>
</body>
</html>
