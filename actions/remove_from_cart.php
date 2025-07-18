<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

requireAuthAjax("Unauthorized");

$cartItemId = $_POST['cart_item_id'] ?? null;

if (!$cartItemId) {
    http_response_code(400);
    header("Location: ../index.php?page=error");
    exit;
}

$stmt = $db->prepare("DELETE FROM cart_item WHERE id = ?");
$success = $stmt->execute([$cartItemId]);

echo $success ? "removed" : "error";
