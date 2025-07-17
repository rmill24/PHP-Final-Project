<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Fetch user from database
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "error";
        exit;
    }

    if (!$user['email_verified']) {
        echo "unverified";
        exit;
    }

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['first_name'] = $user['first_name'];
        echo "redirect:profile";
    } else {
        echo "error";
    }

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
            <a href="#" class="sign-up-link" onclick="handleSignUp()">Sign up here</a>
        </p>
    </div>
</div>