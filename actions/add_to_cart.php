<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/CartModel.php';

$userId = requireAuthAjax();

$productId = $_POST['product_id'] ?? null;
$sizeId = $_POST['size_id'] ?? null;
$quantity = $_POST['quantity'] ?? 1;

if (!$productId || !$sizeId) {
    error_log("Missing data: product_id=$productId, size_id=$sizeId");
    http_response_code(400);
    header("Location: ../index.php?page=error");
    exit;
}

$cartModel = new CartModel($db, $userId);
$cartModel->addOrUpdateItem($productId, $sizeId, $quantity);

echo "added";
