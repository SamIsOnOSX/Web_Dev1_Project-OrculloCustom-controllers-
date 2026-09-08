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
                    
                    <!-- Wrap the button in a form targeting the cart -->
                    <form action="../cart/cart.php" method="POST" style="margin: 0;">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="type" value="<?php echo htmlspecialchars($product['name']); ?>">
                        <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                        <input type="hidden" name="button_color" value="Standard">
                        
                        <button type="submit" name="add_to_cart" class="add-to-cart-btn" style="cursor: pointer;">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php include '../global/footer.php'; ?>