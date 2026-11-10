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
            $strdir = htmlspecialchars($stripped_dir, ENT_QUOTES, 'UTF-8');
            $pagecontent = '<?php
        require_once \'/Applications/ServBay/www/testwebsite/search/database-access.php\';

        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                \'cookie_lifetime\' => 0,
                \'cookie_secure\'   => true,
                \'cookie_httponly\' => true,
                \'cookie_samesite\' => \'Strict\'
            ]);
        }

        $str_name = \'' . $stripped_dir . '\';
        $owner = \'\';
        try {
            global $pdo;
            $stmt = $pdo->prepare("SELECT owner FROM portfolio WHERE str_name = :str_name LIMIT 1");
            $stmt->execute([\'str_name\' => $str_name]);
            $owner = $stmt->fetchColumn() ?: \'\';
        } catch (PDOException $e) {
            $owner = \'\';
        }

        $is_owner = (isset($_SESSION[\'user\']) && $_SESSION[\'user\'] === $owner) || $_SESSION[\'user\'] === \'Admin\';

        if ($_SERVER[\'REQUEST_METHOD\'] === \'POST\' && $is_owner) {
            $input = json_decode(file_get_contents(\'php://input\'), true);
            if ($input !== null) {
                $json_file = __DIR__ . \'/content.json\';
                if (file_put_contents($json_file, json_encode($input, JSON_PRETTY_PRINT)) !== false) {
                    echo json_encode([\'success\' => true]);
                    exit;
                }
                echo json_encode([\'success\' => false, \'error\' => \'Could not save content\']);
                exit;
            }
        }

        $content_file = __DIR__ . \'/content.json\';
        $content = file_exists($content_file) ? json_decode(file_get_contents($content_file), true) : [];
        ?>
        <!DOCTYPE html>
        <html lang=\'en\'>
        <head>
            <meta charset=\'UTF-8\'>
            <meta name=\'viewport\' content=\'width=device-width, initial-scale=1.0\'>
            <title>' . $trimmed_dir . '</title>
            <link rel=\'stylesheet\' href=\'../../style/template1.css\'>
        </head>
        <body>
        <header class=\'portfolio-header\'>
            <div class=\'header-content\'>
                <h1 id=\'portfolio-title\' contenteditable="<?php echo $is_owner ? \'true\' : \'false\'; ?>"><?php echo isset($content[\'portfolio-title\']) ? $content[\'portfolio-title\'] : \'Welcome to the ' . $trimmed_dir . ' portfolio!\'; ?></h1>
                <p id=\'portfolio-tagline\' class=\'tagline\' contenteditable="<?php echo $is_owner ? \'true\' : \'false\'; ?>"><?php echo isset($content[\'portfolio-tagline\']) ? $content[\'portfolio-tagline\'] : \'Placeholder text\'; ?></p>
            </div>
        </header>

            <main class=\'portfolio-main\'>
                <section id=\'about\' class=\'portfolio-section\'>
                    <h2 contenteditable="<?php echo $is_owner ? \'true\' : \'false\'; ?>">About Me</h2>
                    <div id=\'about-content\' class=\'content-box\' contenteditable="<?php echo $is_owner ? \'true\' : \'false\'; ?>">
                        <?php echo isset($content[\'about-content\']) ? $content[\'about-content\'] : \'<p>Placeholder text</p>\'; ?>
                    </div>
                </section>

                <section id=\'projects\' class=\'portfolio-section\'>
                        <h2 contenteditable="<?php echo $is_owner ? \'true\' : \'false\'; ?>">Projects</h2>
                        <div class=\'projects-grid\'>
                            <div id=\'project-0\' class=\'content-box project-box\' contenteditable="<?php echo $is_owner ? \'true\' : \'false\'; ?>">
                                <?php echo isset($content[\'project-0\']) ? $content[\'project-0\'] : \'<h3>Project One</h3><p>Placeholder text</p>\'; ?>
                            </div>
                            <div id=\'project-1\' class=\'content-box project-box\' contenteditable="<?php echo $is_owner ? \'true\' : \'false\'; ?>">
                                <?php echo isset($content[\'project-1\']) ? $content[\'project-1\'] : \'<h3>Project Two</h3><p>Placeholder text</p>\'; ?>
                            </div>
                            <div id=\'project-2\' class=\'content-box project-box\' contenteditable="<?php echo $is_owner ? \'true\' : \'false\'; ?>">
                                <?php echo isset($content[\'project-2\']) ? $content[\'project-2\'] : \'<h3>Project Three</h3><p>Placeholder text</p>\'; ?>
                            </div>
                        </div>
                </section>

                <section id=\'education\' class=\'portfolio-section\'>
                    <h2 contenteditable="<?php echo $is_owner ? \'true\' : \'false\'; ?>">Education</h2>
                    <div id=\'education-content\' class=\'content-box education-box\' contenteditable="<?php echo $is_owner ? \'true\' : \'false\'; ?>">
                        <?php echo isset($content[\'education-content\']) ? $content[\'education-content\'] : \'<h3>Degree / Certification</h3><p>Placeholder text</p>\'; ?>
                    </div>
                </section>

                <section id=\'contact\' class=\'portfolio-section\'>
                    <h2>Contact</h2>
                    <div id=\'contact-content\' class=\'content-box contact-box\' contenteditable="<?php echo $is_owner ? \'true\' : \'false\'; ?>">
                        <?php echo isset($content[\'contact-content\']) ? $content[\'contact-content\'] : \'<p>Placeholder text</p>\'; ?>
                    </div>
                </section>
            </main>

        <?php if ($is_owner): ?>
        <div id="save-bar">
            <span id="save-status">Editing Mode</span>
            <button id="save-btn">Save Changes</button>
        </div>
        <script>
        document.getElementById(\'save-btn\').addEventListener(\'click\', function() {
            const btn = document.getElementById(\'save-btn\');
            const status = document.getElementById(\'save-status\');
            btn.textContent = \'Saving...\';

            const data = {
                \'portfolio-title\': document.getElementById(\'portfolio-title\').innerHTML,
                \'portfolio-tagline\': document.getElementById(\'portfolio-tagline\').innerHTML,
                \'about-content\': document.getElementById(\'about-content\').innerHTML,
                \'project-0\': document.getElementById(\'project-0\').innerHTML,
                \'project-1\': document.getElementById(\'project-1\').innerHTML,
                \'project-2\': document.getElementById(\'project-2\').innerHTML,
                \'education-content\': document.getElementById(\'education-content\').innerHTML,
                \'contact-content\': document.getElementById(\'contact-content\').innerHTML
            };

            fetch(window.location.href, {
                method: \'POST\',
                headers: {
                    \'Content-Type\': \'application/json\'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    btn.textContent = \'Saved!\';
                    status.textContent = \'All changes saved successfully.\';
                    setTimeout(() => { btn.textContent = \'Save Changes\'; }, 2000);
                } else {
                    btn.textContent = \'Error!\';
                    status.textContent = result.error || \'Failed to save.\';
                }
            })
            .catch(err => {
                btn.textContent = \'Error!\';
                status.textContent = \'Network error.\';
            });
        });
        </script>
        <?php endif; ?>
        <section id="links">
            <div id="link-buttons">
                <button onclick="gotoLink(\'../../signup/signup.php\')" type="button" id="button-home">Signup</button>
                <button onclick="gotoLink(\'../../index.php\')" type="button" id="button-home">Back to home</button>
                <button onclick="gotoLink(\'../../login/login.php\')" type="button" id="button-home">Login</button>
            </div>
        </section>
        <script type="text/javascript" src="../../button1.js"></script>
        </body>
        </html>
        ';

            try {
                $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM portfolio WHERE owner = :usrname LIMIT 1");
                $stmt2->execute(['usrname' => $_SESSION['user']]);

                if ($stmt2->fetchColumn() === 0) {
                    $dir_path = '../portfolios/' . $stripped_dir;

                    if (!is_dir($dir_path)) {
                        if (mkdir($dir_path, 0755, true)) {
                            $file_path = $dir_path . '/' . $stripped_dir . '_portfolio.php';
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
            <input type="text" oninput="this.value = this.value.replace(/[^a-zA-Z ]/g, '')" autocomplete="off" name="dirName" required placeholder="Portfolio name..." value="<?php echo htmlspecialchars($dir, ENT_QUOTES, 'UTF-8'); ?>">
            <button type="submit">Create page</button>
        </form>

        <?php if (isset($_SESSION['stripped_dir'])): ?>
            <?php
                $safe_stripped = htmlspecialchars($_SESSION['stripped_dir'], ENT_QUOTES, 'UTF-8');
                echo '<button onclick="gotoLink(\'../portfolios/' . $safe_stripped . '/' . $safe_stripped . '_portfolio.php\')" type="button" id="portfolio-button">Go to the \'' . $safe_stripped . '\' portfolio</button>';
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
