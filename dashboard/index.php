<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../database/db.php';
require '../database/e_commerce.php';
require '../database/users.php';
$base_path = '../';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$role = $_SESSION['role'] ?? $_SESSION['user_role'] ?? 'user';

if ($role === 'admin') {
    include 'admin.php';
} else {
    include 'user.php';
}
?>