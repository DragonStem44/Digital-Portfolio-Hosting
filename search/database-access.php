<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'testing_db';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB connection fail: " . $e->getMessage());
}
?>
