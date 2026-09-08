<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_path = '../';

// Handle adding custom items from the customizer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart_custom'])) {
    $type = $_POST['type'] ?? 'arcade_stick';
    
    // Dynamic base pricing depending on controller type selected
    $price = 150.00;
    if ($type === 'leverless') {
        $price = 160.00;
    } elseif ($type === 'gamepad') {
        $price = 120.00;
    }

    // Handle optional file upload for custom artwork
    $artwork_path = '';
    if (isset($_FILES['custom_artwork']) && $_FILES['custom_artwork']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $file_name = time() . '_' . basename($_FILES['custom_artwork']['name']);
        $target_file = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES['custom_artwork']['tmp_name'], $target_file)) {
            $artwork_path = 'uploads/' . $file_name; // Relative path for viewing
        }
    }

    $item = [
        'type' => $type,
        'button_color' => $_POST['button_color'] ?? 'black',
        'price' => $price,
        'artwork_path' => $artwork_path
    ];
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    $_SESSION['cart'][] = $item;
    
    header("Location: cart.php");
    exit();
}

// Handle regular shop item additions (if you used the shop fix earlier)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $item = [
        'type' => $_POST['type'] ?? 'Controller',
        'button_color' => $_POST['button_color'] ?? 'Standard',
        'price' => (float)($_POST['price'] ?? 0.00),
        'artwork_path' => ''
    ];
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    $_SESSION['cart'][] = $item;
    
    header("Location: cart.php");
    exit();
}

// Handle item removal
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $index = (int)$_GET['remove'];
    
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); 
    }
    
    header("Location: cart.php");
    exit();
}

$cart_items = $_SESSION['cart'] ?? [];
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'];
}

include 'index.php';
?>