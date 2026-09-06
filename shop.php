<?php
    session_start();
    require 'database/db.php';

    $sql = "SELECT id, name, description, price, image_path FROM products ORDER BY id DESC";
    $stmt = $pdo->query($sql);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="product-grid">
    <?php if (empty($products)): ?>
        <p>No controllers currently in stock.</p>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <!-- Using htmlspecialchars to prevent XSS attacks -->
                <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="Controller">
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <p>$<?php echo number_format($product['price'], 2); ?></p>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>