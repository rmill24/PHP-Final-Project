<?php
// Authentication utility functions
require_once __DIR__ . '/session.php';

/**
 * Check if user is authenticated and redirect if not
 * @param string $redirectTo Where to redirect if not authenticated
 */
function requireAuth($redirectTo = 'index.php?page=home') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: $redirectTo");
        exit;
    }
    return $_SESSION['user_id'];
}

/**
 * Check if user is authenticated for AJAX requests
 * @param string $errorMessage Error message to return
 */
function requireAuthAjax($errorMessage = 'unauthorized') {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo $errorMessage;
        exit;
    }
    return $_SESSION['user_id'];
}
