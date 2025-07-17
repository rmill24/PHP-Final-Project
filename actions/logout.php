<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

session_unset();
session_destroy();

// Optional: force cookie removal
setcookie("PHPSESSID", "", time() - 3600, "/");

header("Location: /PHP-Final-Project/index.php?page=home");
exit;
