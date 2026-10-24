<?php
require_once '/Applications/ServBay/www/testwebsite/search/database-access.php';

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

    if (empty($username) || empty($plain_password)) {
        die("Please fill in all the fields.");
    }
    if (strlen($username) > 24 || strlen($plain_password) > 24) {
        die("Error: Username and password must be 24 characters or less.");
    }
    if (strlen($plain_password) < 8) {
        die("Error: Password must be at least 8 characters long.");
    }
    if (preg_match('/[^\x20-\x7E]/', $plain_password)) {
        die("Error: Password can only contain standard alphanumeric characters and basic keyboard symbols.");
    }
    $hashed_password = password_hash($plain_password, PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (uuid, username, password) VALUES (UUID_TO_BIN(UUID()), :username, :password)");
        $stmt->execute([
            'username' => $username,
            'password' => $hashed_password
        ]);
        echo "Registration successful.";
    } catch (PDOException $e) {

        if ($e->getCode() == 23000) {
            die("Username already exists. Did you mean to log in?");
        }
            die("Database error: " . $e->getMessage());
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
</head>
<body>
    <h1>Signup</h1>
    <?php if (isset($_SESSION['user'])): ?>
        <?php echo "<p id='user_display'>Logged in as " . $_SESSION['user'] . ".</p>"; ?>
    <?php endif; ?>
    <p>Passwords and usernames are CASE SENSITIVE!</p>
    <!-- Signup form -->
    <form method="POST" action="signup.php">
        <label for="username">Username (Max 24 characters):</label><br>
        <input type="text" id="username" name="username" maxlength="24" required><br><br>

        <label for="password">Password (8-24 characters, standard symbols allowed):</label><br>
        <input type="password" id="password" name="password" minlength="8" maxlength="24" required><br><br>

        <button type="submit">Sign Up</button>
    </form>
    <br>
    <!-- Signup form end -->
    <div hidden="true" id="user-prof" data-user-id="<?php echo htmlspecialchars($_SESSION['uuid']); ?>" data-is-logged="<?php echo htmlspecialchars($_SESSION['is_logged']); ?>"></div>
    <p>Link buttons below</p>


    <div id="links">
    <button onclick="gotoLink('/index.php')" type="button" id="button1">Back to home page</button>

    <button onclick="gotoLink('/search/search.php')" type="button" id="button2">Search</button>

    <button onclick="const usrEl = document.getElementById('user-prof');
    const usrLogged = usrEl.dataset.isLogged;
    console.log('testing');
    if (usrLogged === 'true') {
      gotoLink('/insert/insert.php')
      console.log('true');
    }" type="button" id="button3">Insert into DB</button>

    <button onclick="const usrEl = document.getElementById('user-prof');
    const usrLogged = usrEl.dataset.isLogged;
    if (usrLogged === 'true') {
      gotoLink('/create/create.php')
    }" type="button" id="button4">Create a Portfolio</button>

    <button onclick="gotoLink('/login/login.php')" type="button" id="button5">Login</button>
    </div>

    <script type="text/javascript" src="/button1.js"></script>

</body>
</html>
