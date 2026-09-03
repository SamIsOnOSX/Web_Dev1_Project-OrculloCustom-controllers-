<?php
// db.php
$host = 'localhost';
$db = 'customcontroller_db'; // Database name ni please
$user = 'root';
$pass = ''; // blank = no password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>