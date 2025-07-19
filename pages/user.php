<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/db.php';
require_once 'models/UserModel.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=home');
    exit;
}

$userModel = new UserModel($db);
$user = $userModel->getById($_SESSION['user_id']);

// After $user is fetched, get orders
$orders = $userModel->getRecentOrdersWithItems($_SESSION['user_id']);

if (!$user) {
    session_destroy(); // clean up invalid session
    header('Location: index.php?page=home');
    exit;
}
?>

<!-- User Profile Section -->
<section class="user-profile">
    <div class="container">
        <div class="profile-header">
            <h2>MY PROFILE</h2>
            <div class="profile-actions">
                <a href="#" class="btn-outline" id="editProfileBtn">EDIT PROFILE</a>
                <button type="submit" class="btn-outline save-btn" id="saveProfileBtn" form="profileForm" style="display: none;">SAVE CHANGES</button>
                <a href="#" class="btn-outline cancel-btn" id="cancelEditBtn" style="display: none;">CANCEL</a>
                <a href="actions/logout.php" class="btn-outline">SIGN OUT</a>
            </div>
        </div>

        <div class="profile-layout">
            <!-- Profile Sidebar -->
            <div class="profile-sidebar">
                <div class="profile-card">
                    <div class="profile-avatar">
                        <img src="https://cdn2.iconfinder.com/data/icons/business-and-finance-related-hand-gestures/256/face_female_blank_user_avatar_mannequin-512.png">
                    </div>
                    <div class="profile-info">
                        <h3><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h3>
                    </div>
                </div>

                <nav class="profile-menu">
                    <a href="#" class="menu-item active">
                        <i class="icon-user"></i>
                        <span>Account Details</span>
                    </a>
                    <a href="#" class="menu-item">
                        <i class="icon-bag"></i>
                        <span>Recent Orders</span>
                    </a>
                </nav>
            </div>

            <!-- Profile Content -->
            <div class="profile-content">
                <div class="content-section">
                    <h3>Account Details</h3>
                    <form id="profileForm" method="post">
                        <div class="detail-grid">
                            <div class="detail-item" data-field="first_name">
                                <label>First Name</label>
                                <p class="detail-display"><?= htmlspecialchars($user['first_name']) ?></p>
                                <input type="text" name="first_name" class="detail-input" value="<?= htmlspecialchars($user['first_name']) ?>" data-original-value="<?= htmlspecialchars($user['first_name']) ?>" style="display: none;" required>
                            </div>
                            <div class="detail-item" data-field="last_name">
                                <label>Last Name</label>
                                <p class="detail-display"><?= htmlspecialchars($user['last_name']) ?></p>
                                <input type="text" name="last_name" class="detail-input" value="<?= htmlspecialchars($user['last_name']) ?>" data-original-value="<?= htmlspecialchars($user['last_name']) ?>" style="display: none;" required>
                            </div>
                            <div class="detail-item" data-field="email">
                                <label>Email</label>
                                <p class="detail-display"><?= htmlspecialchars($user['email']) ?></p>
                                <p class="detail-readonly" style="display: none; color: #999; font-style: italic;">Email cannot be changed</p>
                            </div>
                            <div class="detail-item" data-field="phone_number">
                                <label>Phone</label>
                                <p class="detail-display"><?= htmlspecialchars($user['phone_number'] ?: 'Not provided') ?></p>
                                <input type="tel" name="phone_number" class="detail-input" value="<?= htmlspecialchars($user['phone_number']) ?>" data-original-value="<?= htmlspecialchars($user['phone_number']) ?>" style="display: none;">
                            </div>
                            <div class="detail-item address-section" data-field="address">
                                <label>Address</label>
                                <p class="detail-display"><?= htmlspecialchars($user['address'] ?: 'Not provided') ?></p>
                                <div class="address-fields" style="display: none;">
                                    <div class="address-row">
                                        <div class="address-field">
                                            <label for="street">Street Address</label>
                                            <input type="text" id="street" name="street" class="detail-input address-input" placeholder="123 Main Street" data-original-value="">
                                        </div>
                                    </div>
                                    <div class="address-row">
                                        <div class="address-field">
                                            <label for="city">City</label>
                                            <input type="text" id="city" name="city" class="detail-input address-input" placeholder="Manila" data-original-value="">
                                        </div>
                                        <div class="address-field">
                                            <label for="state">State/Province</label>
                                            <input type="text" id="state" name="state" class="detail-input address-input" placeholder="Metro Manila" data-original-value="">
                                        </div>
                                    </div>
                                    <div class="address-row">
                                        <div class="address-field">
                                            <label for="zipCode">ZIP Code</label>
                                            <input type="text" id="zipCode" name="zipCode" class="detail-input address-input" placeholder="1000" data-original-value="">
                                        </div>
                                        <div class="address-field">
                                            <label for="country">Country</label>
                                            <select id="country" name="country" class="detail-input address-input" data-original-value="">
                                                <option value="">Select Country</option>
                                                <option value="Philippines">Philippines</option>
                                                <option value="United States">United States</option>
                                                <option value="Canada">Canada</option>
                                                <option value="United Kingdom">United Kingdom</option>
                                                <option value="Australia">Australia</option>
                                                <option value="Singapore">Singapore</option>
                                                <option value="Malaysia">Malaysia</option>
                                                <option value="Thailand">Thailand</option>
                                                <option value="Indonesia">Indonesia</option>
                                                <option value="Vietnam">Vietnam</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="content-section">
                    <h3>Recent Orders</h3>

                    <?php if (empty($orders)) : ?>
                        <p>No orders found.</p>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <div class="order-card">
                                <div class="order-header">
                                    <span class="order-number">ORDER ID#<?= htmlspecialchars($order['id']) ?></span>
                                    <span class="order-date"><?= date('F j, Y', strtotime($order['created_at'])) ?></span>
                                    <!-- Assuming you have a status column, else remove or hardcode -->
                                    <span class="order-status delivered">Delivered</span>
                                    <span class="order-total">₱<?= number_format($order['total'], 2) ?></span>
                                </div>
                                <div class="order-products">
                                    <?php foreach ($order['items'] as $item): ?>
                                        <div class="product-preview">
                                            <img src="<?= htmlspecialchars($item['product_image']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>">
                                            <span><?= htmlspecialchars($item['product_name']) ?> (Size: <?= htmlspecialchars($item['size_label']) ?>)</span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="order-actions">
                                    <a href="index.php?page=order_detail&order_id=<?= urlencode($order['id']) ?>" class="btn-outline">View All</a>
                                    <a href="index.php?page=store" class="btn-outline">Buy Again</a>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</section>