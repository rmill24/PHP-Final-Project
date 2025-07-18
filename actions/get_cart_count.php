<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/db.php';

$count = 0;

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $stmt = $db->prepare("
        SELECT SUM(quantity) as total
        FROM cart_item
        WHERE cart_id = (SELECT id FROM cart WHERE user_id = ?)
    ");
    $stmt->execute([$userId]);
    $count = $stmt->fetchColumn() ?: 0;
}

echo json_encode(['count' => (int)$count]);
