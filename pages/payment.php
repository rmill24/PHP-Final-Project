<?php
// payment.php - Complete Payment Method Page for Women's Clothing Website

// Sample cart data (in a real application, this would come from the session/database)
$cartItems = [
    [
        'name' => 'Silk Camisole',
        'size' => 'S',
        'color' => 'Ivory',
        'quantity' => 1,
        'price' => 89.00,
        'image' => 'https://images.unsplash.com/photo-1551232864-3f0890e580d9?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'
    ],
    [
        'name' => 'Wool Blazer',
        'size' => 'M',
        'color' => 'Camel',
        'quantity' => 1,
        'price' => 245.00,
        'image' => 'https://images.unsplash.com/photo-1585487000160-6ebcfceb0d03?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'
    ]
];

// Calculate totals
$subtotal = array_reduce($cartItems, function($carry, $item) {
    return $carry + ($item['price'] * $item['quantity']);
}, 0);

// Check for discount in session (passed from cart.php)
$discount = 0;
$discountCode = '';
if (isset($_SESSION['discount'])) {
    $discount = $_SESSION['discount']['amount'];
    $discountCode = $_SESSION['discount']['code'];
}

$shipping = 0; // Free shipping
$taxRate = 0.08; // 8% tax
$tax = ($subtotal - $discount) * $taxRate;
$total = $subtotal - $discount + $shipping + $tax;

// Clear discount from session after displaying it
unset($_SESSION['discount']);
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
                    <form class="payment-form" id="payment-form" method="post" action="process_payment.php">
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
                        <input type="hidden" name="subtotal" value="<?php echo $subtotal; ?>">
                        <input type="hidden" name="discount" value="<?php echo $discount; ?>">
                        <input type="hidden" name="discount_code" value="<?php echo $discountCode; ?>">
                        <input type="hidden" name="tax" value="<?php echo $tax; ?>">
                        <input type="hidden" name="total" value="<?php echo $total; ?>">
                        
                        <button type="submit" class="btn-place-order">Place Order</button>
                    </form>
                </div>
                
                <div class="method-content" id="seabank">
                    <form class="payment-form" id="seabank-form" method="post" action="process_payment.php">
                        
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
                    <form class="payment-form" id="gcash-form" method="post" action="process_payment.php">
                        
                        <div class="form-group">
                            <label for="gcash-mobile">GCash Registered Mobile Number</label>
                            <input type="tel" id="gcash-mobile" name="gcash_mobile" placeholder="09XXXXXXXXX" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="gcash-otp">OTP (Sent to your mobile)</label>
                            <input type="text" id="gcash-otp" name="gcash_otp" placeholder="Enter 6-digit OTP" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="gcash-pin">GCash MPIN</label>
                            <input type="password" id="gcash-pin" name="gcash_pin" placeholder="Enter your 4-digit MPIN" required>
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
                
                <div class="summary-items">
                    <?php foreach ($cartItems as $item): ?>
                    <div class="summary-item">
                        <div class="item-image" style="background-image: url('<?php echo $item['image']; ?>');"></div>
                        <div class="item-details">
                            <h4><?php echo $item['name']; ?></h4>
                            <p>Size: <?php echo $item['size']; ?> | Color: <?php echo $item['color']; ?></p>
                            <p>Qty: <?php echo $item['quantity']; ?></p>
                        </div>
                        <div class="item-price">$<?php echo number_format($item['price'], 2); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="summary-totals">
                    <div class="total-row">
                        <span>Subtotal</span>
                        <span>$<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    
                    <?php if ($discount > 0): ?>
                    <div class="total-row discount-row">
                        <span>Discount (<?php echo $discountCode; ?>)</span>
                        <span>-$<?php echo number_format($discount, 2); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="total-row">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    <div class="total-row">
                        <span>Tax</span>
                        <span>$<?php echo number_format($tax, 2); ?></span>
                    </div>
                    <div class="total-row grand-total">
                        <span>Total</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                </div>
                
                <div class="return-policy">
                    <i class="fas fa-undo-alt"></i>
                    <p>Free returns within 30 days. <a href="#">See our return policy</a></p>
                </div>
            </div>
        </div>
    </div>