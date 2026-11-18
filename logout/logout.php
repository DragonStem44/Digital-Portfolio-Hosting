<?php
require_once __DIR__ . '/../search/database-access.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 0,
        'cookie_secure'   => true,
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict'
    ]);
}

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/logout/logout.css">
</head>
<body>
    <!-- Top Bar containing title and navigation links -->
    <header id="top-bar">
        <h1 id="top-title">Logout</h1>
        <div id="links">
            <button onclick="gotoLink('/index.php')" type="button" id="button1">Back to home</button>
            <button onclick="gotoLink('/search/search.php')" type="button" id="button2">Search</button>
            <button onclick="gotoLink('/signup/signup.php')" type="button" id="button5">Signup</button>
            <button onclick="gotoLink('/login/login.php')" type="button" id="button6">Login</button>
        </div>
    </header>

    <?php if (isset($_SESSION['user'])): ?>
        <?php if ($_SESSION['user'] === 'Guest'): ?>
            <?php echo "<p id='user-display'>Not logged in.</p>"; ?>
        <?php else: ?>
            <?php echo "<p id='user-display'>Logged in as " . htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8') . ".</p>"; ?>
        <?php endif; ?>
    <?php endif; ?>

    <form class="form" method="POST">
        <p style="margin-top: 0; color: #94A3B8;">Are you sure you want to log out?</p>
        <button type="submit" name="logout">Logout</button>
    </form>

    <div hidden="true" id="user-prof" data-user-id="<?php echo htmlspecialchars($_SESSION['uuid'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-is-logged="<?php echo htmlspecialchars($_SESSION['is_logged'] ?? 'false', ENT_QUOTES, 'UTF-8'); ?>"></div>

    <script type="text/javascript" src="/button1.js"></script>
</body>
</html>
