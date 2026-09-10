<?php
/**
 * dashboard/admin_actions.php
 * Handles POST requests and business logic for the Admin Control Panel.
 */

require_once dirname(__DIR__) . '/database/e_commerce.php';
require_once dirname(__DIR__) . '/database/users.php';

/**
 * Dispatches and handles all admin POST operations.
 *
 * @param PDO $pdo Database connection instance
 * @return array ['message' => string, 'error' => string]
 */
function handleAdminPostActions(PDO $pdo): array {
    $result = ['message' => '', 'error' => ''];

    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        return $result;
    }

    // 1. Create New Product
    if (isset($_POST['create_product'])) {
        $result = processCreateProduct($pdo);
    }
    // 2. Delete Existing Product
    elseif (isset($_POST['delete_product'])) {
        $result = processDeleteProduct($pdo);
    }
    // 3. Update Product Stock
    elseif (isset($_POST['update_stock'])) {
        $result = processUpdateStock($pdo);
    }
    // 4. Update User Account
    elseif (isset($_POST['update_user'])) {
        $result = processUpdateUser($pdo);
    }
    // 5. Update Order Status
    elseif (isset($_POST['update_order'])) {
        $result = processUpdateOrder($pdo);
    }
    // 6. Admin changes a customer's password
    elseif (isset($_POST['admin_change_password'])) {
        $result = processChangeUserPassword($pdo);
    }

    return $result;
}

/**
 * Handles product creation form submission and image uploading.
 */
function processCreateProduct(PDO $pdo): array {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price_input = $_POST['price'] ?? '';
    $stock_input = $_POST['stock'] ?? '';

    if ($name === '') {
        return ['message' => '', 'error' => 'Product name is required.'];
    }
    if (!is_numeric($price_input) || (float)$price_input < 0) {
        return ['message' => '', 'error' => 'Please enter a valid price (0 or higher).'];
    }
    if (!is_numeric($stock_input) || (int)$stock_input < 0) {
        return ['message' => '', 'error' => 'Please enter a valid stock quantity (0 or higher).'];
    }

    $price = (float)$price_input;
    $stock = (int)$stock_input;
    $image_path = '';

    // Handle file upload if provided
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $upload_result = saveUploadedProductImage($_FILES['product_image']);
        if (!empty($upload_result['error'])) {
            return ['message' => '', 'error' => $upload_result['error']];
        }
        $image_path = $upload_result['path'];
    } elseif (!empty($_POST['existing_asset'])) {
        $image_path = trim($_POST['existing_asset']);
    }

    if (empty($image_path)) {
        return ['message' => '', 'error' => 'Please provide a product image (upload an image or select an existing asset).'];
    }

    if (createProduct($pdo, $name, $description, $price, $stock, $image_path)) {
        return ['message' => "Product '$name' was successfully created!", 'error' => ''];
    }

    return ['message' => '', 'error' => 'Database error: Could not create product.'];
}

/**
 * Validates and saves an uploaded image file to the uploads directory.
 */
function saveUploadedProductImage(array $file): array {
    $allowed_exts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $file_info    = pathinfo($file['name']);
    $file_ext     = strtolower($file_info['extension'] ?? '');

    if (!in_array($file_ext, $allowed_exts)) {
        return ['path' => '', 'error' => 'Invalid file type. Allowed formats: JPG, PNG, WEBP, GIF.'];
    }

    $finfo     = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    if (!in_array($mime_type, $allowed_mimes)) {
        return ['path' => '', 'error' => 'Uploaded file is not a valid image.'];
    }

    $upload_dir = dirname(__DIR__) . '/uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $safe_filename = time() . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $file_info['filename']) . '.' . $file_ext;
    $target_dest   = $upload_dir . $safe_filename;

    if (move_uploaded_file($file['tmp_name'], $target_dest)) {
        return ['path' => 'uploads/' . $safe_filename, 'error' => ''];
    }

    return ['path' => '', 'error' => 'Failed to save uploaded image.'];
}

/**
 * Handles deleting a product from the database.
 */
function processDeleteProduct(PDO $pdo): array {
    $product_id = (int)($_POST['product_id'] ?? 0);
    if ($product_id > 0 && deleteProduct($pdo, $product_id)) {
        return ['message' => "Product #$product_id was successfully removed.", 'error' => ''];
    }
    return ['message' => '', 'error' => "Failed to remove product #$product_id."];
}

/**
 * Handles updating product stock quantity.
 */
function processUpdateStock(PDO $pdo): array {
    $product_id = (int)($_POST['product_id'] ?? 0);
    $new_stock  = (int)($_POST['new_stock'] ?? 0);

    if ($product_id > 0 && updateProductStock($pdo, $product_id, $new_stock)) {
        return ['message' => 'Product stock successfully updated!', 'error' => ''];
    }
    return ['message' => '', 'error' => 'Failed to update product stock.'];
}

/**
 * Handles updating user account information.
 */
function processUpdateUser(PDO $pdo): array {
    $target_id = (int)($_POST['user_id'] ?? 0);
    $username  = trim($_POST['username'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $role_val  = strtolower(trim($_POST['role'] ?? 'customer'));

    // Map legacy 'user' to database enum 'customer'
    if ($role_val === 'user') {
        $role_val = 'customer';
    }

    $allowed_roles = ['customer', 'admin'];
    if (!in_array($role_val, $allowed_roles, true)) {
        return ['message' => '', 'error' => 'Invalid role selected.'];
    }

    if ($target_id <= 0 || empty($username) || empty($email)) {
        return ['message' => '', 'error' => 'Username and email cannot be empty.'];
    }

    try {
        $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id");
        if ($stmt->execute(['username' => $username, 'email' => $email, 'role' => $role_val, 'id' => $target_id])) {
            return ['message' => "User #$target_id successfully updated to " . ucfirst($role_val) . ".", 'error' => ''];
        }
    } catch (PDOException $e) {
        return ['message' => '', 'error' => 'Database error: ' . $e->getMessage()];
    }

    return ['message' => '', 'error' => "Failed to update user #$target_id."];
}

/**
 * Handles updating order status.
 */
function processUpdateOrder(PDO $pdo): array {
    $order_id = (int)($_POST['order_id'] ?? 0);
    $status   = strtolower(trim($_POST['status'] ?? 'pending'));

    $allowed_statuses = ['pending', 'paid', 'shipped', 'delivered', 'cancelled'];
    if (!in_array($status, $allowed_statuses, true)) {
        return ['message' => '', 'error' => 'Invalid order status selected.'];
    }

    if ($order_id <= 0) {
        return ['message' => '', 'error' => 'Invalid order ID.'];
    }

    try {
        $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
        if ($stmt->execute(['status' => $status, 'id' => $order_id])) {
            return ['message' => "Order #$order_id status changed to '" . ucfirst($status) . "'.", 'error' => ''];
        }
    } catch (PDOException $e) {
        return ['message' => '', 'error' => 'Database error: ' . $e->getMessage()];
    }

    return ['message' => '', 'error' => "Failed to update order #$order_id."];
}

/**
 * Allows an admin to change a customer's password.
 * Admins cannot change another admin's password.
 */
function processChangeUserPassword(PDO $pdo): array {
    $target_id   = (int)($_POST['user_id'] ?? 0);
    $new_password = trim($_POST['new_password'] ?? '');

    if ($target_id <= 0) {
        return ['message' => '', 'error' => 'Invalid user ID.'];
    }
    if (strlen($new_password) < 6) {
        return ['message' => '', 'error' => 'Password must be at least 6 characters.'];
    }

    // Fetch target user to check their role
    try {
        $stmt = $pdo->prepare("SELECT id, role FROM users WHERE id = :id");
        $stmt->execute(['id' => $target_id]);
        $target_user = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return ['message' => '', 'error' => 'Database error: ' . $e->getMessage()];
    }

    if (!$target_user) {
        return ['message' => '', 'error' => "User #$target_id not found."];
    }

    // Block admins from changing other admins' passwords
    if ($target_user['role'] === 'admin') {
        return ['message' => '', 'error' => 'You cannot change another admin\'s password.'];
    }

    $hashed = password_hash($new_password, PASSWORD_DEFAULT);

    if (updateUserPassword($pdo, $target_id, $hashed)) {
        return ['message' => "Password for User #$target_id has been updated.", 'error' => ''];
    }

    return ['message' => '', 'error' => "Failed to update password for User #$target_id."];
}

/**
 * Scans the Assets directory and returns an array of existing image paths.
 *
 * @return array List of relative paths (e.g., ['Assets/Gamepad.png', ...])
 */
function getAvailableAssets(): array {
    $assets_dir = dirname(__DIR__) . '/Assets/';
    $available_assets = [];

    if (is_dir($assets_dir)) {
        $scanned_files = scandir($assets_dir);
        if ($scanned_files) {
            foreach ($scanned_files as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'svg'])) {
                    $available_assets[] = 'Assets/' . $file;
                }
            }
        }
    }

    return $available_assets;
}
