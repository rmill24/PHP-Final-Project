<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/ProductModel.php';

$category = $_GET['category'] ?? null;
$productModel = new ProductModel($db);
$products = $productModel->getAll($category);
?>

<div class="container">

    <div class="store-nav">
        <h1>Venusia Collection</h1>
        <nav>
            <a href="?page=store" class="<?= !$category ? 'active' : '' ?>">All Items</a>
            <a href="?page=store&category=Tops" class="<?= $category === 'Tops' ? 'active' : '' ?>">Tops</a>
            <a href="?page=store&category=Bottoms" class="<?= $category === 'Bottoms' ? 'active' : '' ?>">Bottoms</a>
            <a href="?page=store&category=Dresses" class="<?= $category === 'Dresses' ? 'active' : '' ?>">Dresses</a>
            <a href="?page=store&category=Outerwear" class="<?= $category === 'Outerwear' ? 'active' : '' ?>">Outerwear</a>
        </nav>
    </div>

    <div class="product-grid">

        <?php foreach ($products as $product): ?>
            <a href="?page=product&product_id=<?= $product['id'] ?>" class="product-card">
                <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <div class="product-info">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="price">P<?= number_format($product['price'], 2) ?></p>
                    <form class="add-to-cart-form" data-product-id="<?= $product['id'] ?>" data-size-id="1">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit"><i class="fa-solid fa-cart-plus"></i><span class="button-text">  Add to Cart</span></button>
                    </form>
                </div>
            </a>
        <?php endforeach; ?>

    </div>

</div>