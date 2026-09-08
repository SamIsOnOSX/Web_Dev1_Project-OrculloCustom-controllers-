<?php
// Fetches all products to display on the shop page
function getAllProducts($pdo) {
    // Added 'stock' to the SELECT statement
    $sql = "SELECT id, name, description, price, stock, image_path FROM products ORDER BY id DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateProductStock($pdo, $product_id, $new_stock) {
    $sql = "UPDATE products SET stock = :stock WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':stock', $new_stock, PDO::PARAM_INT);
    $stmt->bindValue(':id', $product_id, PDO::PARAM_INT);
    return $stmt->execute();
}

function createOrder($pdo, $user_id, $total_amount) {
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount) VALUES (:user_id, :total_amount)");
    $stmt->execute(['user_id' => $user_id, 'total_amount' => $total_amount]);
    return $pdo->lastInsertId();
}

function createOrderItem($pdo, $order_id, $type, $button_color, $price, $artwork_path) {
    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_type, button_color, price, artwork_path) VALUES (:order_id, :type, :button_color, :price, :artwork_path)");
    return $stmt->execute([
        'order_id' => $order_id,
        'type' => $type,
        'button_color' => $button_color,
        'price' => $price,
        'artwork_path' => $artwork_path
    ]);
}

?>