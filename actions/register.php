<?php
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = $_POST['firstName'];
    $last = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $token = bin2hex(random_bytes(16));

    $stmt = $db->prepare("INSERT INTO users (first_name, last_name, email, phone_number, address, password, email_verified) VALUES (?, ?, ?, ?, ?, ?, 0)");
    $stmt->execute([$first, $last, $email, $phone, $address, $password]);

    $userId = $db->lastInsertId();

    // Save token in the database
    $stmt = $db->prepare("INSERT INTO verification_tokens (user_id, token) VALUES (?, ?)");
    $stmt->execute([$userId, $token]);

    // Change this to real hosted domain
    $verifyLink = "http://localhost/PHP-Final-Project/actions/verify.php?user=$userId&token=$token";

    // local testing
    echo "Check your email to verify your account:<br>";
    echo "<a href='$verifyLink'>$verifyLink</a>";

    // header("Location: /PHP-Final-Project/index.php?page=home&registered=1");
    // exit;
}
