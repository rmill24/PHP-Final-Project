<?php
require_once __DIR__ . '/../includes/db.php';

// Get user ID and token from the URL
$userId = $_GET['user'] ?? null;
$token = $_GET['token'] ?? null;

if (!$userId || !$token) {
    echo "❌ Missing verification information.";
    exit;
}

// Fetch token from DB
$stmt = $db->prepare("SELECT token FROM verification_tokens WHERE user_id = ?");
$stmt->execute([$userId]);
$storedToken = $stmt->fetchColumn();

if ($storedToken && hash_equals($storedToken, $token)) {
    // Mark user as verified
    $db->prepare("UPDATE users SET email_verified = 1 WHERE id = ?")->execute([$userId]);

    // Delete token
    $db->prepare("DELETE FROM verification_tokens WHERE user_id = ?")->execute([$userId]);

    echo "✅ Your email has been successfully verified. You may now log in.";
} else {
    echo "❌ Invalid or expired verification link.";
}

