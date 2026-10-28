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

if (isset($_GET['query']) && !empty(trim($_GET["query"]))){
    $search = trim($_GET['query']);
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM portfolio WHERE name LIKE :query OR owner LIKE :query");
    $stmt->execute(['query' => '%' . $search . '%']);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

}

?>
<?php if ($_SESSION['is_logged'] === "true"): ?>
<?php
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
    <?php if (isset($_SESSION['user'])): ?>
        <?php echo "<p id='user_display'>Logged in as " . $_SESSION['user'] . ".</p>"; ?>
    <?php endif; ?>
    <form class="form" action="/search/search.php" method="GET">
        <input type="text" name="query" required placeholder="Search terms" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>

    <!--Results logic-->

    <?php if (count($results) > 0):?>
        <?php echo "<h3 class='result_title'>Results for <q>$search</q></h3>"?>
        <?php foreach ($results as $result): ?>
            <?php $owner = $result['owner']; ?>
            <?php $name = $result['name']; ?>
            <?php echo "<section class='allResults'>"; ?>
            <?php echo "<p class='results-title' id='result-" . $result_order . "-id'>Owner: " . $owner . "</p>"; ?>
            <?php echo "<p class='results-content' id='result-" . $result_order . "-content'><strong>Name:</strong> " . $name . "</p>"; ?>
            <?php echo "<button type='button' class='results-link' id='result-" . $result_order . "-link' onclick=\"gotoLink('/portfolios/" . $result['str_name'] . "/" . $result['str_name'] . "_portfolio.html')\"> Goto '" . $result['name'] . "' portfolio</button>"; ?>
            <?php echo "</section>"; ?>
            <?php $result_order++; ?>
        <?php endforeach; ?>
        <?php echo "<h5 class='result_count'>Found " . count($results) . " result(s).</h5>"; ?>
    <?php elseif ($search !== ''): ?>
        <?php echo "<h3 class='result_title'>No results found. Try a different query!</h3>"; ?>
        <?php echo "<h5 class='result_count'>Found " . count($results) . " result(s)."; ?>
    <?php endif; ?>

    <div hidden="true" id="user-prof" data-user-id="<?php echo htmlspecialchars($_SESSION['uuid']); ?>" data-is-logged="<?php echo htmlspecialchars($_SESSION['is_logged']); ?>"></div>

    <p>Link buttons below</p>
    <div id="links">
        <button onclick="gotoLink('/index.php')" type="button" id="button1">Back to home page</button>

        <button onclick="const usrEl = document.getElementById('user-prof');
        const usrLogged = usrEl.dataset.isLogged;
        if (usrLogged === 'true') {
          gotoLink('/insert/insert.php')
        }" type="button" id="button3">Insert into DB</button>

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
    <script type="text/javascript" src="../../button1.js"></script>
</body>
</html>
<?php else: ?>
    <?php echo "You are not logged in."; ?>
<?php endif; ?>
