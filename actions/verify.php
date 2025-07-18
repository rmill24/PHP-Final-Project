<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/UserModel.php';

$userModel = new UserModel($db);

$userId = $_GET['user'] ?? null;
$token = $_GET['token'] ?? null;

if ($userId && $token && $userModel->verifyUser($userId, $token)) {
    header('Location: ../index.php?page=verified');
    exit;
} else {
    echo "❌ Invalid or expired verification link.";
}
