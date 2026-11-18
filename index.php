<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start([
    'cookie_lifetime' => 0,
    'cookie_secure'   => true,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Strict'
]);
}


if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = 'Guest';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/index.css">
</head>
<body>
    <!-- Top Bar containing title and navigation links -->
    <header id="top-bar">
        <h1 id="top-title">Home</h1>
        <div id="links">
            <button onclick="gotoLink('/search/search.php')" type="button" id="button1">Search</button>

            <button onclick="const usrEl = document.getElementById('user-prof');
            const usrLogged = usrEl.dataset.isLogged;
            if (usrLogged === 'true') {
              gotoLink('/create/create.php')}"
            type="button" id="button2">
                Create a Portfolio
            </button>

            <button onclick="gotoLink('/signup/signup.php')" type="button" id="button4">Signup</button>
            <button onclick="gotoLink('/login/login.php')" type="button" id="button5">Login</button>

            <button onclick="const usrEl = document.getElementById('user-prof');
            const usrLogged = usrEl.dataset.isLogged;
            if (usrLogged === 'true') {
              gotoLink('/logout/logout.php')
            }" type="button" id="button6">Logout</button>
        </div>
    </header>

    <?php if (isset($_SESSION['user'])): ?>
        <?php if ($_SESSION['user'] === 'Guest'): ?>
            <?php echo "<p id='user-display'>Not logged in.</p>"; ?>
        <?php else: ?>
            <?php echo "<p id='user-display'>Logged in as " . $_SESSION['user'] . ".</p>"; ?>
        <?php endif; ?>
    <?php endif; ?>
    <div class="text">
        <h1 class="welcome">Welcome to D-PHP!</h1>
        <h4 class="credits">Made by Sam Hoeben<br>DragonStem44 on Github</h4>

        <h2 class="info">D-PHP stands for Digital Portfolio Hosting Platform.</h2>
        <p class="subinfo">It is also coded nearly entirely in PHP, with minimal javascript or non-dynamic HTML.<br>The platform was made as a resource to students to easily create a professional looking digital portfolio.<br>Many current digital portfolio services do not include templates, or if they do they are paid.<br>D-PHP solves this problem, giving templates for free with easy editing and saving!</p>
        <br><br>
        <h3 class="instructions">You can start by clicking "Signup" in the top right!<br>Once you've signed up, login and you can create a portfolio.</h3>
    </div>
    <div hidden="true" id="user-prof" data-user-id="<?php echo htmlspecialchars($_SESSION['uuid'] ?? ''); ?>" data-is-logged="<?php echo htmlspecialchars($_SESSION['is_logged'] ?? 'false'); ?>"></div>

    <script type="text/javascript" src="/button1.js"></script>
</body>
</html>
