<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_path = '../';

require_once '../database/db.php';
require_once '../database/e_commerce.php';

$cart_error = '';

/**
 * Validates and saves a custom artwork upload to the root uploads/ directory.
 * Mirrors the dashboard's saveUploadedProductImage() for consistency.
 *
 * @param array $file  Entry from $_FILES
 * @return array ['path' => string, 'error' => string]
 */
function saveUploadedArtwork(array $file): array {
    $allowed_exts  = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

    $file_info = pathinfo($file['name']);
    $file_ext  = strtolower($file_info['extension'] ?? '');

    if (!in_array($file_ext, $allowed_exts, true)) {
        return ['path' => '', 'error' => 'Invalid file type. Allowed formats: JPG, PNG, WEBP, GIF.'];
    }

    $finfo     = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime_type, $allowed_mimes, true)) {
        return ['path' => '', 'error' => 'Uploaded file is not a valid image.'];
    }

    $upload_dir = dirname(__DIR__) . '/uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $safe_filename = time() . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $file_info['filename']) . '.' . $file_ext;
    $target_dest   = $upload_dir . $safe_filename;

    if (move_uploaded_file($file['tmp_name'], $target_dest)) {
        return ['path' => 'uploads/' . $safe_filename, 'error' => ''];
    }

    return ['path' => '', 'error' => 'Failed to save uploaded artwork.'];
}

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
        $upload_result = saveUploadedArtwork($_FILES['custom_artwork']);
        if (!empty($upload_result['error'])) {
            $_SESSION['cart_error'] = $upload_result['error'];
            header('Location: ../customize/index.php');
            exit();
        }
        $artwork_path = $upload_result['path'];
    }

    $item = [
        'product_id'   => null,
        'type'         => $type,
        'button_color' => $_POST['button_color'] ?? 'black',
        'price'        => $price,
        'quantity'     => 1,
        'artwork_path' => $artwork_path
    ];
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    $_SESSION['cart'][] = $item;
    
    header("Location: cart.php");
    exit();
}

// Handle regular shop item additions with quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity   = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;
    $type       = trim($_POST['type'] ?? 'Controller');
    $button_color = trim($_POST['button_color'] ?? 'Standard');
    $price      = (float)($_POST['price'] ?? 0.00);

    // Verify stock availability if product_id is provided
    if ($product_id > 0) {
        $prod = getProductById($pdo, $product_id);
        if ($prod) {
            $price = (float)$prod['price'];
            $type  = $prod['name'];
            $available_stock = (int)$prod['stock'];

            if ($available_stock <= 0) {
                $_SESSION['cart_error'] = "Sorry, '$type' is out of stock.";
                header("Location: " . $base_path . "shop/shop.php");
                exit();
            }

            // Cap requested quantity to available stock
            if ($quantity > $available_stock) {
                $quantity = $available_stock;
            }
        }
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // If already in cart as identical item, increase quantity up to stock
    $found = false;
    foreach ($_SESSION['cart'] as &$existing_item) {
        if (!empty($existing_item['product_id']) && $existing_item['product_id'] === $product_id) {
            $new_qty = ($existing_item['quantity'] ?? 1) + $quantity;
            if (isset($available_stock) && $new_qty > $available_stock) {
                $new_qty = $available_stock;
                $_SESSION['cart_error'] = "Limited to maximum available stock ($available_stock) for '$type'.";
            }
            $existing_item['quantity'] = $new_qty;
            $found = true;
            break;
        }
    }
    unset($existing_item);

    if (!$found) {
        $_SESSION['cart'][] = [
            'product_id'   => $product_id > 0 ? $product_id : null,
            'type'         => $type,
            'button_color' => $button_color,
            'price'        => $price,
            'quantity'     => $quantity,
            'artwork_path' => ''
        ];
    }
    
    header("Location: cart.php");
    exit();
}

// Handle updating cart quantities
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart_quantity'])) {
    $index = isset($_POST['cart_index']) ? (int)$_POST['cart_index'] : -1;
    $new_qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    if (isset($_SESSION['cart'][$index])) {
        if ($new_qty <= 0) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        } else {
            $pid = $_SESSION['cart'][$index]['product_id'] ?? null;
            if ($pid) {
                $prod = getProductById($pdo, $pid);
                if ($prod && $new_qty > (int)$prod['stock']) {
                    $new_qty = (int)$prod['stock'];
                    $_SESSION['cart_error'] = "Maximum available stock for '{$prod['name']}' is {$prod['stock']}.";
                }
            }
            $_SESSION['cart'][$index]['quantity'] = max(1, $new_qty);
        }
    }
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

if (!empty($_SESSION['cart_error'])) {
    $cart_error = $_SESSION['cart_error'];
    unset($_SESSION['cart_error']);
}

$cart_items = $_SESSION['cart'] ?? [];
$total_price = 0;
foreach ($cart_items as &$item) {
    if (!isset($item['quantity']) || $item['quantity'] < 1) {
        $item['quantity'] = 1;
    }
    $total_price += ($item['price'] * $item['quantity']);
}
unset($item);

include 'index.php';
?>