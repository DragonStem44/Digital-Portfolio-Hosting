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

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $plain_password = $_POST['password'] ?? '';

    if (empty($username) || empty($plain_password)) {
        $error = "Please fill in all the fields.";
    } elseif (strlen($username) > 24 || strlen($plain_password) > 24) {
        $error = "Error: Username and password must be 24 characters or less.";
    } elseif (strlen($plain_password) < 8) {
        $error = "Error: Password must be at least 8 characters long.";
    } elseif (preg_match('/[^\x20-\x7E]/', $plain_password)) {
        $error = "Error: Password can only contain standard alphanumeric characters and basic keyboard symbols.";
    } else {
        $hashed_password = password_hash($plain_password, PASSWORD_BCRYPT);
        global $pdo;
        try {
            $stmt = $pdo->prepare("INSERT INTO users (uuid, username, password) VALUES (UUID_TO_BIN(UUID()), :username, :password)");
            $stmt->execute([
                'username' => $username,
                'password' => $hashed_password
            ]);
            $success = "Registration successful. You can now login!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Username already exists. Did you mean to log in?";
            } else {
                $error = "Database error occurred.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/signup/signup.css">
</head>
<body>
    <!-- Top Bar containing title and navigation links -->
    <header id="top-bar">
        <h1 id="top-title">Signup</h1>
        <div id="links">
            <button onclick="gotoLink('/index.php')" type="button" id="button1">Back to home</button>
            <button onclick="gotoLink('/search/search.php')" type="button" id="button2">Search</button>
            <button onclick="gotoLink('/login/login.php')" type="button" id="button5">Login</button>
        </div>
    </header>

    <?php if (isset($_SESSION['user'])): ?>
        <?php if ($_SESSION['user'] === 'Guest'): ?>
            <?php echo "<p id='user-display'>Not logged in.</p>"; ?>
        <?php else: ?>
            <?php echo "<p id='user-display'>Logged in as " . htmlspecialchars($_SESSION['user']) . ".</p>"; ?>
        <?php endif; ?>
    <?php endif; ?>

    <form class="form" method="POST" action="signup.php">
        <p style="margin-top: 0; font-size: 0.9rem; color: #94A3B8;">Passwords and usernames are CASE SENSITIVE!</p>

        <?php if (!empty($error)): ?>
            <p style="color: #f87171; margin-top: 0;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <p style="color: #4ade80; margin-top: 0;"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <label for="username">Username (Max 24 characters):</label>
        <input type="text" id="username" name="username" maxlength="24" autocomplete="off" required>

        <div class="password-wrapper">
            <label for="password">Password (8-24 characters):</label>
            <input type="password" id="password" name="password" minlength="8" maxlength="24" required>

            <button type="button" id="show-password">Show</button>
        </div>
        <script>
          const passwordInput = document.getElementById('password');
          const toggleButton = document.getElementById('show-password');

          toggleButton.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';

            passwordInput.type = isPassword ? 'text' : 'password';

            toggleButton.textContent = isPassword ? 'Hide' : 'Show';
          });
        </script>

        <button type="submit">Sign Up</button>

    </form>

    <div hidden="true" id="user-prof" data-user-id="<?php echo htmlspecialchars($_SESSION['uuid'] ?? ''); ?>" data-is-logged="<?php echo htmlspecialchars($_SESSION['is_logged'] ?? 'false'); ?>"></div>

    <script type="text/javascript" src="/button1.js"></script>
</body>
</html>
