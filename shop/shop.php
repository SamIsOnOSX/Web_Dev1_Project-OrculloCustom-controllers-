<?php
// Step up one level to reach the database folder
require '../database/db.php';

// Define the base path so the header knows to look in the root directory
$base_path = '../'; 

$sql = "SELECT id, name, description, price, image_path FROM products ORDER BY id DESC";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Step up one level to include the global header
include '../global/header.php'; 
?>

<main class="shop-container">
    <div class="product-grid">
        <?php if (empty($products)): ?>
            <p>No controllers currently in stock.</p>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <!-- Prepend $base_path so the browser looks in the root Assets/ folder -->
                    <img src="<?php echo $base_path . htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p>$<?php echo number_format($product['price'], 2); ?></p>
                    
                    <button class="add-to-cart-btn">Add to Cart</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<!-- Step up one level to reach the global footer -->
<?php include '../global/footer.php'; ?>