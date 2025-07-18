<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/CartModel.php';

$userId = requireAuthAjax('unauthorized');

$cartItemId = $_POST['cart_item_id'] ?? null;
$newSizeId = $_POST['size_id'] ?? null;

if (!$cartItemId || !$newSizeId) {
    http_response_code(400);
    header("Location: ../index.php?page=error");
    exit;
}

$cartModel = new CartModel($db, $userId);
$success = $cartModel->updateItemSize($cartItemId, $newSizeId);

echo $success ? 'updated' : 'error';
