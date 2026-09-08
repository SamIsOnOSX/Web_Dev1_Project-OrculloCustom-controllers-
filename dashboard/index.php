<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../database/db.php';
require_once '../database/e_commerce.php';
require_once '../database/users.php';
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