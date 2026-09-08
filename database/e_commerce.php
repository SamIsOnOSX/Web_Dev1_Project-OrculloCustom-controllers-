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

?>