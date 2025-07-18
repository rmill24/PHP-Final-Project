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
            SELECT ci.id AS cart_item_id, ci.product_id, ci.size_id, ci.quantity, ci.selected, p.name, p.price, p.image_url, s.label AS size
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

    <div class="cart-content">
        <!-- Cart with items -->
        <section class="cart-items">
            <?php if (empty($cartItems)): ?>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        document.querySelector(".cart-items").style.display = "none";
                        document.querySelector(".order-summary").style.display = "none";
                        document.querySelector(".empty-cart").style.display = "block";
                    });
                </script>
            <?php else: ?>
                <?php foreach ($cartItems as $item): ?>
                    <article class="cart-item" data-cart-item-id="<?= $item['cart_item_id'] ?>">
                        <div class="item-selector">
                            <input type="checkbox" class="item-checkbox" <?= $item['selected'] ? 'checked' : '' ?>>
                        </div>
                        <div class="item-image" style="background-image: url('<?= htmlspecialchars($item['image_url']) ?>');"></div>
                        <div class="item-details">
                            <div class="item-header">
                                <h3>
                                    <a href="index.php?page=product&product_id=<?= $item['product_id'] ?>">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </a>
                                </h3>
                                <div class="item-meta">
                                    <span>Size:
                                        <span class="current-size" data-size-id="<?= $item['size_id'] ?>">
                                            <?= htmlspecialchars($item['size']) ?>
                                        </span>
                                    </span>
                                    <!-- static color placeholder -->
                                    <span>Color: <span class="current-color">Default</span></span>
                                </div>
                                <div>
                                    <span class="customize-option change-size">Change Size</span>
                                </div>
                            </div>
                            <div class="item-actions">
                                <div class="quantity-selector">
                                    <button class="quantity-btn minus">-</button>
                                    <input type="number" value="<?= $item['quantity'] ?>" class="quantity-input" min="1">
                                    <button class="quantity-btn plus">+</button>
                                </div>
                                <button class="remove-item">
                                    <i class="far fa-trash-alt"></i> Remove
                                </button>
                            </div>
                        </div>
                        <div class="item-price">
                            ₱<?= number_format($item['price'], 2) ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>

        </section>

        <!-- Empty cart state (hidden by default) -->
        <section class="empty-cart">
            <div class="empty-cart-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <h2 class="empty-cart-title">Your Venusia Cart is Empty</h2>
            <p class="empty-cart-text">Discover our new collection of timeless pieces crafted with care and designed for comfort and elegance.</p>
            <a href="index.php?page=store" class="shop-btn">Start Shopping</a>
        </section>

        <aside class="order-summary">
            <h2 class="summary-title">Order Summary</h2>
            <div class="summary-row">
                <span>Selected Items (<span id="selected-count">2</span>)</span>
                <span>₱<span id="subtotal">434.00</span></span>
            </div>
            <div class="summary-row">
                <span>Shipping</span>
                <span id="shipping">Free</span>
            </div>
            <div class="summary-row">
                <span>Tax</span>
                <span>₱<span id="tax">47.36</span></span>
            </div>

            <!-- Promo code discount row (hidden by default) -->
            <div class="summary-row promo-discount" style="display: none;">
                <span>Discount <span class="promo-code-name"></span> <span class="promo-discount-remove">(Remove)</span></span>
                <span class="discount-amount">-₱0.00</span>
            </div>

            <div class="summary-row summary-total">
                <span>Total</span>
                <span>₱<span id="total">481.36</span></span>
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
    </div>
</div>

<!-- Customization Modal -->
<div class="cart-modal" id="customizationModal">
    <div class="cart-modal-content">
        <div class="cart-modal-header">
            <h3 class="cart-modal-title">Customize Item</h3>
            <button class="close-cart-modal">&times;</button>
        </div>
        <div class="option-group">
            <span class="option-title">Size</span>
            <div class="option-values" id="sizeOptions">
                <span class="option-value" data-id="1" data-value="XS">XS</span>
                <span class="option-value" data-id="2" data-value="S">S</span>
                <span class="option-value" data-id="3" data-value="M">M</span>
                <span class="option-value" data-id="4" data-value="L">L</span>
                <span class="option-value" data-id="5" data-value="XL">XL</span>
            </div>
        </div>
        <div class="cart-modal-actions">
            <button class="cart-modal-btn cart-cancel-btn">Cancel</button>
            <button class="cart-modal-btn cart-save-btn">Save Changes</button>
        </div>
    </div>
</div>