<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orcullo Custom Controllers</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/hero.css">
    <link rel="stylesheet" href="css/sections.css">
    <link rel="stylesheet" href="css/footer.css">
    
</head>
<body>
    <header class="main-header">
        <div class="logo">
            <a href="index.php" class="header-logo">
                <img src="Assets/LogoOnly.png" class="logo-image" alt="Orcullo Logo">
                <img src="Assets/TextOnly.png" class="text-logo" alt="Orcullo Custom Controller">
            </a>
        </div>
        <nav class="main-nav">
            <a href="index.php">Home</a>
            <a href="shop.php">Shop</a>
            <a href="#">Customize</a>
            <a href="#">About</a>
        </nav>
        <div class="header-actions">
            <div class="search-bar">
                <input type="text" placeholder="Search...">
            </div>
            <a href="#" class="icon-link" title="Wishlist"><i class="fa-regular fa-heart"></i></a>
            <a href="#" class="icon-link" title="Cart"><i class="fa-solid fa-cart-shopping"></i></a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="icon-link" title="Logout"><i class="fa-solid fa-user-check"></i></a>
            <?php else: ?>
                <a href="login.php" class="icon-link" title="Login"><i class="fa-regular fa-user"></i></a>
            <?php endif; ?>
        </div>
    </header>