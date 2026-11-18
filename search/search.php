<?php
require_once 'database-access.php';

$results = [];
$search = '';

$result_order = 0;
if (session_status() === PHP_SESSION_NONE) {
    session_start([
    'cookie_lifetime' => 0,
    'cookie_secure'   => true,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Strict'
]);
}
if (isset($_GET['query'])) {
    $search = trim($_GET['query']);
    global $pdo;
    if (!empty(trim($_GET["query"]))){
        $stmt = $pdo->prepare("SELECT * FROM portfolio WHERE name LIKE :query OR owner LIKE :query");
        $stmt->execute(['query' => '%' . $search . '%']);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM portfolio WHERE name LIKE :query OR owner LIKE :query");
        $stmt->execute(['query' => '%%%']);
    }
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = 'Guest';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Website Search</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/search/search.css">
</head>
<body>
    <!-- Top Bar containing title and navigation links -->
    <header id="top-bar">
        <h1 id="top-title">Search</h1>
        <div id="links">
            <button onclick="gotoLink('/index.php')" type="button" id="button1">Back to home page</button>

            <button onclick="const usrEl = document.getElementById('user-prof');
            const usrLogged = usrEl.dataset.isLogged;
            if (usrLogged === 'true') {
              gotoLink('/create/create.php')
            }" type="button" id="button4">Create a Portfolio</button>

            <button onclick="gotoLink('/signup/signup.php')" type="button" id="button5">Signup</button>

            <button onclick="gotoLink('/login/login.php')" type="button" id="button5">Login</button>

            <button onclick="const usrEl = document.getElementById('user-prof');
            const usrLogged = usrEl.dataset.isLogged;
            if (usrLogged === 'true') {
              gotoLink('/logout/logout.php')
            }" type="button" id="button5">Logout</button>
        </div>
    </header>


    <?php if (isset($_SESSION['user'])): ?>
        <?php if ($_SESSION['user'] === 'Guest'): ?>
            <?php echo "<p id='user-display'>Not logged in.</p>"; ?>
        <?php else: ?>
            <?php echo "<p id='user-display'>Logged in as " . $_SESSION['user'] . ".</p>"; ?>
        <?php endif; ?>
    <?php endif; ?>

    <form class="form" action="/search/search.php" method="GET">
        <input type="text" name="query" required placeholder="Search terms" value="<?php echo htmlspecialchars($search); ?>">
        <button id="search" type="submit">Search</button>
    </form>

    <!--Results logic-->

    <?php if (count($results) > 0):?>
        <?php if (!empty(trim($search))): ?>
            <?php echo "<h3 class='result-title'>Results for <q>$search</q>:</h3>"?>
            <?php echo "<div class='results-box'>"; ?>
            <?php foreach ($results as $result): ?>
                <?php $owner = $result['owner']; ?>
                <?php $name = $result['name']; ?>
                <?php echo "<section class='allResults'>"; ?>
                <?php echo "<p class='results-title' id='result-" . $result_order . "-id'>Owner: " . $owner . "</p>"; ?>
                <?php echo "<p class='results-content' id='result-" . $result_order . "-content'><strong>Name:</strong> " . $name . "</p>"; ?>
                <?php echo "<button type='button' class='results-link' id='result-" . $result_order . "-link' onclick=\"gotoLink('/portfolios/" . $result['str_name'] . "/" . $result['str_name'] . "_portfolio.php')\"> Goto '" . $result['name'] . "' portfolio</button>"; ?>
                <?php echo "</section>"; ?>
                <?php $result_order++; ?>
            <?php endforeach; ?>
            <?php echo "</div>"; ?>
            <?php echo "<h5 class='result-count'>Found " . count($results) . " result(s).</h5>"; ?>
        <?php endif; ?>
    <?php elseif ($search !== ''): ?>
        <?php echo "<h3 class='result-title'>No results found. Try a different query!</h3>"; ?>
    <?php endif; ?>

    <div hidden="true" id="user-prof" data-user-id="<?php echo htmlspecialchars($_SESSION['uuid']); ?>" data-is-logged="<?php echo htmlspecialchars($_SESSION['is_logged']); ?>"></div>

    <script type="text/javascript" src="../../button1.js"></script>
</body>
</html>
