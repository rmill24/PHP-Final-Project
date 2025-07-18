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
                <a href="#" class="btn-outline">EDIT PROFILE</a>
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
                        <span>Order History</span>
                    </a>
                </nav>
            </div>

            <!-- Profile Content -->
            <div class="profile-content">
                <div class="content-section">
                    <h3>Account Details</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Full Name</label>
                            <p><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></p>
                        </div>
                        <div class="detail-item">
                            <label>Email</label>
                            <p><?= htmlspecialchars($user['email']) ?></p>
                        </div>
                        <div class="detail-item">
                            <label>Phone</label>
                            <p><?= htmlspecialchars($user['phone_number']) ?></p>
                        </div>
                        <div class="detail-item">
                            <label>Address</label>
                            <p><?= htmlspecialchars($user['address']) ?></p>
                        </div>
                    </div>
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