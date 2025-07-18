<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
            echo "unverified";
        } else {
            echo "error";
        }
        exit;
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['first_name'] = $user['first_name'];

    echo "redirect:profile";
    exit;
}
?>

<!-- Modal -->
<div class="modal-overlay" id="loginModal">
    <div class="modal">
        <button class="close-btn" onclick="closeModal()">&times;</button>
        <div class="modal-header">
            <div class="modal-title-container">
                <h2 class="modal-title">Welcome back</h2>
                <p class="modal-subtitle">Sign in to your account</p>
            </div>
        </div>
        <form onsubmit="handleLogin(event)">
            <div class="form-group">
                <input type="email" name="email" id="loginEmail" placeholder="Email" class="form-input" required />
            </div>
            <div class="form-group">
                <input type="password" name="password" id="loginPassword" placeholder="Password" class="form-input" required />
            </div>
            <button type="submit" class="sign-in-btn">Sign In</button>
            <div id="loginError" style="color: red; margin-bottom: 10px;"></div>
        </form>
        <p class="sign-up-text">
            Don't have an account yet?
            <a href="#register" class="sign-up-link" onclick="closeModal()">Sign up here</a>
        </p>
    </div>
</div>