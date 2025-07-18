<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'Venusia') ?></title>
    <link rel="icon" href="assets/img/venusia_logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/global.css">
    <?php if (file_exists("assets/css/{$current_page}.css")): ?>
        <link rel="stylesheet" href="assets/css/<?= $current_page ?>.css">
    <?php endif; ?>
</head>

<body>

    <!-- Page Load Animation -->
    <div class="page-loader" id="pageLoader">
        <div class="loader-content">
            <div class="loader-logo">
                <img src="assets/img/venusia_logo.png" alt="Venusia Logo">
                <span class="loader-brand-text">VENUSIA</span>
            </div>
            <p class="loader-tagline">Luxury redefined for the modern woman</p>
            <div class="loader-spinner"></div>
            <div class="loader-progress">
                <div class="loader-progress-bar"></div>
            </div>
        </div>
    </div>

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
                        <a href="#" id="profileLink"><i class="far fa-user"></i></a>
                        <a href="index.php?page=cart" class="<?= $current_page === 'cart' ? 'active' : '' ?>">
                            <i class="fas fa-shopping-bag"></i>
                            <span id="cartCount" class="cart-count-badge">0</span>
                        </a>

                    </div>
                    <button class="mobile-menu-toggle" aria-label="Toggle navigation">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </header>
    </div>

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

    <!-- Modal -->
    <div class="modal-overlay" id="loginModal">
        <div class="modal">
            <button class="close-btn" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            <div class="modal-header">
                <div class="modal-title-container">
                    <h2 class="modal-title">Welcome Back</h2>
                    <p class="modal-subtitle">Sign in to your account</p>
                </div>
            </div>
            <form onsubmit="handleLogin(event)" id="login-modal-form">
                <div class="form-group">
                    <input type="email" name="email" id="loginEmail" placeholder="Email" class="form-input" required />
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="loginPassword" placeholder="Password" class="form-input" required />
                </div>
                <button type="submit" class="sign-in-btn">Sign In</button>
                <div id="loginError" style="color: red; margin-bottom: 10px;"></div>
            </form>
            <p class="sign-up-text">
                Don't have an account yet?
                <a href="index.php?page=home#register" class="sign-up-link" onclick="closeModal()">Sign up here</a>
            </p>
        </div>
    </div>