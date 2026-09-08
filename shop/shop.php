<?php
require '../database/db.php';
require '../database/e_commerce.php';

$base_path = '../'; 

// Fetch all products using the new reusable function
$products = getAllProducts($pdo);

include '../global/header.php'; 
?>

<main class="shop-container">
    <!-- Existing HTML remains untouched -->
    <div class="product-grid">
        <?php if (empty($products)): ?>
            <p>No controllers currently in stock.</p>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="<?php echo $base_path . htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p>$<?php echo number_format($product['price'], 2); ?></p>
                    
                    <button class="add-to-cart-btn">Add to Cart</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php include '../global/footer.php'; ?>