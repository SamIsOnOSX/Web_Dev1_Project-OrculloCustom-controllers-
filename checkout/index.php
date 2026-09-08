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

<main class="checkout-container">
    <?php if ($success): ?>
        <div class="checkout-success-box">
            <h2>Order Confirmed!</h2>
            <p>Your custom controller build has been securely placed in our system.</p>
            <a href="../index.php" class="btn-home">Return Home</a>
        </div>
    <?php else: ?>
        <h2 class="checkout-title">Checkout Summary</h2>
        
        <?php if (!empty($error)): ?>
            <div class="checkout-error-box">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="checkout-summary-card">
            <h3>Order Total: $<?php echo number_format($total_price, 2); ?></h3>
            <p>You are about to finalize your order for <?php echo count($cart_items); ?> item(s).</p>
            
            <form method="POST" action="checkout.php">
                <button type="submit" name="confirm_checkout" class="btn-confirm">
                    Confirm & Pay
                </button>
            </form>
        </div>
    <?php endif; ?>
</main>

<?php include '../global/footer.php'; ?>