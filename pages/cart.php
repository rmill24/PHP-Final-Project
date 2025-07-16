
    <div class="container">
        <header class="cart-header">
            <h1 class="cart-title">Your Venusia Cart</h1>
            <p class="cart-subtitle">Select items to proceed to checkout</p>
        </header>

        <main class="cart-content">
            <!-- Cart with items -->
            <section class="cart-items">
                <article class="cart-item">
                    <div class="item-selector">
                        <input type="checkbox" class="item-checkbox" checked>
                    </div>
                    <div class="item-image" style="background-image: url('https://images.unsplash.com/photo-1551232864-3f0890e580d9?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80');"></div>
                    <div class="item-details">
                        <div class="item-header">
                            <h3>Silk Slip Dress</h3>
                            <div class="item-meta">
                                <span>Size: <span class="current-size">S</span></span>
                                <span>Color: <span class="current-color">Champagne</span></span>
                            </div>
                            <div>
                                <span class="customize-option change-size">Change Size</span>
                                <span class="customize-option change-color">Change Color</span>
                            </div>
                        </div>
                        <div class="item-actions">
                            <div class="quantity-selector">
                                <button class="quantity-btn minus">-</button>
                                <input type="number" value="1" min="1" class="quantity-input">
                                <button class="quantity-btn plus">+</button>
                            </div>
                            <button class="remove-item">
                                <i class="far fa-trash-alt"></i> Remove
                            </button>
                        </div>
                    </div>
                    <div class="item-price">
                        $189.00
                    </div>
                </article>

                <article class="cart-item">
                    <div class="item-selector">
                        <input type="checkbox" class="item-checkbox" checked>
                    </div>
                    <div class="item-image" style="background-image: url('https://images.unsplash.com/photo-1585487000160-6ebcfceb0d03?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80');"></div>
                    <div class="item-details">
                        <div class="item-header">
                            <h3>Cashmere Cardigan</h3>
                            <div class="item-meta">
                                <span>Size: <span class="current-size">M</span></span>
                                <span>Color: <span class="current-color">Ivory</span></span>
                            </div>
                            <div>
                                <span class="customize-option change-size">Change Size</span>
                                <span class="customize-option change-color">Change Color</span>
                            </div>
                        </div>
                        <div class="item-actions">
                            <div class="quantity-selector">
                                <button class="quantity-btn minus">-</button>
                                <input type="number" value="1" min="1" class="quantity-input">
                                <button class="quantity-btn plus">+</button>
                            </div>
                            <button class="remove-item">
                                <i class="far fa-trash-alt"></i> Remove
                            </button>
                        </div>
                    </div>
                    <div class="item-price">
                        $245.00
                    </div>
                </article>

                <article class="cart-item">
                    <div class="item-selector">
                        <input type="checkbox" class="item-checkbox">
                    </div>
                    <div class="item-image" style="background-image: url('https://images.unsplash.com/photo-1591047139829-d91aecb6caea?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80');"></div>
                    <div class="item-details">
                        <div class="item-header">
                            <h3>Linen Wide-Leg Pants</h3>
                            <div class="item-meta">
                                <span>Size: <span class="current-size">6</span></span>
                                <span>Color: <span class="current-color">Oatmeal</span></span>
                            </div>
                            <div>
                                <span class="customize-option change-size">Change Size</span>
                                <span class="customize-option change-color">Change Color</span>
                            </div>
                        </div>
                        <div class="item-actions">
                            <div class="quantity-selector">
                                <button class="quantity-btn minus">-</button>
                                <input type="number" value="1" min="1" class="quantity-input">
                                <button class="quantity-btn plus">+</button>
                            </div>
                            <button class="remove-item">
                                <i class="far fa-trash-alt"></i> Remove
                            </button>
                        </div>
                    </div>
                    <div class="item-price">
                        $158.00
                    </div>
                </article>
            </section>

            <!-- Empty cart state (hidden by default) -->
            <section class="empty-cart">
                <div class="empty-cart-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <h2 class="empty-cart-title">Your Venusia Cart is Empty</h2>
                <p class="empty-cart-text">Discover our new collection of timeless pieces crafted with care and designed for comfort and elegance.</p>
                <a href="#" class="shop-btn">Start Shopping</a>
            </section>

            <aside class="order-summary">
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

    