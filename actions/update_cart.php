<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/CartModel.php';

$userId = requireAuthAjax('unauthorized');
$cartItemId = $_POST['cart_item_id'] ?? null;
$quantity = $_POST['quantity'] ?? null;

if (!$cartItemId || !$quantity || $quantity < 1) {
    http_response_code(400);
    header("Location: ../index.php?page=error");
    exit;
}

$cartModel = new CartModel($db, $userId);
$success = $cartModel->updateItemQuantity($cartItemId, $quantity);

echo $success ? 'updated' : 'error';
