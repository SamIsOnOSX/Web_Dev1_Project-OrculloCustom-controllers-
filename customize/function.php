<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Adjust path to reach database folder from inside 'customize/'
require_once '../database/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller_type = $_POST['controller_type'] ?? 'arcade_stick';
    $button_color    = $_POST['button_color'] ?? 'black';
    
    $prices = [
        'arcade_stick' => 150.00,
        'leverless'    => 160.00,
        'gamepad'      => 120.00
    ];
    $price = $prices[$controller_type] ?? 150.00;

    $artwork_path = null;
    if (isset($_FILES['custom_artwork']) && $_FILES['custom_artwork']['error'] === UPLOAD_ERR_OK) {
        // Save upload one level up in the root uploads directory
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $file_name = time() . '_' . basename($_FILES['custom_artwork']['name']);
        $artwork_path = 'uploads/' . $file_name;
        move_uploaded_file($_FILES['custom_artwork']['tmp_name'], $upload_dir . $file_name);
    }

    $custom_item = [
        'type'         => $controller_type,
        'button_color' => $button_color,
        'artwork_path' => $artwork_path,
        'price'        => $price
    ];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][] = $custom_item;

    $message = "Custom build successfully added to your cart!";
}
?>