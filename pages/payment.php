<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/CartModel.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=home');
    exit;
}

$cartModel = new CartModel($db, $_SESSION['user_id']);
$cartItems = $cartModel->getSelectedCartItems();

if (empty($cartItems)) {
    echo "<p>No items selected for checkout. <a href='index.php?page=cart'>Return to cart</a></p>";
    exit;
}

$subtotal = 0;

foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$discount = 0;
$shipping = 0;
$promo = $_SESSION['discount'] ?? null;
$discountCode = $promo['code'] ?? '';

if ($promo) {
    if ($promo['type'] === 'percent') {
        $discount = $subtotal * $promo['amount'];
    } elseif ($promo['type'] === 'fixed-shipping') {
        $shipping = -$promo['amount'];
    }
}

$tax = ($subtotal - $discount) * 0.09;
$total = $subtotal + $tax + $shipping - $discount;

?>


<div class="payment-container">
    <div class="payment-header">
        <h1 class="payment-title">Secure Payment</h1>
        <p class="payment-subtitle">Complete your purchase with confidence</p>
    </div>

    <div class="payment-content">
        <div class="payment-methods">
            <h2 class="section-title">Payment Method</h2>

            <div class="method-tabs">
                <button class="method-tab active" data-tab="credit-card">
                    <i class="far fa-credit-card"></i> Credit Card
                </button>
                <button class="method-tab" data-tab="seabank">
                    <i class="fas fa-building"></i> SeaBank
                </button>
                <button class="method-tab" data-tab="gcash">
                    <i class="fas fa-mobile-alt"></i> GCash
                </button>
            </div>

            <div class="method-content active" id="credit-card">
                <form class="payment-form" id="payment-form" method="post" action="actions/process_payment.php">
                    <div class="form-group">
                        <label for="card-number">Card Number</label>
                        <input type="text" id="card-number" name="card_number" placeholder="1234 5678 9012 3456" required>
                        <div class="card-icons">
                            <img src="https://cdn.manilastandard.net/wp-content/uploads/2024/11/VISA.png" alt="Visa">
                            <img src="https://images.fastcompany.com/image/upload/wp-cms/uploads/2023/04/i-4-90885664-mastercard-logo-813x457.jpg" alt="Mastercard">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="expiry-date">Expiry Date</label>
                            <input type="text" id="expiry-date" name="expiry_date" placeholder="MM/YY" required>
                        </div>
                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="text" id="cvv" name="cvv" placeholder="123" required>
                            <i class="fas fa-question-circle" title="3-digit code on back of card"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="card-name">Name on Card</label>
                        <input type="text" id="card-name" name="card_name" placeholder="As it appears on your card" required>
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" id="save-card" name="save_card" checked>
                        <label for="save-card">Save this card for future purchases</label>
                    </div>

                    <!-- Hidden fields to pass cart data -->
                    <input type="hidden" name="items[<?= $item['id'] ?>][product_id]" value="<?= $item['id'] ?>">
                    <input type="hidden" name="items[<?= $item['id'] ?>][size_id]" value="<?= $item['size_id'] ?>">
                    <input type="hidden" name="items[<?= $item['id'] ?>][quantity]" value="<?= $item['quantity'] ?>">
                    <input type="hidden" name="items[<?= $item['id'] ?>][price]" value="<?= $item['price'] ?>">

                    <button type="submit" class="btn-place-order">Place Order</button>
                </form>
            </div>

            <div class="method-content" id="seabank">
                <form class="payment-form" id="seabank-form" method="post" action="actions/process_payment.php">

                    <div class="form-group">
                        <label for="seabank-account">SeaBank Account Number</label>
                        <input type="text" id="seabank-account" name="seabank_account" placeholder="Enter your 10-digit account number" required>
                    </div>

                    <div class="form-group">
                        <label for="seabank-mobile">Registered Mobile Number</label>
                        <input type="tel" id="seabank-mobile" name="seabank_mobile" placeholder="09XXXXXXXXX" required>
                    </div>

                    <div class="form-group">
                        <label for="seabank-otp">OTP (Sent to your mobile)</label>
                        <input type="text" id="seabank-otp" name="seabank_otp" placeholder="Enter 6-digit OTP" required>
                    </div>

                    <!-- Hidden fields to pass cart data -->
                    <input type="hidden" name="payment_method" value="seabank">
                    <input type="hidden" name="subtotal" value="<?php echo $subtotal; ?>">
                    <input type="hidden" name="discount" value="<?php echo $discount; ?>">
                    <input type="hidden" name="discount_code" value="<?php echo $discountCode; ?>">
                    <input type="hidden" name="tax" value="<?php echo $tax; ?>">
                    <input type="hidden" name="total" value="<?php echo $total; ?>">

                    <button type="submit" class="btn-seabank">Pay with SeaBank</button>
                </form>
            </div>

            <div class="method-content" id="gcash">
                <form class="payment-form" id="gcash-form" method="post" action="actions/process_payment.php">

                    <div class="form-group">
                        <label for="gcash-mobile">GCash Registered Mobile Number</label>
                        <input type="tel" id="gcash-mobile" name="gcash_mobile" placeholder="09XXXXXXXXX" required>
                    </div>

                    <div class="form-group">
                        <label for="gcash-otp">OTP (Sent to your mobile)</label>
                        <input type="text" id="gcash-otp" name="gcash_otp" placeholder="Enter 6-digit OTP" required>
                    </div>

                    <!-- Hidden fields to pass cart data -->
                    <input type="hidden" name="payment_method" value="gcash">
                    <input type="hidden" name="subtotal" value="<?php echo $subtotal; ?>">
                    <input type="hidden" name="discount" value="<?php echo $discount; ?>">
                    <input type="hidden" name="discount_code" value="<?php echo $discountCode; ?>">
                    <input type="hidden" name="tax" value="<?php echo $tax; ?>">
                    <input type="hidden" name="total" value="<?php echo $total; ?>">

                    <button type="submit" class="btn-gcash">Pay with GCash</button>
                </form>
            </div>

            <div class="security-badge">
                <i class="fas fa-lock"></i>
                <span>All transactions are secure and encrypted</span>
            </div>
        </div>

        <div class="order-summary">
            <h2 class="section-title">Order Summary</h2>

            <?php foreach ($cartItems as $item): ?>
                <div class="summary-item">
                    <div class="item-image" style="background-image: url('<?= htmlspecialchars($item['image_url']) ?>');"></div>
                    <div class="item-details">
                        <h4><?= htmlspecialchars($item['name']) ?></h4>
                        <p>Size: <?= htmlspecialchars($item['size_label']) ?></p>
                        <p>Qty: <?= $item['quantity'] ?></p>
                    </div>
                    <div class="item-price">₱<?= number_format($item['price'], 2) ?></div>
                </div>
            <?php endforeach; ?>


            <div class="summary-totals">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span>₱<?php echo number_format($subtotal, 2); ?></span>
                </div>

                <?php if ($discount > 0): ?>
                    <div class="total-row discount-row">
                        <span>Discount (<?php echo $discountCode; ?>)</span>
                        <span>-₱<?php echo number_format($discount, 2); ?></span>
                    </div>
                <?php endif; ?>

                <div class="total-row">
                    <span>Shipping</span>
                    <span>Free</span>
                </div>
                <div class="total-row">
                    <span>Tax</span>
                    <span>₱<?php echo number_format($tax, 2); ?></span>
                </div>
                <div class="total-row grand-total">
                    <span>Total</span>
                    <span>₱<?php echo number_format($total, 2); ?></span>
                </div>
            </div>

            <div class="return-policy">
                <i class="fas fa-undo-alt"></i>
                <p>Free returns within 30 days. <a href="#">See our return policy</a></p>
            </div>
        </div>
    </div>
</div>