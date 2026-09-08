<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../database/db.php';
require '../database/e_commerce.php';
$base_path = '../'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$cart_items = $_SESSION['cart'] ?? [];

if (empty($cart_items)) {
    header("Location: ../cart/cart.php");
    exit();
}

$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += (float)$item['price'];
}

$user_id = $_SESSION['user_id'];
$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_checkout'])) {
    try {
        // Keep the transaction wrapper to ensure both order and items save together
        $pdo->beginTransaction();

        // 1. Create the parent order using your new function
        $order_id = createOrder($pdo, $user_id, $total_price);

        // 2. Loop through and save each item using your new function
        foreach ($cart_items as $item) {
            $type = $item['type'] ?? 'Unknown';
            $button_color = $item['button_color'] ?? 'Standard';
            $artwork_path = $item['artwork_path'] ?? '';
            
            createOrderItem($pdo, $order_id, $type, $button_color, $item['price'], $artwork_path);
        }

        // 3. Commit the save and clear the cart
        $pdo->commit();
        $_SESSION['cart'] = [];
        $success = true;

    } catch (Exception $e) {
        $pdo->rollBack();
        // Append the actual SQL error message to the output
        $error = "Checkout failed. Error: " . $e->getMessage();
    }
}

// Load the separated view
include 'index.php';
?>