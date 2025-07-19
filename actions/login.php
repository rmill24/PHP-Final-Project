<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/UserModel.php';

$userModel = new UserModel($db);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = $userModel->login($email, $password);

    if (!$user) {
        // Check if the email exists but is unverified
        $unverifiedCheck = $userModel->getByEmail($email);
        if ($unverifiedCheck && !$unverifiedCheck['email_verified']) {
            header("Location: ../index.php?page=unverified");
            exit;
        } else {
            header("Location: ../index.php?page=error");
            exit;
        }
        exit;
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['first_name'] = $user['first_name'];

    echo "redirect:profile";
    exit;
}
