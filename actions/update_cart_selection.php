<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/CartModel.php';

$userId = requireAuthAjax();
$cartItemId = $_POST['cart_item_id'] ?? null;
$selected = isset($_POST['selected']) ? (bool)$_POST['selected'] : false;

if (!$cartItemId) {
    error_log("Missing cart_item_id");
    http_response_code(400);
    header("Location: ../index.php?page=error");
    exit;
}

$cartModel = new CartModel($db, $userId);
$success = $cartModel->updateItemSelection($cartItemId, $selected);

echo $success ? "updated" : "failed";
