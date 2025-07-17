<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/CartModel.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo 'unauthorized';
    exit;
}

$cartItemId = $_POST['cart_item_id'] ?? null;
$newSizeId = $_POST['size_id'] ?? null;
$userId = $_SESSION['user_id'];

if (!$cartItemId || !$newSizeId) {
    http_response_code(400);
    echo 'invalid';
    exit;
}

$cartModel = new CartModel($db, $userId);
$success = $cartModel->updateItemSize($cartItemId, $newSizeId);

echo $success ? 'updated' : 'error';
