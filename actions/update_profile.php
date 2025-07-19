<?php
require_once __DIR__ . '/../includes/session.php';
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

// Handle address fields
$street = trim($_POST['street'] ?? '');
$city = trim($_POST['city'] ?? '');
$state = trim($_POST['state'] ?? '');
$zipCode = trim($_POST['zipCode'] ?? '');
$country = trim($_POST['country'] ?? '');

// Combine address fields into a single address string
$addressParts = array_filter([$street, $city, $state, $zipCode, $country]);
$address = !empty($addressParts) ? implode(', ', $addressParts) : '';

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

// Address validation (if any address field is provided, validate all required ones)
if (!empty($street) || !empty($city) || !empty($state) || !empty($zipCode) || !empty($country)) {
    if (empty($street) || strlen($street) < 5) {
        $errors[] = 'Street address must be at least 5 characters long';
    } elseif (strlen($street) > 100) {
        $errors[] = 'Street address must not exceed 100 characters';
    }
    
    if (empty($city) || strlen($city) < 2) {
        $errors[] = 'City must be at least 2 characters long';
    } elseif (strlen($city) > 50) {
        $errors[] = 'City must not exceed 50 characters';
    } elseif (!preg_match('/^[a-zA-Z\s\-\'\.]+$/', $city)) {
        $errors[] = 'City contains invalid characters';
    }
    
    if (empty($state) || strlen($state) < 2) {
        $errors[] = 'State/Province must be at least 2 characters long';
    } elseif (strlen($state) > 50) {
        $errors[] = 'State/Province must not exceed 50 characters';
    } elseif (!preg_match('/^[a-zA-Z\s\-\'\.]+$/', $state)) {
        $errors[] = 'State/Province contains invalid characters';
    }
    
    if (empty($zipCode)) {
        $errors[] = 'ZIP code is required';
    } elseif (!preg_match('/^[0-9]{4,10}$/', $zipCode)) {
        $errors[] = 'ZIP code must be 4-10 digits';
    }
    
    if (empty($country)) {
        $errors[] = 'Country is required';
    } elseif (strlen($country) > 50) {
        $errors[] = 'Country name must not exceed 50 characters';
    }
}

if (!empty($address) && strlen($address) > 255) {
    $errors[] = 'Complete address must not exceed 255 characters';
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
