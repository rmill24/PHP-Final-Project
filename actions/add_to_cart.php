<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/CartModel.php';

if (!isset($_SESSION['user_id'])) {
    echo "unauthorized";
    exit;
}


$userId = $_SESSION['user_id'];
$productId = $_POST['product_id'] ?? null;
$sizeId = $_POST['size_id'] ?? null;
$quantity = $_POST['quantity'] ?? 1;

if (!$productId || !$sizeId) {
    http_response_code(400);
    echo "Invalid request.";
    exit;
}

$cartModel = new CartModel($db, $userId);
$cartModel->addOrUpdateItem($productId, $sizeId, $quantity);

echo "added";
