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

                <form method="POST" action="checkout.php" class="checkout-form" enctype="multipart/form-data">

                    <!-- Payment Method Selection -->
                    <div class="payment-method-section">
                        <h4 class="payment-method-title">
                            <i class="fa-solid fa-credit-card"></i> Payment Method
                        </h4>
                        <div class="payment-options">
                            <label class="payment-option-label" id="label-cod">
                                <input type="radio" name="payment_method" value="cod" checked onchange="togglePaymentProof(this.value)">
                                <span class="payment-option-box" id="box-cod">
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                    <span class="payment-option-name">Cash on Delivery</span>
                                    <span class="payment-option-desc">Pay when your order arrives</span>
                                </span>
                            </label>
                            <label class="payment-option-label" id="label-online">
                                <input type="radio" name="payment_method" value="online" onchange="togglePaymentProof(this.value)">
                                <span class="payment-option-box" id="box-online">
                                    <i class="fa-solid fa-qrcode"></i>
                                    <span class="payment-option-name">Online Payment</span>
                                    <span class="payment-option-desc">GCash</span>
                                </span>
                            </label>
                        </div>

                        <!-- Online Payment QR Panel (shown when online is selected) -->
                        <div id="online-payment-panel" style="display:none;">

                            <!-- QR Code Display: GCash only -->
                            <div class="qr-display-box">
                                <img id="qr-gcash" src="<?php echo $base_path; ?>Assets/qr/gcash_qr.jpg" alt="GCash QR Code" class="qr-img">
                                <p class="qr-hint"><i class="fa-solid fa-circle-info"></i> Scan the QR code with your GCash app, then upload the screenshot below as proof.</p>
                            </div>

                            <!-- Proof of Payment Upload -->
                            <div class="proof-upload-section" id="proof-upload-section">
                                <label class="proof-upload-label" for="payment_proof">
                                    <i class="fa-solid fa-upload"></i> Upload Proof of Payment
                                    <span class="proof-upload-hint">JPG, PNG, WEBP or PDF — max 5 MB</span>
                                </label>
                                <input type="file" name="payment_proof" id="payment_proof" class="proof-upload-input" accept=".jpg,.jpeg,.png,.webp,.pdf">
                                <div class="proof-preview" id="proof-preview" style="display:none;">
                                    <img id="proof-img-preview" src="" alt="Preview">
                                    <span id="proof-file-name"></span>
                                </div>
                            </div>

                        </div><!-- /online-payment-panel -->
                    </div>

                    <button type="submit" name="confirm_checkout" class="btn-confirm-order">
                        <i class="fa-solid fa-shield-check"></i> Confirm &amp; Place Order
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </main>
</div>

<script>
function togglePaymentProof(value) {
    var onlinePanel = document.getElementById('online-payment-panel');
    var boxCod      = document.getElementById('box-cod');
    var boxOnline   = document.getElementById('box-online');

    if (value === 'online') {
        onlinePanel.style.display = 'block';
        boxOnline.classList.add('selected');
        boxCod.classList.remove('selected');
    } else {
        onlinePanel.style.display = 'none';
        boxCod.classList.add('selected');
        boxOnline.classList.remove('selected');
        // Clear file + preview
        document.getElementById('payment_proof').value = '';
        document.getElementById('proof-preview').style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Highlight COD by default
    var boxCod = document.getElementById('box-cod');
    if (boxCod) boxCod.classList.add('selected');

    // Image preview on file pick
    var fileInput = document.getElementById('payment_proof');
    if (fileInput) {
        fileInput.addEventListener('change', function () {
            var preview   = document.getElementById('proof-preview');
            var imgEl     = document.getElementById('proof-img-preview');
            var nameEl    = document.getElementById('proof-file-name');
            var file      = this.files[0];

            if (!file) { preview.style.display = 'none'; return; }

            nameEl.textContent = file.name;
            var ext = file.name.split('.').pop().toLowerCase();
            if (['jpg','jpeg','png','webp'].includes(ext)) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    imgEl.src = e.target.result;
                    imgEl.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imgEl.style.display = 'none'; // PDF – just show name
            }
            preview.style.display = 'flex';
        });
    }
});
</script>

<?php include '../global/footer.php'; ?>