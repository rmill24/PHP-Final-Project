<?php
session_start();

$page = $_GET['page'] ?? 'home';
$allowed_pages = ['home', 'about', 'store', 'cart', 'payment'];

if (!in_array($page, $allowed_pages)) {
    $page = 'home';
}

ob_start();
include "pages/$page.php";
$page_content = ob_get_clean();

$current_page = $page;
include 'layout.php';