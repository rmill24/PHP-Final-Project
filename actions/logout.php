<?php
// Start session if not already started
require_once __DIR__ . '/../includes/session.php';

// Clear all session variables
$_SESSION = [];

// Delete the session cookie if it exists
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect to homepage or login
header("Location: ../index.php?page=home");
exit;
