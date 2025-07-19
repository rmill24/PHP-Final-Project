<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/UserModel.php';

$userModel = new UserModel($db);

$userId = $_GET['user'] ?? null;
$token = $_GET['token'] ?? null;

if (!$userId || !$token) {
    header('Location: ../index.php?page=error&message=' . urlencode('Invalid verification link.'));
    exit;
}

if ($userModel->verifyUser($userId, $token)) {
    header('Location: ../index.php?page=verified');
    exit;
} else {
    // Check if user is already verified
    $user = $userModel->getById($userId);
    if ($user && $user['email_verified'] == 1) {
        header('Location: ../index.php?page=verified&message=' . urlencode('Your account is already verified.'));
        exit;
    }
    
    // Token is invalid or expired
    header('Location: ../index.php?page=error&message=' . urlencode('Verification link has expired or is invalid.'));
    exit;
}
