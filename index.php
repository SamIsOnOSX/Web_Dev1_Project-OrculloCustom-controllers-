<?php include 'global/header.php'; ?>

<main>
    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="hero-content">
            <h1>Control Your <br>
                <span class="text-gradient">Gaming Experience</span><br> 
                Your Way
            </h1>
            <p>Built for precision. Designed for personalization. Orcullo Custom Controller gives gamers the freedom to create a controller that matches their style, preferences, and playstyle.</p>
            <div class="hero-buttons">
                <a href="<?php echo $base_path; ?>customize/index.php" class="btn btn-primary">CUSTOMIZE NOW</a>
                <a href="<?php echo $base_path; ?>shop/shop.php" class="btn btn-outline">VIEW CONTROLLERS</a>
            </div>
        </div>
    </section>

    <!-- FEATURES BAR -->
    <section class="features-section">
        <div class="feature-card">
            <svg class="feature-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 3h15v13H1z"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            <h3>FAST SHIPPING</h3>
            <p>Get it by tomorrow</p>
        </div>
        <div class="feature-card">
            <svg class="feature-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <h3>1YR WARRANTY</h3>
            <p>Full coverage protection</p>
        </div>
        <div class="feature-card">
            <svg class="feature-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <h3>CUSTOMER SUPPORT</h3>
            <p>We're here when you need us</p>
        </div>
        <div class="feature-card">
            <svg class="feature-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            <h3>SECURE CHECKOUT</h3>
            <p>Safe and secure online payments</p>
        </div>
    </section>

    <!-- CATEGORIES SECTION -->
    <section class="content-section categories-section">
        <div class="section-header">
            <div>
                <h2>CATEGORIES</h2>
                <p class="section-sub">Find what you need at our store!</p>
            </div>
            <a href="<?php echo $base_path; ?>shop/shop.php" class="explore-link">EXPLORE ALL &gt;</a>
        </div>
        <div class="card-grid col-4">
            <a href="<?php echo $base_path; ?>shop/shop.php" class="category-card">
                <img src="<?php echo $base_path; ?>Assets/ARCADE_STICKS.png" alt="Arcade Sticks" class="category-img">
                <div class="category-label">ARCADE STICKS</div>
            </a>
            <a href="<?php echo $base_path; ?>shop/shop.php" class="category-card">
                <img src="<?php echo $base_path; ?>Assets/Gamepad.png" alt="Game Pads" class="category-img">
                <div class="category-label">GAME PADS</div>
            </a>
            <a href="<?php echo $base_path; ?>shop/shop.php" class="category-card">
                <img src="<?php echo $base_path; ?>Assets/LEVERLESS.png" alt="Leverless" class="category-img">
                <div class="category-label">LEVERLESS</div>
            </a>
            <a href="<?php echo $base_path; ?>customize/index.php" class="category-card">
                <img src="<?php echo $base_path; ?>Assets/CUSTOM.png" alt="Custom" class="category-img">
                <div class="category-label">CUSTOM</div>
            </a>
        </div>
    </section>

    <!-- BUY NOW SECTION -->
    <section class="content-section buy-now-section">
        <div class="section-header">
            <h2>BUY NOW!</h2>
        </div>
        <div class="card-grid col-4">
            <div class="product-card">
                <img src="<?php echo $base_path; ?>Assets/Orucllo_MixBox.png" alt="MixBox" class="product-img">
                <h4>Orcullo MixBox</h4>
                <p class="price">$150.00</p>
                <a href="<?php echo $base_path; ?>shop/shop.php" class="btn btn-outline" style="display: block; text-align: center; margin-top: 10px; padding: 8px 12px; font-size: 13px;">View in Shop</a>
            </div>
            <div class="product-card">
                <img src="<?php echo $base_path; ?>Assets/Custom Art Works  .png" alt="Custom Art Works" class="product-img">
                <h4>Custom Art Works</h4>
                <p class="price">$10.00</p>
                <a href="<?php echo $base_path; ?>shop/shop.php" class="btn btn-outline" style="display: block; text-align: center; margin-top: 10px; padding: 8px 12px; font-size: 13px;">View in Shop</a>
            </div>
            <div class="product-card">
                <img src="<?php echo $base_path; ?>Assets/BROOK-WINGMAN-P5__72173 1.png" alt="Brook PS5 Converter" class="product-img">
                <h4>Brook PS5 Converter</h4>
                <p class="price">$60.00</p>
                <a href="<?php echo $base_path; ?>shop/shop.php" class="btn btn-outline" style="display: block; text-align: center; margin-top: 10px; padding: 8px 12px; font-size: 13px;">View in Shop</a>
            </div>
            <div class="product-card">
                <img src="<?php echo $base_path; ?>Assets/Sanwa.png" alt="Sanwa Joystick" class="product-img">
                <h4>Sanwa Joystick</h4>
                <p class="price">$25.00</p>
                <a href="<?php echo $base_path; ?>shop/shop.php" class="btn btn-outline" style="display: block; text-align: center; margin-top: 10px; padding: 8px 12px; font-size: 13px;">View in Shop</a>
            </div>
        </div>
    </section>

    <!-- WHY CHOOSE US SECTION -->
    <section class="content-section why-us-section">
        <div class="split-layout">
            <img src="Assets/showcase.png" alt="Controller Showcase" class="showcase-img">
            <div class="why-us-text">
                <h2>Why You Should Choose Us?</h2>
                <p class="section-desc">Every controller is designed with precision, comfort, and customization in mind. Choose the features and design that fit your playstyle and create a controller that feels uniquely yours.</p>
                <div class="perks-grid">
                    <div class="perk-box">
                        <h5>✓ PRECISION CONTROL</h5>
                        <p>Responsive controls designed for accurate and consistent gameplay.</p>
                    </div>
                    <div class="perk-box">
                        <h5>✓ FULL CUSTOMIZATION</h5>
                        <p>Choose colors, buttons, sticks, and artwork to match your aesthetic.</p>
                    </div>
                    <div class="perk-box">
                        <h5>✓ BUILT FOR COMFORT</h5>
                        <p>Designed with comfortable controls for longer gaming sessions.</p>
                    </div>
                    <div class="perk-box">
                        <h5>✓ YOUR PLAYSTYLE</h5>
                        <p>Create a setup that matches the way you play and the games you enjoy.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- REVIEWS SECTION -->
    <section class="content-section testimonials-section">
        <div class="testimonials-header">
            <h2>What Our Players Say</h2>
            <p class="section-sub">Designed for passionate people that are into fighting games. Explore verified feedback from the Orcullo community.</p>
        </div>
        <div class="card-grid col-3">
            <div class="review-card">
                <div class="stars">★★★★★</div>
                <p class="review-text">"The input response on the Orcullo MixBox is flawless. Zero latency, custom Cherry MX switches, and the layout feels incredibly natural."</p>
                <h5>Justin 'J Wong' Wong</h5>
                <span class="role">Customer</span>
            </div>
            <div class="review-card">
                <div class="stars">★★★★★</div>
                <p class="review-text">"I've been using the Leverless for three months and the build quality is immaculate. The custom artwork template printed beautifully."</p>
                <h5>Solomon K.</h5>
                <span class="role">Customer</span>
            </div>
            <div class="review-card">
                <div class="stars">★★★★★</div>
                <p class="review-text">"Custom button configuration tool made it so easy to build my ideal layout. The heavy aluminum base plate gives it the perfect weight."</p>
                <h5>Akihiro T.</h5>
                <span class="role">Customer</span>
            </div>
        </div>
    </section>
</main>

<?php include 'global/footer.php'; ?>