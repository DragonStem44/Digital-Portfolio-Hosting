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
$dir = '';
$message = '';
$pagecontent = '';

if (isset($_POST['dirName']) && !empty(trim($_POST["dirName"]))){
    $trimmed_dir = trim($_POST['dirName']);
    if (!empty($trimmed_dir)) {
        global $pdo;
        $stripped_dir = preg_replace("/\s+/", '', $trimmed_dir);
        $pagecontent = "<!DOCTYPE html>\n" .
                        "<html lang='en'>\n" .
                        "<head>\n".
                        "   <meta charset'UTF-8'>\n" .
                        "   <title>" . $stripped_dir  . "</title>\n" .
                        "   <link rel='stylesheet' href='../../style/template1.css'>\n" .
                        "</head>\n" .
                        "<body contenteditable='true'>\n" .
                        "   <h1 id='portfolio-title'>Welcome to the " . $trimmed_dir . " page!</h1>\n" .
                        "   <p id='portfolio-content'>Content will go here</p>\n" .
                        "</body>";
        if ($stripped_dir):
            $_SESSION['stripped_dir'] = $stripped_dir;
            if (!is_dir('../portfolios/' . $stripped_dir)):
                mkdir('../portfolios/' . $stripped_dir, 0755, true);
                $file = fopen('../portfolios/' . $stripped_dir . '/' . $stripped_dir . '_portfolio.html', 'w');
                fwrite($file, $pagecontent);
                $_SESSION['flash_message'] = "<h2>Successfully created the '" . $trimmed_dir . "' page!</h2>";
            else:
                $_SESSION['flash_message'] = '<h2>The directory already exists. Perhaps you already made it, or someone else has your name!</h2>';
            endif;
        endif;

        header("Location: /create/create.php");
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
    <title>Create Portfolio</title>
</head>
<body>
    <h1>Create Portfolio</h1>
    <?php if (isset($_SESSION['user'])): ?>
        <?php echo "<p id='user_display'>Logged in as " . $_SESSION['user'] . ".</p>"; ?>
    <?php endif; ?>
    <p>The name entered below will be the name used to search for this portfolio. IT CANNOT BE CHANGED ONCE CREATED.</p>
    <?php if ($message): ?>
        <?php echo $message; ?>
    <?php endif; ?>
    <form class="form" action="/create/create.php" method="POST">
        <input type="text" name="dirName" required placeholder="Your name..." value="<?php echo htmlspecialchars($dir); ?>">
        <button type="submit">Create page</button>
    </form>
    <?php if (isset($_SESSION['stripped_dir'])): ?>
        <?php echo '<button onclick="gotoLink(\'../portfolios/' . $_SESSION['stripped_dir'] . '/' . $_SESSION['stripped_dir'] . '_portfolio.html\')" type="button" id="portfolio-button">Go to the \'' . $_SESSION['stripped_dir'] . '\' portfolio</button>'; ?>
        <?php unset($_SESSION['stripped_dir']); ?>
    <?php endif; ?>


    <div hidden="true" id="user-prof" data-user-id="<?php echo htmlspecialchars($_SESSION['uuid']); ?>" data-is-logged="<?php echo htmlspecialchars($_SESSION['is_logged']); ?>"></div>


    <div id="links">
    <button onclick="gotoLink('index.php')" type="button" id="button1">Signup</button>

    <button onclick="gotoLink('search/search.php')" type="button" id="button2">Search</button>

    <button onclick="const usrEl = document.getElementById('user-prof');
    const usrLogged = usrEl.dataset.isLogged;
    if (usrLogged === "true") {
      gotoLink('insert/insert.php')
    }" type="button" id="button3">Insert into DB</button>

    <button onclick="gotoLink('signup/signup.php')" type="button" id="button5">Signup</button>

    <button onclick="gotoLink('login/login.php')" type="button" id="button6">Login</button>
    </div>

    <script type="text/javascript" src="../button1.js"></script>
</body>
</html>
