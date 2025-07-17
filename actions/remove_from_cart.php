<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "Unauthorized";
    exit;
}

$cartItemId = $_POST['cart_item_id'] ?? null;

if (!$cartItemId) {
    http_response_code(400);
    echo "Invalid item";
    exit;
}

$stmt = $db->prepare("DELETE FROM cart_item WHERE id = ?");
$success = $stmt->execute([$cartItemId]);

echo $success ? "removed" : "error";
