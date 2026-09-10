<?php $base_path = $base_path ?? ''; ?>

<footer class="main-footer">
        <div class="footer-container">
            <div class="footer-column brand-column">
                <div class="footer-logo">
                    <img src="<?php echo $base_path; ?>Assets/LogoOnly.png" class="logo-image" alt="Orcullo Logo">
                    <img src="<?php echo $base_path; ?>Assets/TextOnly.png" class="text-logo" alt="Orcullo Custom Controller">
                </div>
                <p>Custom gaming controllers designed for players who want greater control and a setup that's uniquely theirs.</p>
            </div>
            
            <div class="footer-column links-column">
                <h4>SHOP</h4>
                <a href="<?php echo $base_path; ?>shop/shop.php">Controllers</a>
                <a href="<?php echo $base_path; ?>customize/index.php">Custom Builds</a>
                <a href="<?php echo $base_path; ?>shop/shop.php">Accessories</a>
            </div>
            
            <div class="footer-column links-column support-column">
                <h4>SUPPORT</h4>
                <span class="unclickable-item">Support Center</span>
                <span class="unclickable-item">Contact Us</span>
                <span class="unclickable-item">FAQs</span>
            </div>

            <div class="footer-column newsletter-column">
                <h4>NEWSLETTER</h4>
                <p>Get updates, new designs, and the latest from Orcullo.</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Email address" required>
                    <button type="submit">JOIN</button>
                </form>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2026 ALL RIGHTS RESERVED FOR ORCULLO CUSTOM CONTROLLERS.</p>
        </div>
    </footer>
</body>
</html>