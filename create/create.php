<?php
session_start();
require_once '/search/database-access.php';


$dir = '';
$message = '';
$pagecontent = '';

if (isset($_POST['dirName']) && !empty(trim($_POST["dirName"]))){
    $trimmed_dir = trim($_POST['dirName']);
    if (!empty($trimmed_dir)) {
        global $pdo;
        $pagecontent = '<!DOCTYPE html>\n' .
                        '<html lang="en">\n' .
                        '<head>\n' .
                        '   <meta charset="UTF-8">\n' .
                        '   <title>' . $trimmed_dir  . '</title>\n' .
                        '   <link rel="stylesheet" href="/portfolios/' . $trimmed_dir . '/' . $trimmed_dir .'_portfolio.css">\n' .
                        '</head>\n' .
                        '<body>\n' .
                        '   <h1 id="portfolio-title" contenteditable="true">Welcome to the ' . $trimmed_dir . 'page!</h1>\n' .
                        '</body>\n';
        $dir = $trimmed_dir;
        if ($trimmed_dir):
            if (!is_dir('../portfolios/' . $trimmed_dir)):
                mkdir('../portfolios/' . $trimmed_dir, 0755, true);
                $file = fopen('../portfolios/' . $trimmed_dir . '/' . $trimmed_dir . '_portfolio.html', 'w');
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
    <title>Website Search</title>
    <link rel="stylesheet" href="/create/create.css">
</head>
<body>
    <h1>Create Portfolio</h1>
    <?php if ($message): ?>
        <?php echo $message; ?>
    <?php endif; ?>
    <form class="form" action="/create/create.php" method="POST">
        <input type="text" name="dirName" required placeholder="Your name..." value="<?php echo htmlspecialchars($dir); ?>">
        <button type="submit">Create page</button>
    </form>
    <button onclick="gotoLink('..')" type="button" id="button1">Back to home page</button>
    <script type="text/javascript" src="../../button1.js"></script>
</body>
</html>
