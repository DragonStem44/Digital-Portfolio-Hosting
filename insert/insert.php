<?php
require_once '/Applications/ServBay/www/testwebsite/search/database-access.php';

$insert = '';
$message = '';
if (session_status() === PHP_SESSION_NONE) {
    session_start([
    'cookie_lifetime' => 0,
    'cookie_secure'   => true,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Strict'
]);
}
if (isset($_POST['content']) && !empty(trim($_POST["content"]))){
    $trimmed_content = trim($_POST['content']);
    if (!empty($trimmed_content)) {
        $insert = $trimmed_content;
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO test (content) VALUES (?)");
        $stmt->execute([$insert]);
        $_SESSION['flash_message'] = "Successfully inserted '" . $trimmed_content . "'!";
        header("Location: /insert/insert.php");
        exit();
    }
}
if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Insert</title>
</head>
<body>
    <h1>Insert text into "test" database</h1>
    <?php if ($message): ?>
        <?php echo "<p class='result'>" . $message . "</p>"; ?>
    <?php endif; ?>
    <form action="insert.php" method="POST">
        <input type="text" name="content" required placeholder="Content to insert.." value="<?php echo htmlspecialchars($insert); ?>">
        <button type="submit">Insert</button>
    </form>

    <div hidden="true" id="user-prof" data-user-id="<?php echo htmlspecialchars($_SESSION['user_id']); ?>" data-is-logged="<?php echo htmlspecialchars($_SESSION['is_logged']); ?>"></div>
    <p>Link buttons below</p>


    <button onclick="gotoLink('/index.php')" type="button" id="button1">Back to home page</button>

    <button onclick="gotoLink('/search/search.php')" type="button" id="button2">Search</button>

    <button onclick="const usrEl = document.getElementById('user-prof');
    const usrLogged = usrEl.dataset.isLogged;
    if (usrLogged) {
      gotoLink('/create/create.php')
    }')" type="button" id="button4">Create a Portfolio</button>

    <button onclick="gotoLink('/signup/signup.php')" type="button" id="button5">Signup</button>

    <button onclick="gotoLink('login/login.php')" type="button" id="button6">Login</button>

    <script type="text/javascript" src="/button1.js"></script>
</body>
</html>
