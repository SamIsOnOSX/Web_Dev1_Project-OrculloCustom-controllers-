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
foreach ($cart_items as &$item) {
    if (!isset($item['quantity']) || (int)$item['quantity'] < 1) {
        $item['quantity'] = 1;
    }
    $total_price += (float)$item['price'] * (int)$item['quantity'];
}
unset($item);

$user_id = $_SESSION['user_id'];
$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_checkout'])) {
    try {
        $payment_method = isset($_POST['payment_method']) && $_POST['payment_method'] === 'online' ? 'online' : 'cod';
        $payment_proof  = null;

        // If online payment, require and handle proof of payment upload
        if ($payment_method === 'online') {
            if (!isset($_FILES['payment_proof']) || $_FILES['payment_proof']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Please upload your proof of payment for online transactions.");
            }

            $file     = $_FILES['payment_proof'];
            $allowed_exts  = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
            $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];

            $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($file_ext, $allowed_exts)) {
                throw new Exception("Invalid file type. Allowed formats: JPG, PNG, WEBP, PDF.");
            }

            $finfo     = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mime_type, $allowed_mimes)) {
                throw new Exception("Uploaded file is not a valid image or PDF.");
            }

            if ($file['size'] > 5 * 1024 * 1024) { // 5 MB limit
                throw new Exception("Proof of payment file must be under 5 MB.");
            }

            $proof_dir = dirname(__DIR__) . '/uploads/payment_proofs/';
            if (!is_dir($proof_dir)) {
                mkdir($proof_dir, 0755, true);
            }

            $safe_name    = time() . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($file['name'], PATHINFO_FILENAME)) . '.' . $file_ext;
            $target_path  = $proof_dir . $safe_name;

            if (!move_uploaded_file($file['tmp_name'], $target_path)) {
                throw new Exception("Failed to save proof of payment. Please try again.");
            }

            $payment_proof = 'uploads/payment_proofs/' . $safe_name;
        }

        // Keep the transaction wrapper to ensure both order and items save together
        $pdo->beginTransaction();

        // 1. Stock check before placing order
        foreach ($cart_items as $item) {
            $product_id = isset($item['product_id']) ? (int)$item['product_id'] : 0;
            $qty = isset($item['quantity']) ? (int)$item['quantity'] : 1;

            if ($product_id > 0) {
                // Fetch latest stock using FOR UPDATE lock
                $stmt = $pdo->prepare("SELECT id, name, stock FROM products WHERE id = :id FOR UPDATE");
                $stmt->execute(['id' => $product_id]);
                $prod = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$prod) {
                    throw new Exception("Product '{$item['type']}' is no longer available.");
                }

                if ((int)$prod['stock'] < $qty) {
                    throw new Exception("Insufficient stock for '{$prod['name']}'. Only {$prod['stock']} available.");
                }
            }
        }

        // 2. Create the parent order (with payment info)
        $order_id = createOrder($pdo, $user_id, $total_price, $payment_method, $payment_proof);

        // 3. Save order items and reduce stock
        foreach ($cart_items as $item) {
            $type         = $item['type'] ?? 'Unknown';
            $button_color = $item['button_color'] ?? 'Standard';
            $artwork_path = $item['artwork_path'] ?? '';
            $price        = (float)$item['price'];
            $qty          = isset($item['quantity']) ? (int)$item['quantity'] : 1;
            $product_id   = isset($item['product_id']) ? (int)$item['product_id'] : 0;

            // Insert each item instance into order_items
            for ($i = 0; $i < $qty; $i++) {
                createOrderItem($pdo, $order_id, $type, $button_color, $price, $artwork_path);
            }

            // Deduct stock if linked to a shop product
            if ($product_id > 0) {
                reduceProductStock($pdo, $product_id, $qty);
            }
        }

        // 4. Commit the transaction and clear cart
        $pdo->commit();
        $_SESSION['cart'] = [];
        $success = true;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $error = $e->getMessage();
    }
}

// Load the separated view
include 'index.php';
?>