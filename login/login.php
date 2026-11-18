<?php
require_once '../search/database-access.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 0,
        'cookie_secure'   => true,
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict'
    ]);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        try {
            global $pdo;
            $stmt = $pdo->prepare("SELECT uuid, username, password FROM users WHERE username = :username LIMIT 1");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);

                $_SESSION['user'] = $user['username'];
                $_SESSION['uuid'] = $user['uuid'];
                $_SESSION['is_logged'] = 'true';

                header('Location: /index.php');
                exit();
            } else {
                $error = 'Invalid username or password.';
            }
        } catch (PDOException $e) {
            $error = 'Database error occurred.';
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/login/login.css">
</head>
<body>
    <!-- Top Bar containing title and navigation links -->
    <header id="top-bar">
        <h1 id="top-title">Login</h1>
        <div id="links">
            <button onclick="gotoLink('/index.php')" type="button" id="button1">Back to home</button>
            <button onclick="gotoLink('/search/search.php')" type="button" id="button2">Search</button>
            <button onclick="gotoLink('/signup/signup.php')" type="button" id="button3">Signup</button>
        </div>
    </header>

    <form class="form" action="/login/login.php" method="POST">
        <?php if (!empty($error)): ?>
            <p style="color: #f87171; margin-top: 0;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <div class="password-wrapper">
            <input type="text" id="username" name="username" required placeholder="Username" autocomplete="off">

            <input type="password" id="password" name="password" required placeholder="Password">
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
        <button id="login" type="submit">Login</button>
    </form>

    <script type="text/javascript" src="../button1.js"></script>
</body>
</html>
