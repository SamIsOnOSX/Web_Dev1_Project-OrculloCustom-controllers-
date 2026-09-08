<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_path = '../';
include '../global/header.php';
?>

<!-- Link the dedicated about stylesheet -->
<link rel="stylesheet" href="<?php echo $base_path; ?>css/about.css">

<main class="about-container">
    <h2 class="about-title">About Orcullo Custom Controllers</h2>
    
    <div class="about-card">
        <h3>Our Mission</h3>
        <p>
            At Orcullo Custom Controllers, we are dedicated to giving gamers and enthusiasts absolute control over their hardware. We believe that a controller should be an extension of your unique playstyle and personality—not just a standard accessory.
        </p>
        
        <h3>Crafted for Precision</h3>
        <p>
            Whether you are ordering from our curated shop or designing a custom build from scratch using our interactive configurator, every piece is built with performance, ergonomics, and striking aesthetics in mind.
        </p>
    </div>

    <div class="about-grid">
        <div class="about-grid-item">
            <h4> Fully Customized</h4>
            <p>Tailor your button layouts, color schemes, and custom specs.</p>
        </div>
        <div class="about-grid-item">
            <h4>Real-Time Inventory</h4>
            <p>Backed by a dynamic live management system for total tracking.</p>
        </div>
        <div class="about-grid-item">
            <h4> Secure Access</h4>
            <p>Role-based authentication keeping admin tools protected.</p>
        </div>
    </div>
</main>

<?php include '../global/footer.php'; ?>