<?php
// db.php
$host = 'localhost';
$db = 'customcontroller_db'; // Database name ni please
$user = 'root';
$pass = ''; // blank = no password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch (PDOException $e) {
    error_log("Database Connection Error: " . $e->getMessage()); // Logs to server
    die("A system error occurred. Please try again later."); // Safe user message
}
?>