<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/UserModel.php';

// Set JSON response header
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    
    if (empty($email)) {
        echo json_encode(['available' => true]);
        exit;
    }
    
    // Validate email format first
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['available' => true]); // Let frontend validation handle format errors
        exit;
    }
    
    $userModel = new UserModel($db);
    $existingUser = $userModel->getByEmail($email);
    
    if ($existingUser) {
        echo json_encode(['available' => false, 'message' => 'This email is already registered. Please use a different email or try signing in.']);
    } else {
        echo json_encode(['available' => true]);
    }
} else {
    echo json_encode(['available' => true]);
}
?>
