<?php
require_once __DIR__ . '/../includes/db.php';

$stmt = $db->query("SELECT * FROM products ORDER BY RAND() LIMIT 4");
$featuredProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="carousel-container">
    <!-- Bootstrap Carousel -->
    <div id="carouselVenusia" class="carousel slide" data-bs-ride="carousel">
        <!-- Carousel Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselVenusia" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselVenusia" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselVenusia" data-bs-slide-to="2" aria-label="Slide 3"></button>
            <button type="button" data-bs-target="#carouselVenusia" data-bs-slide-to="3" aria-label="Slide 4"></button>
            <button type="button" data-bs-target="#carouselVenusia" data-bs-slide-to="4" aria-label="Slide 5"></button>
        </div>

        <!-- Carousel Content -->
        <div class="carousel-inner shadow">
            <!-- Slide 1 -->
            <div class="carousel-item active">
                <img src="https://i.pinimg.com/736x/84/68/cd/8468cdd48ba8b0e90e12051bb2a97891.jpg" class="d-block w-100" alt="New Collection">
                <div class="carousel-caption d-none d-md-block">
                    <h2>Discover Timeless Style</h2>
                    <p>"Redefine simplicity and sophistication with curated looks that speak grace and confidence"</p>
                    <a class="btn btn-light text-light" href="#register">Sign Up & Slay</a>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-item">
                <img src="https://i.pinimg.com/1200x/53/29/63/53296318765b637b8ccd9b50082a4b07.jpg" class="d-block w-100" alt="Limited Edition Items">
            </div>

            <!-- Slide 3 -->
            <div class="carousel-item">
                <img src="https://i.pinimg.com/1200x/dd/35/45/dd354522de7b5d53ab1e006ee8dfd7ef.jpg" class="d-block w-100" alt="Special Discounts">
            </div>

            <!-- Slide 4 -->
            <div class="carousel-item">
                <img src="https://i.pinimg.com/1200x/da/4a/c3/da4ac365fcfd75f768d0fd14e50f0878.jpg" class="d-block w-100" alt="Summer Collection">
            </div>

            <!-- Slide 5 -->
            <div class="carousel-item">
                <img src="https://i.pinimg.com/1200x/d3/9c/d6/d39cd6ed7f4c27737736d47f4a35404f.jpg" class="d-block w-100" alt="Premium Selection">
            </div>
        </div>
    </div>
</div>

<div class="category-layout">
    <!-- Left Large Card: Casual Dress -->
    <div class="category-large">
        <a href="index.php?page=store&category=Dresses"><img src="assets/img/casual-dress.png" alt="Casual Dress"></a>
    </div>

    <!-- Right Side Small Cards -->
    <div class="category-side">
        <div class="category-small">
            <a href="index.php?page=store&category=Tops"><img src="assets/img/women-tops.png" alt="Casual Tops"></a>
        </div>
        <div class="category-small">
            <a href="index.php?page=store&category=Bottoms"><img src="assets/img/women-bottoms.png" alt="Casual Bottoms"></a>
        </div>

        <!-- Outerwear Banner -->
        <div class="category-banner">
            <a href="index.php?page=store&category=Outerwear"><img src="assets/img/outerwear.png" alt="Casual Outerwear"></a>
        </div>
    </div>
</div>

<!-- Featured Collection -->
<section class="featured">
    <div class="container">
        <div class="collection-header">
            <h2 class="section-title">FEATURED COLLECTION</h2>
            <div class="collection-tags">
                <span class="tag">Best Seller</span>
                <span class="tag">New Arrival</span>
            </div>
        </div>
        <div class="product-grid">
            <!-- Dynamic products -->
            <?php foreach ($featuredProducts as $product): ?>
                <a href="?page=product&product_id=<?= $product['id'] ?>" class="product-card">
                    <div class="product-image">
                        <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    </div>
                    <div class="product-info">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p><?= htmlspecialchars($product['product_details']) ?></p>
                        <span class="price">P<?= number_format($product['price'], 2) ?></span>
                        <form class="add-to-cart-form" data-product-id="<?= $product['id'] ?>" data-size-id="1">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <button class="add-to-cart" type="submit"><i class="fa-solid fa-cart-plus"></i>  Add to Cart</button>
                        </form>

                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        <!-- Banner -->
        <div class="promo-banner">
            <img src="assets/img/promo_banner.png" alt="Venusia Sale - 20% Off Everything">
        </div>
    </div>
</section>

<!-- Registration Section -->
<section class="registration-section" id="register">
    <div class="registration-container">
        <div class="registration-content">
            <h2>Sign Up Now</h2>
            <p class="subtitle">A touch of elegance, just for you.</p>
            <p>Join our list and enjoy early access to new drops, exclusive styles, and timeless pieces you'll keep reaching for.</p>
        </div>

        <form class="registration-form" action="actions/register.php" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="firstName" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="lastName" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group full-width">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group full-width">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group full-width">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
            </div>
            <div id="formErrors"></div>
            <button type="submit" class="register-btn">Register</button>
        </form>
    </div>
</section>