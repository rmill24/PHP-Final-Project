<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/UserModel.php';

$userModel = new UserModel($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'first_name' => $_POST['firstName'],
        'last_name'  => $_POST['lastName'],
        'email'      => $_POST['email'],
        'phone'      => $_POST['phone'],
        'address'    => $_POST['address'],
        'password'   => $_POST['password']
    ];

    $userId = $userModel->register($data);
    $token = bin2hex(random_bytes(16));
    $userModel->saveVerificationToken($userId, $token);

    // For local dev; change when hosted
    $verifyLink = "http://localhost/PHP-Final-Project/verify.php?user=$userId&token=$token";

    // Output or email this link
    echo "Check your email to verify your account:<br>";
    echo "<a href='$verifyLink'>$verifyLink</a>";

    // header("Location: /PHP-Final-Project/index.php?page=home&registered=1");
    // exit;
}
