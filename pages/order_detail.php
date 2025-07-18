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

if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    echo "Invalid order.";
    exit;
}

$orderId = (int)$_GET['order_id'];
$userId = $_SESSION['user_id'];

$userModel = new UserModel($db);

// Fetch order, check ownership
$order = $userModel->getOrderByIdAndUser($orderId, $userId);

if (!$order) {
    echo "Order not found or access denied.";
    exit;
}

// Fetch order items
$orderItems = $userModel->getOrderItems($orderId);
?>
<div class="container">
    <h1>Order ID#<?= htmlspecialchars($order['id']) ?></h1>
    <h5>Placed on: <?= htmlspecialchars($order['created_at']) ?></h5>
    <?php
    if ($order['discount_code']) {
        echo '<h5>Discount Code: ' . htmlspecialchars($order['discount_code']) . '</h5>';
    }
    ?>
    <h5>Total: ₱<?= htmlspecialchars(number_format($order['total'], 2)) ?></h5>
    <br>
    <h2>Items</h2>
    <?php foreach ($orderItems as $item): ?>
        <div class="item-card">
            <img src="<?= htmlspecialchars($item['product_image']) ?>">
            <?= htmlspecialchars($item['product_name']) ?>
            (Size: <?= htmlspecialchars($item['size_label']) ?>)
            x<?= (int)$item['quantity'] ?> - ₱<?= number_format($item['price'], 2) ?>
        </div>
    <?php endforeach; ?>
    <a href="index.php?page=user"><button>Back to Profile</button></a>
</div>