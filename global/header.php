<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Default to empty string if viewed from the root directory
$base_path = $base_path ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orcullo Custom Controllers</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Dynamic CSS Paths -->
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/global.css">
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/header.css">
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/hero.css">
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/sections.css">
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/footer.css">
</head>
<body>
    <header class="main-header">
        <div class="logo">
            <a href="<?php echo $base_path; ?>index.php" class="header-logo">
                <img src="<?php echo $base_path; ?>Assets/LogoOnly.png" class="logo-image" alt="Orcullo Logo">
                <img src="<?php echo $base_path; ?>Assets/TextOnly.png" class="text-logo" alt="Orcullo Custom Controller">
            </a>
        </div>
        
        <nav class="main-nav">
            <a href="<?php echo $base_path; ?>index.php">Home</a>
            <a href="<?php echo $base_path; ?>shop/shop.php">Shop</a>
            <a href="<?php echo $base_path; ?>customize/index.php">Customize</a>
            <a href="<?php echo $base_path; ?>about/about.php">About</a>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?php echo $base_path; ?>dashboard/index.php">Profile</a>
            <?php endif; ?>
        </nav>

        <div class="header-actions">
            <div class="search-bar">
                <input type="text" placeholder="Search...">
            </div>
            <a href="<?php echo $base_path; ?>cart/cart.php" class="icon-link" title="Cart"><i class="fa-solid fa-cart-shopping"></i></a>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?php echo $base_path; ?>auth/logout.php" class="icon-link" title="Logout"><i class="fa-solid fa-user-check"></i></a>
            <?php else: ?>
                <a href="<?php echo $base_path; ?>auth/login.php" class="icon-link" title="Login"><i class="fa-regular fa-user"></i></a>
            <?php endif; ?>
        </div>
    </header>