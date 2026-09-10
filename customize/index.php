<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base_path = '../'; 
include '../global/header.php';
?>

<link rel="stylesheet" href="../css/customize.css">

<div class="customize-page-wrapper">
    <main class="customize-container">
        <div class="customize-header">
            <h1>Controller Customizer</h1>
            <p>Tailor your controller's type, button color, and custom top artwork.</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="success-box">
                <i class="fa-solid fa-circle-check"></i>
                <span><?php echo $message; ?> <a href="../cart/cart.php">View Cart</a></span>
            </div>
        <?php endif; ?>

        <?php
        $upload_error = '';
        if (!empty($_SESSION['cart_error'])) {
            $upload_error = $_SESSION['cart_error'];
            unset($_SESSION['cart_error']);
        }
        ?>
        <?php if (!empty($upload_error)): ?>
            <div class="error-box">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span><?php echo htmlspecialchars($upload_error); ?></span>
            </div>
        <?php endif; ?>

        <div class="customizer-card">
            <form action="../cart/cart.php" method="POST" enctype="multipart/form-data" class="customizer-form">
                <input type="hidden" name="add_to_cart_custom" value="1">

                <div class="input-group">
                    <label for="controller_type">
                        <i class="fa-solid fa-gamepad input-icon"></i> Controller Type
                    </label>
                    <select id="controller_type" name="type" required>
                        <option value="arcade_stick">Arcade Stick &mdash; $150.00</option>
                        <option value="leverless">Leverless &mdash; $160.00</option>
                        <option value="gamepad">Gamepad &mdash; $120.00</option>
                    </select>
                    <span class="input-hint">Choose your preferred form factor and internal PCB layout.</span>
                </div>

                <div class="input-group">
                    <label for="button_color">
                        <i class="fa-solid fa-palette input-icon"></i> Button Color
                    </label>
                    <select id="button_color" name="button_color" required>
                        <option value="black">Obsidian Black (Standard)</option>
                        <option value="white">Pure White</option>
                        <option value="red">Crimson Red</option>
                        <option value="blue">Cobalt Blue</option>
                    </select>
                    <span class="input-hint">High-response Sanwa-style arcade pushbuttons.</span>
                </div>

                <div class="input-group">
                    <label for="custom_artwork">
                        <i class="fa-solid fa-image input-icon"></i> Upload Custom Artwork <span class="optional-tag">(Optional)</span>
                    </label>
                    <div class="file-upload-wrapper">
                        <input type="file" id="custom_artwork" name="custom_artwork" accept="image/*">
                    </div>
                    <span class="input-hint">Supports PNG, JPG, or WEBP high-resolution layout artwork.</span>
                </div>

                <button type="submit" class="btn-submit-custom">
                    <i class="fa-solid fa-cart-plus"></i> Save and Add to Cart
                </button>
            </form>
        </div>
    </main>
</div>

<?php include '../global/footer.php'; ?>