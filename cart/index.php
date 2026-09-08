<?php
$cart_items = $cart_items ?? [];
$total_price = $total_price ?? 0;
$base_path = $base_path ?? '../';

if (!isset($_SESSION)) {
    header("Location: cart.php");
    exit();
}

include '../global/header.php';
?>

<!-- Link the dedicated cart stylesheet -->
<link rel="stylesheet" href="<?php echo $base_path; ?>css/cart.css">

<main class="cart-container">
    <h2 class="cart-title">Your Shopping Cart</h2>

    <?php if (empty($cart_items)): ?>
        <p class="cart-empty-text">Your cart is currently empty. <a href="../shop/shop.php" class="cart-link">Browse the shop</a> or <a href="../customize/index.php" class="cart-link">build a custom controller</a>.</p>
    <?php else: ?>
        <table class="cart-table">
            <tr class="cart-table-header">
                <th>Item Configuration</th>
                <th>Price</th>
                <th class="th-center">Action</th>
            </tr>
            
            <?php foreach ($cart_items as $index => $item): ?>
                <tr class="cart-table-row">
                    <td>
                        <strong class="cart-item-name">
                            <?php echo htmlspecialchars(str_replace('_', ' ', $item['type'])); ?>
                        </strong>
                        <span class="cart-item-meta">
                            Button Color: <?php echo htmlspecialchars(ucfirst($item['button_color'])); ?>
                        </span><br>
                        <?php if (!empty($item['artwork_path'])): ?>
                            <span class="cart-artwork-badge">✓ Custom Artwork Attached</span>
                        <?php endif; ?>
                    </td>
                    <td class="cart-price">$<?php echo number_format($item['price'], 2); ?></td>
                    <td class="cart-action-col">
                        <a href="cart.php?remove=<?php echo $index; ?>" class="cart-remove-link">✕ Remove</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="cart-summary">
            <h3 class="cart-total-amount">Total: $<?php echo number_format($total_price, 2); ?></h3>
            <a href="../checkout/checkout.php" class="cart-checkout-btn">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</main>

<?php include '../global/footer.php'; ?>