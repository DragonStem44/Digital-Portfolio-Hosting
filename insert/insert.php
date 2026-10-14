<?php
require_once '/Applications/ServBay/www/testwebsite/search/database-access.php';

$insert = '';
$message = '';
session_start();
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
    <title>Website Search</title>
    <link rel="stylesheet" href="search.css">
</head>
<body>
    <h1>Insert text into "test" database</h1>
    <p>This text goes into the test database<br>and is stored with an ascending id,<br>which can be searched through <a href="/search/search.php">here</a>.</p>
    <?php if ($message): ?>
        <?php echo "<p class='result'>" . $message . "</p>"; ?>
    <?php endif; ?>
    <form action="insert.php" method="POST">
        <input type="text" name="content" required placeholder="Content to insert.." value="<?php echo htmlspecialchars($insert); ?>">
        <button type="submit">Insert</button>
    </form>
    <button onclick="gotoLink('../index.html')" type="button" id="button1">Back to home page</button>
    <script type="text/javascript" src="/button1.js"></script>
</body>
</html>
