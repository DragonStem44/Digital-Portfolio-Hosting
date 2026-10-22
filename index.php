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
</head>
<body>
    <h1>Home page</h1>
    <div hidden="true" id="user-prof" data-user-id="<?php echo htmlspecialchars($_SESSION['user_id']); ?>" data-is-logged="<?php echo htmlspecialchars($_SESSION['is_logged']); ?>"></div>
    <p>Link buttons below. You must be logged in to search, create, or insert.</p>

    <button onclick="gotoLink('/search/search.php')" type="button" id="button1">Search</button>

    <button onclick="const usrEl = document.getElementById('user-id');
    const usrLogged = usrEl.dataset.isLogged;
    if (usrLogged) {
      gotoLink('/create/create.php')}"
    type="button" id="button2">
        Create a Portfolio
    </button>

    <button onclick="const usrEl = document.getElementById('user-id');
    const usrLogged = usrEl.dataset.isLogged;
    if (usrLogged) {
      gotoLink('/insert/insert.php')}"
    type="button" id="button3">
        Insert into DB
    </button>


    <button onclick="gotoLink('/signup/signup.php')" type="button" id="button4">Signup</button>
    <button onclick="gotoLink('/login/login.php')" type="button" id="button5">Login</button>
    <script type="text/javascript" src="/button1.js"></script>
</body>
</html>
