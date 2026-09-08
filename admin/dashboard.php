<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Restrict access: Only allow users with the 'admin' role
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

require '../database/db.php';
require '../database/e_commerce.php';
require '../database/users.php';

$message = '';

// Handle POST requests for stock or role updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_stock'])) {
        $product_id = (int)$_POST['product_id'];
        $new_stock = (int)$_POST['new_stock'];
        updateProductStock($pdo, $product_id, $new_stock);
        $message = "Product stock successfully updated!";
    } elseif (isset($_POST['update_role'])) {
        $target_user_id = (int)$_POST['user_id'];
        $new_role = $_POST['role'];
        updateUserRole($pdo, $target_user_id, $new_role);
        $message = "User role successfully updated!";
    }
}

// Fetch data for the view
$products = getAllProducts($pdo);
$users = getAllUsers($pdo);
$base_path = '../';

// Include the separate HTML view file
include 'index.php';
?>