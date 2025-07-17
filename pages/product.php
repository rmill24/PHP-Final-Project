<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/ProductModel.php';

$productModel = new ProductModel($db);
$productId = $_GET['product_id'] ?? null;

if (!$productId) {
    echo "<p>Product not found.</p>";
    exit;
}

$product = $productModel->getById($productId);
if (!$product) {
    echo "<p>Product not found.</p>";
    exit;
}

// Get sizes
$stmt = $db->prepare("
    SELECT s.id AS size_id, s.label
    FROM product_sizes ps
    JOIN sizes s ON ps.size_id = s.id
    WHERE ps.product_id = ?
");


$stmt->execute([$productId]);
$sizes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <div class="product-container">
        <div class="img-container">
            <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        </div>
        <div class="product-details">
            <div>
                <h1><?= htmlspecialchars($product['name']) ?></h1>
                <p>★★★★⯪ 4.9 (350 Reviews)</p>
            </div>
            <div>
                <h2>P<?= number_format($product['price'], 2) ?></h2>
                <br>
                <p><?= nl2br(htmlspecialchars($product['product_details'])) ?></p>
            </div>

            <form method="POST" action="actions/add_to_cart.php" id="addToCartForm" style="display:inline;">
                <div>
                    <p><strong>Available Size</strong></p>
                    <div class="size-grid">
                        <?php foreach ($sizes as $size): ?>
                            <button type="button" class="size-option" data-size-id="<?= $size['size_id'] ?>">
                                <?= htmlspecialchars($size['label']) ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                    <input type="hidden" name="size_id" id="selectedSizeInput">
                </div>

                <div class="action-grid">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <button type="submit" id="addToCartBtn">Add to Cart</button>
                    <button type="button">Buy Now</button> <!-- Optional -->
                </div>

                <!-- Message container -->
                <div id="cartMessage" style="margin-top: 10px; font-weight: bold;"></div>
            </form>



            <!-- Message container -->
            <div id="cartMessage" style="margin-top: 10px; font-weight: bold;"></div>
            </form>

        </div>

    </div>

    <div class="customer-reviews">

        <h3>Customer Reviews</h3>
        <hr>
        <div class="review-container">
            <div>
                <div class="review-details">
                    <img src="https://i.pinimg.com/474x/41/eb/79/41eb79399271bab90c4e55cbba6879a8.jpg" class="reviewer-icon">
                    <div class="reviewer-details">
                        <p>Violet Kiramman</p>
                        <p><strong>Product Quality: </strong>Very good quality</p>
                        <p><strong>True to Product Images: </strong>What you see is what you get. But the person wearing it made them look 1000x better</p>
                        <p><strong>Fabric Material: </strong>Very comfortable.</p>
                        <p>I got this for my wife and she absolutely loved it!</p>
                    </div>
                </div>
                <hr>
                <div class="review-details">
                    <img src="https://wiki.leagueoflegends.com/en-us/images/thumb/Caitlyn_Arcane_13_Render.png/280px-Caitlyn_Arcane_13_Render.png?b3f8d" class="reviewer-icon">
                    <div class="reviewer-details">
                        <p>Caitlyn Kiramman</p>
                        <p><strong>Product Quality: </strong>Very good quality</p>
                        <p><strong>True to Product Images: </strong>What you see is what you get. I daresay the actual material look even better in person!</p>
                        <p><strong>Fabric Material: </strong>Very comfortable.</p>
                        <p>My wonderful wife got this for me and like she said, I absolutely loved this!</p>
                    </div>
                </div>
            </div>

            <div class="review-graphics">
                <!-- Overall Rating Display -->
                <div class="overall-rating">
                    <div class="rating-score">4.9</div>
                    <div class="rating-text">out of 5</div>
                    <div class="stars-display">
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star half">★</span>
                    </div>
                </div>

                <!-- Star Rating Breakdown -->
                <div class="rating-breakdown">
                    <div class="rating-row">
                        <div class="star-label">5 Stars</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 80%"></div>
                        </div>
                        <div class="percentage">80%</div>
                    </div>
                    <div class="rating-row">
                        <div class="star-label">4 Stars</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 10%"></div>
                        </div>
                        <div class="percentage">10%</div>
                    </div>
                    <div class="rating-row">
                        <div class="star-label">3 Stars</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 5%"></div>
                        </div>
                        <div class="percentage">5%</div>
                    </div>
                    <div class="rating-row">
                        <div class="star-label">2 Stars</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 5%"></div>
                        </div>
                        <div class="percentage">5%</div>
                    </div>
                    <div class="rating-row">
                        <div class="star-label">1 Stars</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 0%"></div>
                        </div>
                        <div class="percentage">0%</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>