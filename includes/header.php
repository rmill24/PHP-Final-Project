<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title ?? 'Venusia') ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/global.css">
  <?php if (file_exists("assets/css/{$current_page}.css")): ?>
    <link rel="stylesheet" href="assets/css/<?= $current_page ?>.css">
  <?php endif; ?>
</head>
<body>

<!-- Updated header.php -->
<div class="main-header">
    <header>
    <div class="nav-container">
        <!-- Logo + Text -->
        <div class="logo-header">
            <img src="assets/img/venusia_logo.png" alt="Venusia Icon">
            <span class="venusia-header-text">VENUSIA</span>
        </div>

        <!-- Navigation Links (desktop) -->
        <div class="nav-links">
            <a href="index.php?page=home" class="<?= $current_page === 'home' ? 'active' : '' ?>">HOME</a>
            <a href="index.php?page=store" class="<?= $current_page === 'store' ? 'active' : '' ?>">COLLECTION</a>
            <a href="index.php?page=about" class="<?= $current_page === 'about' ? 'active' : '' ?>">ABOUT US</a>
        </div>

        <!-- User and Cart Icons + Mobile Menu Toggle -->
        <div class="right-section">
            <div class="nav-icons">
                <a href="#" onclick="openModal()"><i class="far fa-user"></i></a>
                <a href="index.php?page=cart" class="<?= $current_page === 'cart' ? 'active' : '' ?>"><i class="fas fa-shopping-bag"></i></a>
            </div>
            <button class="mobile-menu-toggle" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
    </header>
</div>

<?php include __DIR__ . '/../actions/login.php'; ?>

<!-- Mobile Menu Overlay -->
<div class="mobile-menu-overlay">
    <div class="mobile-menu-container">
        <button class="close-menu-btn" aria-label="Close navigation">
            <i class="fas fa-times"></i>
        </button>
        <nav class="mobile-nav-links">
            <a href="index.php?page=home" class="<?= $current_page === 'home' ? 'active' : '' ?>">HOME</a>
            <a href="index.php?page=store" class="<?= $current_page === 'store' ? 'active' : '' ?>">COLLECTION</a>
            <a href="index.php?page=about" class="<?= $current_page === 'about' ? 'active' : '' ?>">ABOUT US</a>
        </nav>
    </div>
</div>
