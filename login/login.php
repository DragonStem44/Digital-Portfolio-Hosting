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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $plain_password = $_POST['password'];
    global $pdo;
    $stmt = $pdo->prepare("SELECT uuid, password FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($plain_password, $user['password'])) {
        session_regenerate_id(true);
        echo "success!";
        $_SESSION['user'] = $username;
        $_SESSION['uuid'] = $user['uuid'];
        $_SESSION['is_logged'] = "true";
    } else {
        $error = "Invalid email or password.";
        echo "error!";
    }
}
$username = '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <p>Passwords and usernames are CASE SENSITIVE!</p>
    <?php if (isset($_SESSION['user'])): ?>
        <?php echo "<p id='user_display'>Logged in as " . $_SESSION['user'] . ".</p>"; ?>
    <?php endif; ?>
    <!-- Login form -->
    <form method="POST">
        <input type="text" id="username" name="username" placeholder="Username:" required maxlength="24">
        <input type="password" id="password" name="password" placeholder="Password:" maxlength="24" minlength="8" required>
        <button type="submit">Login</button>
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

    <button onclick="const usrEl = document.getElementById('user-prof');
    const usrLogged = usrEl.dataset.isLogged;
    if (usrLogged === 'true') {
      gotoLink('/logout/logout.php')
    }" type="button" id="button5">Logout</button>
    </div>

    <script type="text/javascript" src="/button1.js"></script>
</body>
</html>
