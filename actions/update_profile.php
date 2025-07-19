<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/UserModel.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$userModel = new UserModel($db);
$userId = $_SESSION['user_id'];

// Validate and sanitize input data
$firstName = trim($_POST['first_name'] ?? '');
$lastName = trim($_POST['last_name'] ?? '');
$phoneNumber = trim($_POST['phone_number'] ?? '');
$address = trim($_POST['address'] ?? '');

// Basic validation
$errors = [];

if (empty($firstName)) {
    $errors[] = 'First name is required';
} elseif (strlen($firstName) < 2 || strlen($firstName) > 50) {
    $errors[] = 'First name must be between 2 and 50 characters';
} elseif (!preg_match('/^[a-zA-Z\s]+$/', $firstName)) {
    $errors[] = 'First name can only contain letters and spaces';
}

if (empty($lastName)) {
    $errors[] = 'Last name is required';
} elseif (strlen($lastName) < 2 || strlen($lastName) > 50) {
    $errors[] = 'Last name must be between 2 and 50 characters';
} elseif (!preg_match('/^[a-zA-Z\s]+$/', $lastName)) {
    $errors[] = 'Last name can only contain letters and spaces';
}

if (!empty($phoneNumber)) {
    // Remove all non-digit characters for validation
    $digitsOnly = preg_replace('/[^0-9]/', '', $phoneNumber);
    
    if (strlen($digitsOnly) !== 11) {
        $errors[] = 'Phone number must be exactly 11 digits';
    } elseif (!preg_match('/^[0-9+\-\s()]+$/', $phoneNumber)) {
        $errors[] = 'Phone number contains invalid characters';
    }
}

if (!empty($address) && strlen($address) > 255) {
    $errors[] = 'Address must not exceed 255 characters';
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Update profile
$updateData = [
    'first_name' => $firstName,
    'last_name' => $lastName,
    'phone_number' => $phoneNumber,
    'address' => $address
];

$success = $userModel->updateProfile($userId, $updateData);

if ($success) {
    // Update session data
    $_SESSION['first_name'] = $firstName;
    
    echo json_encode([
        'success' => true, 
        'message' => 'Profile updated successfully',
        'data' => $updateData
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
}
?>
