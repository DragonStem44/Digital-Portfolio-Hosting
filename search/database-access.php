<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname1 = 'website';

try {
    $pdo_test = new PDO("mysql:host=$host;dbname=$dbname1;charset=utf8mb4", $username, $password);
    $pdo_test->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB connection fail: " . $e->getMessage());
}
