<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Process login form submission
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Dummy login logic (replace with DB query)
    if ($email === "admin@example.com" && $password === "1234") {
        $_SESSION['user'] = $email;
        echo "success";
    } else {
        echo "error";
    }

    exit(); // Important to stop further output
}
?>

<!-- Modal HTML (this part shows only when included or loaded directly) -->
<div class="modal-overlay" style="display: none;">
    <div class="modal">
        <button class="close-btn" onclick="closeModal()">&times;</button>
        <div class="modal-header">
            <h2 class="modal-title">Welcome back</h2>
            <p class="modal-subtitle">Sign in to your account</p>
        </div>
        <form class="login-form" onsubmit="handleLogin(event)">
            <div class="form-group">
                <input type="email" name="email" class="form-input" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-input" placeholder="Password" required>
            </div>
            <button type="submit" class="sign-in-btn">Sign In</button>
        </form>
        <p class="sign-up-text">
            Don't have an account yet?
            <a href="#" class="sign-up-link" onclick="handleSignUp()">Sign up here</a>
        </p>
    </div>
</div>