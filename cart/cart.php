<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define base path to step out of the cart/ folder
$base_path = '../';

// Handle item removal before rendering HTML
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $index = (int)$_GET['remove'];
    
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        // Re-index the array so keys remain sequential
        $_SESSION['cart'] = array_values($_SESSION['cart']); 
    }
    
    // Redirect to self to clear the GET parameter
    header("Location: cart.php");
    exit();
}

// Calculate the running total
$cart_items = $_SESSION['cart'] ?? [];
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'];
}

include '../global/header.php';
?>

<main class="cart-container" style="max-width: 800px; margin: 50px auto; padding: 20px; color: white;">
    <h2 style="font-family: 'Space Grotesk', sans-serif; margin-bottom: 20px;">Your Shopping Cart</h2>

    <?php if (empty($cart_items)): ?>
        <p>Your cart is currently empty. <a href="../shop/shop.php" style="color: #3b82f6;">Browse the shop</a> or <a href="../customize/customize.php" style="color: #3b82f6;">build a custom controller</a>.</p>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
            <tr style="border-bottom: 2px solid #333; text-align: left;">
                <th style="padding: 12px 0;">Item Configuration</th>
                <th style="padding: 12px 0;">Price</th>
                <th style="padding: 12px 0; text-align: center;">Action</th>
            </tr>
            
            <?php foreach ($cart_items as $index => $item): ?>
                <tr style="border-bottom: 1px solid #222;">
                    <td style="padding: 15px 0;">
                        <strong style="text-transform: capitalize; display: block; margin-bottom: 4px;">
                            <?php echo htmlspecialchars(str_replace('_', ' ', $item['type'])); ?>
                        </strong>
                        <span style="color: #a3a3a3; font-size: 0.9rem;">
                            Button Color: <?php echo htmlspecialchars(ucfirst($item['button_color'])); ?>
                        </span><br>
                        <?php if (!empty($item['artwork_path'])): ?>
                            <span style="color: #4ade80; font-size: 0.85rem;">✓ Custom Artwork Attached</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 15px 0;">$<?php echo number_format($item['price'], 2); ?></td>
                    <td style="padding: 15px 0; text-align: center;">
                        <a href="cart.php?remove=<?php echo $index; ?>" style="color: #ef4444; text-decoration: none; font-weight: 600;">✕ Remove</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div style="display: flex; justify-content: space-between; align-items: center; background: #1a1a1a; padding: 20px; border-radius: 8px;">
            <h3 style="margin: 0; font-size: 1.5rem;">Total: $<?php echo number_format($total_price, 2); ?></h3>
            <!-- Points back out to a checkout file in the root or a checkout folder -->
            <a href="../checkout.php" style="padding: 12px 24px; background: white; color: black; text-decoration: none; border-radius: 6px; font-weight: bold;">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</main>

<?php include '../global/footer.php'; ?>