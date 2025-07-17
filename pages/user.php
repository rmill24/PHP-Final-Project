<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=home');
    exit;
}

// Fetch user data from the database
$stmt = $db->prepare("SELECT first_name, last_name, email, phone_number, address FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fallback if user not found
if (!$user) {
    echo "<p>User not found.</p>";
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
                    <div class="order-card">
                        <div class="order-header">
                            <span class="order-number">#ORD-78945</span>
                            <span class="order-date">June 12, 2025</span>
                            <span class="order-status delivered">Delivered</span>
                            <span class="order-total">$189.99</span>
                        </div>
                        <div class="order-products">
                            <div class="product-preview">
                                <img src="https://amourlinen.com/cdn/shop/files/DSC_5973_6e0d2ea1-8f0e-4804-90a6-8d186eb6e980.jpg?v=1749550330" alt="Product">
                                <span>Linen Wrap Dress</span>
                            </div>
                            <div class="product-preview">
                                <img src="https://image.uniqlo.com/UQ/ST3/ph/imagesgoods/460311/item/phgoods_31_460311_3x4.jpg?width=423" alt="Product">
                                <span>Wide Leg Pants</span>
                            </div>
                        </div>
                        <div class="order-actions">
                            <a href="#" class="btn-outline">View All</a>
                            <a href="index.php?page=store" class="btn-outline">Buy Again</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>