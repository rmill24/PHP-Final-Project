<?php
require_once 'includes/db.php';

$category = $_GET['category'] ?? null;
$params = [];

$sql = "SELECT p.*, c.name AS category_name FROM products p
        LEFT JOIN categories c ON p.category_id = c.id";

if ($category) {
    $sql .= " WHERE c.name = ?";
    $params[] = $category;
}

$sql .= " ORDER BY p.name";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<div class="container">

    <div class="store-nav">
        <h1>Venusia Collection</h1>
        <nav>
            <a href="?page=store">All Items</a>
            <a href="?page=store&category=Tops">Tops</a>
            <a href="?page=store&category=Bottoms">Bottoms</a>
            <a href="?page=store&category=Dresses">Dresses</a>
            <a href="?page=store&category=Outerwear">Outerwear</a>
        </nav>
    </div>

    <div class="product-grid">

        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <div class="product-info">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p>P<?= number_format($product['price'], 2) ?></p>
                    <form method="POST" action="actions/add_to_cart.php">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit">Add to Cart</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>

    </div>

</div>