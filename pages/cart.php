<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/db.php';

$cartItems = [];

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $stmt = $db->prepare("SELECT id FROM cart WHERE user_id = ?");
    $stmt->execute([$userId]);
    $cartId = $stmt->fetchColumn();

    if ($cartId) {
        $stmt = $db->prepare("
            SELECT ci.id, ci.quantity, p.name, p.price, p.image_url, s.label AS size
            FROM cart_item ci
            JOIN products p ON ci.product_id = p.id
            JOIN sizes s ON ci.size_id = s.id
            WHERE ci.cart_id = ?
        ");
        $stmt->execute([$cartId]);
        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>

<div class="container">
    <div class="cart-header">
        <h1 class="cart-title">Your Venusia Cart</h1>
        <p class="cart-subtitle">Select items to proceed to checkout</p>
    </div>

    <main class="cart-content">
        <!-- Cart with items -->
        <section class="cart-items" style="<?= empty($cartItems) ? 'display:none;' : '' ?>">
            <?php foreach ($cartItems as $item): ?>
                <article class="cart-item">
                    <div class="item-image" style="background-image: url('<?= htmlspecialchars($item['image_url']) ?>');"></div>
                    <div class="item-details">
                        <h3><?= htmlspecialchars($item['name']) ?></h3>
                        <p>Size: <?= htmlspecialchars($item['size']) ?></p>
                        <p>Quantity: <?= $item['quantity'] ?></p>
                    </div>
                    <div class="item-price">
                        P<?= number_format($item['price'], 2) ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>

        <!-- Empty cart state (hidden by default) -->
        <section class="empty-cart" style="<?= empty($cartItems) ? '' : 'display:none;' ?>">
            <div class="empty-cart-icon"><i class="fas fa-shopping-bag"></i></div>
            <h2 class="empty-cart-title">Your Venusia Cart is Empty</h2>
            <p class="empty-cart-text">Discover our new collection of timeless pieces crafted with care.</p>
            <a href="index.php?page=store" class="shop-btn">Start Shopping</a>
        </section>

        <aside class="order-summary" style="<?= empty($cartItems) ? 'display:none;' : '' ?>>
            <h2 class="summary-title">Order Summary</h2>
            <div class="summary-row">
                <span>Selected Items (<span id="selected-count">2</span>)</span>
                <span>$<span id="subtotal">434.00</span></span>
            </div>
            <div class="summary-row">
                <span>Shipping</span>
                <span id="shipping">Free</span>
            </div>
            <div class="summary-row">
                <span>Tax</span>
                <span>$<span id="tax">47.36</span></span>
            </div>

            <!-- Promo code discount row (hidden by default) -->
            <div class="summary-row promo-discount" style="display: none;">
                <span>Discount <span class="promo-code-name"></span> <span class="promo-discount-remove">(Remove)</span></span>
                <span class="discount-amount">-$0.00</span>
            </div>

            <div class="summary-row summary-total">
                <span>Total</span>
                <span>$<span id="total">481.36</span></span>
            </div>

            <!-- Promo Code Section -->
            <div class="promo-code">
                <h3 class="promo-title">Promo Code</h3>
                <div class="promo-input">
                    <input type="text" id="promoCodeInput" placeholder="Enter promo code">
                    <button id="applyPromoBtn">Apply</button>
                </div>
                <div class="promo-message" id="promoMessage"></div>
            </div>

            <button class="checkout-btn" onclick="window.location.href='index.php?page=payment'">Proceed to Checkout</button>
            <a href="index.php?page=store" class="continue-shopping">
                <i class="fas fa-arrow-left"></i> Continue Shopping
            </a>
        </aside>
    </main>
</div>

<!-- Customization Modal -->
<div class="modal" id="customizationModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Customize Item</h3>
            <button class="close-modal">&times;</button>
        </div>
        <div class="option-group">
            <span class="option-title">Size</span>
            <div class="option-values" id="sizeOptions">
                <span class="option-value" data-value="XS">XS</span>
                <span class="option-value selected" data-value="S">S</span>
                <span class="option-value" data-value="M">M</span>
                <span class="option-value" data-value="L">L</span>
                <span class="option-value" data-value="XL">XL</span>
            </div>
        </div>
        <div class="option-group">
            <span class="option-title">Color</span>
            <div class="option-values" id="colorOptions">
                <span class="option-value selected" data-value="Champagne">Champagne</span>
                <span class="option-value" data-value="Ivory">Ivory</span>
                <span class="option-value" data-value="Black">Black</span>
                <span class="option-value" data-value="Dusty Rose">Dusty Rose</span>
            </div>
        </div>
        <div class="modal-actions">
            <button class="modal-btn cancel-btn">Cancel</button>
            <button class="modal-btn save-btn">Save Changes</button>
        </div>
    </div>
</div>