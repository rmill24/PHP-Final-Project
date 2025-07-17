<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "You must be logged in.";
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

// Ensure cart exists for the user
$stmt = $db->prepare("SELECT id FROM cart WHERE user_id = ?");
$stmt->execute([$userId]);
$cartId = $stmt->fetchColumn();

if (!$cartId) {
    $stmt = $db->prepare("INSERT INTO cart (user_id) VALUES (?)");
    $stmt->execute([$userId]);
    $cartId = $db->lastInsertId();
}

// Check if item already exists
$stmt = $db->prepare("SELECT id FROM cart_item WHERE cart_id = ? AND product_id = ? AND size_id = ?");
$stmt->execute([$cartId, $productId, $sizeId]);
$itemId = $stmt->fetchColumn();

if ($itemId) {
    // Update quantity
    $stmt = $db->prepare("UPDATE cart_item SET quantity = quantity + ? WHERE id = ?");
    $stmt->execute([$quantity, $itemId]);
} else {
    $stmt = $db->prepare("INSERT INTO cart_item (cart_id, product_id, size_id, quantity) VALUES (?, ?, ?, ?)");
    $stmt->execute([$cartId, $productId, $sizeId, $quantity]);
}

echo "added";
