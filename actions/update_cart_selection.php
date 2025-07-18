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
$cartItemId = $_POST['cart_item_id'] ?? null;
$selected = isset($_POST['selected']) ? (bool)$_POST['selected'] : false;

if (!$cartItemId) {
    error_log("Missing cart_item_id");
    http_response_code(400);
    echo "Invalid request.";
    exit;
}

$cartModel = new CartModel($db, $userId);
$success = $cartModel->updateItemSelection($cartItemId, $selected);

echo $success ? "updated" : "failed";
