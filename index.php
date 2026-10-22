<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start([
    'cookie_lifetime' => 0,
    'cookie_secure'   => true,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Strict'
]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Title</title>
<body>
    <h1>Home page</h1>
    <?php if (isset($_SESSION['user'])): ?>
        <?php echo "<p id='user_display'>Logged in as " . $_SESSION['user'] . ".</p>"; ?>
    <?php endif; ?>
    <div hidden="true" id="user-prof" data-user-id="<?php echo htmlspecialchars($_SESSION['uuid']); ?>" data-is-logged="<?php echo htmlspecialchars($_SESSION['is_logged']); ?>"></div>
    <p>Link buttons below. You must be logged in to create or insert.</p>


    <div id="links">
    <button onclick="gotoLink('/search/search.php')" type="button" id="button1">Search</button>

    <button onclick="const usrEl = document.getElementById('user-prof');
    const usrLogged = usrEl.dataset.isLogged;
    if (usrLogged === "true") {
      gotoLink('/create/create.php')}"
    type="button" id="button2">
        Create a Portfolio
    </button>

    <button onclick="const usrEl = document.getElementById('user-prof');
    const usrLogged = usrEl.dataset.isLogged;
    if (usrLogged === "true") {
      gotoLink('/insert/insert.php')}"
    type="button" id="button3">
        Insert into DB
    </button>


    <button onclick="gotoLink('/signup/signup.php')" type="button" id="button4">Signup</button>
    <button onclick="gotoLink('/login/login.php')" type="button" id="button5">Login</button>
    </div>
    <script type="text/javascript" src="/button1.js"></script>
</body>
</html>
