<?php
require '../database/db.php';
require '../database/e_commerce.php';

$base_path = '../'; 

// Fetch all products using the new reusable function
$products = getAllProducts($pdo);

include '../global/header.php'; 
?>

<!-- Link the dedicated shop stylesheet -->
<link rel="stylesheet" href="<?php echo $base_path; ?>css/shop.css">

<div class="shop-page-wrapper">
    <main class="shop-container">
        <div class="shop-header">
            <h1>Shop</h1>
            <p>High-performance controllers, parts, and accessories.</p>
        </div>

    <div class="product-grid">
        <?php if (empty($products)): ?>
            <div class="shop-empty-state">
                <i class="fa-solid fa-box-open empty-icon"></i>
                <h3>No controllers currently in stock</h3>
                <p>Check back soon or customize your own controller today!</p>
                <a href="<?php echo $base_path; ?>customize/index.php" class="btn btn-primary">Go to Customizer</a>
            </div>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <?php 
                    $stock = isset($product['stock']) ? (int)$product['stock'] : 0;
                    $is_out_of_stock = $stock <= 0;
                ?>
                <div class="product-card <?php echo $is_out_of_stock ? 'out-of-stock' : ''; ?>">
                    <div class="product-img-wrapper">
                        <?php if (!empty($product['image_path'])): ?>
                            <img src="<?php echo $base_path . htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-img">
                        <?php else: ?>
                            <div class="product-img-placeholder">
                                <i class="fa-solid fa-gamepad"></i>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($is_out_of_stock): ?>
                            <span class="badge-stock out">Out of Stock</span>
                        <?php elseif ($stock <= 3): ?>
                            <span class="badge-stock low">Only <?php echo $stock; ?> left</span>
                        <?php else: ?>
                            <span class="badge-stock in-stock">In Stock</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-info">
                        <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <?php if (!empty($product['description'])): ?>
                            <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                        <?php endif; ?>
                        
                        <div class="product-footer">
                            <div class="product-price">
                                <span class="currency">$</span><?php echo number_format($product['price'], 2); ?>
                            </div>

                            <form action="<?php echo $base_path; ?>cart/cart.php" method="POST" class="product-form">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="hidden" name="type" value="<?php echo htmlspecialchars($product['name']); ?>">
                                <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                                <input type="hidden" name="button_color" value="Standard">
                                
                                <button type="submit" name="add_to_cart" class="add-to-cart-btn" <?php echo $is_out_of_stock ? 'disabled' : ''; ?>>
                                    <i class="fa-solid fa-cart-plus"></i> <?php echo $is_out_of_stock ? 'Sold Out' : 'Add to Cart'; ?>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>
</div>

<?php include '../global/footer.php'; ?>