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

$is_logged_in = isset($_SESSION['is_logged']) && ($_SESSION['is_logged'] === true || $_SESSION['is_logged'] === "true");

if ($is_logged_in) {
    if (isset($_POST['dirName']) && !empty(trim($_POST["dirName"]))) {
        $trimmed_dir = trim($_POST['dirName']);

        if (preg_match('/^[a-zA-Z ]+$/', $trimmed_dir)) {
            global $pdo;
            $stripped_dir = preg_replace("/\s+/", '', $trimmed_dir);

            $pagecontent = "<!DOCTYPE html>\n" .
                            "<html lang='en'>\n" .
                            "<head>\n".
                            "   <meta charset='UTF-8'>\n" .
                            "   <title>" . htmlspecialchars($stripped_dir, ENT_QUOTES, 'UTF-8')  . "</title>\n" .
                            "   <link rel='stylesheet' href='../../style/template1.css'>\n" .
                            "</head>\n" .
                            "<body contenteditable='true'>\n" .
                            "   <h1 id='portfolio-title'>Welcome to the " . htmlspecialchars($trimmed_dir, ENT_QUOTES, 'UTF-8') . " page!</h1>\n" .
                            "   <p id='portfolio-content'>Content will go here</p>\n" .
                            "</body>";

            try {
                $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM portfolio WHERE owner = :usrname LIMIT 1");
                $stmt2->execute(['usrname' => $_SESSION['user']]);

                if ($stmt2->fetchColumn() === 0) {
                    $dir_path = '../portfolios/' . $stripped_dir;

                    if (!is_dir($dir_path)) {
                        if (mkdir($dir_path, 0755, true)) {
                            $file_path = $dir_path . '/' . $stripped_dir . '_portfolio.html';
                            if (file_put_contents($file_path, $pagecontent) !== false) {
                                try {
                                    $stmt = $pdo->prepare("INSERT INTO portfolio (name, owner, str_name) VALUES (:name, :owner, :str_name)");
                                    $stmt->execute([
                                        'name' => $trimmed_dir,
                                        'owner' => $_SESSION['user'],
                                        'str_name' => $stripped_dir
                                    ]);

                                    $_SESSION['stripped_dir'] = $stripped_dir;
                                    $_SESSION['flash_message'] = "<h2>Successfully created the '" . htmlspecialchars($trimmed_dir, ENT_QUOTES, 'UTF-8') . "' page!</h2>";
                                } catch (PDOException $e) {
                                    @unlink($file_path);
                                    @rmdir($dir_path);
                                    $_SESSION['flash_message'] = "<h2>Database error occurred while creating portfolio.</h2>";
                                }
                            } else {
                                @rmdir($dir_path);
                                $_SESSION['flash_message'] = "<h2>Failed to write portfolio file.</h2>";
                            }
                        } else {
                            $_SESSION['flash_message'] = "<h2>Failed to create directory.</h2>";
                        }
                    } else {
                        $_SESSION['flash_message'] = '<h2>The directory already exists. Perhaps you already made it, or someone else has your name!</h2>';
                    }
                } else {
                    $_SESSION['flash_message'] = '<h2>This user has already created a directory. Try searching for it!</h2>';
                }
            } catch (PDOException $e) {
                $_SESSION['flash_message'] = "<h2>An unexpected error occurred.</h2>";
            }
        } else {
            $_SESSION['flash_message'] = "<h2>The portfolio name can only contain letters and spaces.</h2>";
        }

        header("Location: /create/create.php");
        exit();
    }
}

$message = null;
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
    <?php if ($is_logged_in && isset($_SESSION['user'])): ?>
        <p id='user_display'>Logged in as <?php echo htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8'); ?>.</p>
    <?php endif; ?>

    <p>The name entered below will be the name used to search for this portfolio. IT CANNOT BE CHANGED ONCE CREATED.<br>Letters and spaces only, no symbols.</p>

    <?php if ($message): ?>
        <?php echo $message; ?>
    <?php endif; ?>

    <?php if ($is_logged_in): ?>
        <form class="form" action="/create/create.php" method="POST">
            <input type="text" oninput="this.value = this.value.replace(/[^a-zA-Z ]/g, '')" autocomplete="off" name="dirName" required placeholder="Your name..." value="<?php echo htmlspecialchars($dir, ENT_QUOTES, 'UTF-8'); ?>">
            <button type="submit">Create page</button>
        </form>

        <?php if (isset($_SESSION['stripped_dir'])): ?>
            <?php
                $safe_stripped = htmlspecialchars($_SESSION['stripped_dir'], ENT_QUOTES, 'UTF-8');
                echo '<button onclick="gotoLink(\'../portfolios/' . $safe_stripped . '/' . $safe_stripped . '_portfolio.html\')" type="button" id="portfolio-button">Go to the \'' . $safe_stripped . '\' portfolio</button>';
                unset($_SESSION['stripped_dir']);
            ?>
        <?php endif; ?>

        <div id="links">
            <button onclick="gotoLink('../index.php')" type="button" id="button-home">Back to home</button>
            <button onclick="gotoLink('/search/search.php')" type="button" id="button-search">Search</button>
            <button onclick="gotoLink('/insert/insert.php')" type="button" id="button-insert">Insert into DB</button>
            <button onclick="gotoLink('/signup/signup.php')" type="button" id="button-signup">Signup</button>
            <button onclick="gotoLink('/login/login.php')" type="button" id="button-login">Login</button>
            <button onclick="gotoLink('/logout/logout.php')" type="button" id="button-logout">Logout</button>
        </div>

        <script type="text/javascript" src="../button1.js"></script>
    <?php else: ?>
        <p>You are not logged in.</p>
        <div id="links">
            <button onclick="gotoLink('/login/login.php')" type="button" id="button-login">Login</button>
            <button onclick="gotoLink('/signup/signup.php')" type="button" id="button-signup">Signup</button>
        </div>
        <script type="text/javascript" src="../button1.js"></script>
    <?php endif; ?>
</body>
</html>
