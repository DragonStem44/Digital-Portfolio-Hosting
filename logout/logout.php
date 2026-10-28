<?php
require_once __DIR__ . '/../search/database-access.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 0,
        'cookie_secure'   => true,
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict'
    ]);
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();

        header("Location: ../index.php");
        exit;
    }

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
</head>
<body>
    <h1>Logout</h1>
    <?php if (isset($_SESSION['user'])): ?>
        <?php echo "<p id='user_display'>Logged in as " . $_SESSION['user'] . ".</p>"; ?>
    <?php endif; ?>
    <form method="POST">
        <button type="submit" name="logout">Logout</button>
    </form>
    <!-- Login form end -->
    <div hidden="true" id="user-prof" data-user-id="<?php echo htmlspecialchars($_SESSION['uuid']); ?>" data-is-logged="<?php echo htmlspecialchars($_SESSION['is_logged']); ?>"></div>

    <p>Link buttons below</p>


    <div id="links">
    <button onclick="gotoLink('/index.php')" type="button" id="button1">Back to home page</button>

    <button onclick="gotoLink('/search/search.php')" type="button" id="button2">Search</button>

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
    </div>

    <script type="text/javascript" src="/button1.js"></script>
</body>
</html>
