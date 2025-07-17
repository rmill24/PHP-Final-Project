<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/CartModel.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo 'unauthorized';
    exit;
}

$userId = $_SESSION['user_id'];
$cartItemId = $_POST['cart_item_id'] ?? null;
$quantity = $_POST['quantity'] ?? null;

if (!$cartItemId || !$quantity || $quantity < 1) {
    http_response_code(400);
    echo 'invalid';
    exit;
}

$cartModel = new CartModel($db, $userId);
$success = $cartModel->updateItemQuantity($cartItemId, $quantity);

echo $success ? 'updated' : 'error';
