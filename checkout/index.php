<?php
// Prevent direct access to index.php
if (!isset($pdo)) {
    header("Location: checkout.php");
    exit();
}

$success = $success ?? false;
$error = $error ?? '';
$total_price = $total_price ?? 0;
$cart_items = $cart_items ?? [];
$base_path = $base_path ?? '../';

include '../global/header.php';
?>

<!-- Link the dedicated checkout stylesheet -->
<link rel="stylesheet" href="<?php echo $base_path; ?>css/checkout.css">

<div class="checkout-page-wrapper">
    <main class="checkout-container">
        <?php if ($success): ?>
            <div class="checkout-card checkout-success-card">
                <div class="success-icon-badge">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h2>Order Confirmed!</h2>
                <p>Your order has been securely placed. We're already getting your controllers prepped and assembled.</p>
                <div class="success-actions">
                    <a href="<?php echo $base_path; ?>index.php" class="btn btn-primary">Return to Homepage</a>
                    <a href="<?php echo $base_path; ?>shop/shop.php" class="btn btn-outline">Keep Shopping</a>
                </div>
            </div>
        <?php else: ?>
            <div class="checkout-header">
                <h1>Checkout Summary</h1>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="checkout-error-box">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <?php 
                $total_item_count = 0;
                foreach ($cart_items as $ci) {
                    $total_item_count += isset($ci['quantity']) ? (int)$ci['quantity'] : 1;
                }
            ?>
            <div class="checkout-card checkout-summary-card">
                <div class="checkout-card-header">
                    <h3><i class="fa-solid fa-receipt card-icon"></i> Order Overview</h3>
                    <span class="item-count-badge"><?php echo $total_item_count; ?> Item<?php echo $total_item_count > 1 ? 's' : ''; ?></span>
                </div>

                <div class="checkout-items-preview">
                    <?php foreach ($cart_items as $item): ?>
                        <?php 
                            $qty = isset($item['quantity']) ? (int)$item['quantity'] : 1; 
                            $item_subtotal = $item['price'] * $qty;
                        ?>
                        <div class="checkout-item-row">
                            <div class="item-info">
                                <span class="item-name"><?php echo htmlspecialchars($item['type']); ?></span>
                                <span class="item-meta">Color: <?php echo htmlspecialchars($item['button_color'] ?? 'Standard'); ?> <?php if ($qty > 1): ?>&bull; Qty: <?php echo $qty; ?><?php endif; ?></span>
                            </div>
                            <div class="item-price">
                                $<?php echo number_format($item_subtotal, 2); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="checkout-divider"></div>

                <div class="checkout-total-row">
                    <span class="total-label">Total Amount</span>
                    <span class="total-amount"><span class="currency">$</span><?php echo number_format($total_price, 2); ?></span>
                </div>

                <form method="POST" action="checkout.php" class="checkout-form">
                    <button type="submit" name="confirm_checkout" class="btn-confirm-order">
                        <i class="fa-solid fa-shield-check"></i> Confirm & Pay
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </main>
</div>

<?php include '../global/footer.php'; ?>