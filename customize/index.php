<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base_path = '../'; 
include '../global/header.php';
?>

<link rel="stylesheet" href="../css/customize.css">

<main>
    <div class="customizer-container">
        <h2>Controller Customizer</h2>

        <?php if (!empty($message)): ?>
            <div class="success-box">
                <?php echo $message; ?> <a href="../cart/cart.php">View Cart</a>
            </div>
        <?php endif; ?>

        <form action="../cart/cart.php" method="POST" enctype="multipart/form-data" class="customizer-form">
            <input type="hidden" name="add_to_cart_custom" value="1">

            <div class="input-group">
                <label>Controller Type</label>
                <select name="type" required>
                    <option value="arcade_stick">Arcade Stick - $150.00</option>
                    <option value="leverless">Leverless - $160.00</option>
                    <option value="gamepad">Gamepad - $120.00</option>
                </select>
            </div>

            <div class="input-group">
                <label>Button Color</label>
                <select name="button_color" required>
                    <option value="black">Black</option>
                    <option value="white">White</option>
                    <option value="red">Red</option>
                    <option value="blue">Blue</option>
                </select>
            </div>

            <div class="input-group">
                <label>Upload Custom Artwork (Optional)</label>
                <input type="file" name="custom_artwork" accept="image/*">
            </div>

            <button type="submit" class="btn-primary">Save and Add to Cart</button>
        </form>
    </div>
</main>

<?php include '../global/footer.php'; ?>