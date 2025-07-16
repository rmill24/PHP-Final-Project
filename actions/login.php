<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Dummy login
    if ($email === "admin@example.com" && $password === "1234") {
        $_SESSION['user'] = $email;
        echo "success";
    } else {
        echo "error";
    }
    exit();
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
                <input type="email" placeholder="Email" class="form-input" required />
            </div>
            <div class="form-group">
                <input type="password" placeholder="Password" class="form-input" required />
            </div>
            <button type="submit" class="sign-in-btn">Sign In</button>
        </form>
        <p class="sign-up-text">
            Don't have an account yet?
            <a href="#" class="sign-up-link" onclick="handleSignUp()">Sign up here</a>
        </p>
    </div>
</div>