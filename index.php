<?php
session_start();

$page = $_GET['page'] ?? 'home';
$allowed_pages = ['home', 'about', 'store', 'cart', 'payment', 'user', 'product', 'order_success'];

if (!in_array($page, $allowed_pages)) {
    $page = 'home';
}

// Set title based on page
$page_titles = [
    'home' => 'Home | Venusia',
    'about' => 'About Us | Venusia',
    'store' => 'Store | Venusia',
    'cart' => 'Your Cart | Venusia',
    'payment' => 'Payment | Venusia',
    'product' => 'Product | Venusia',
    'user' => 'User Profile | Venusia',
    'order_success' => 'Order Success | Venusia'
];

$page_title = $page_titles[$page] ?? 'Venusia';

// Get page content
ob_start();
include "pages/$page.php";
$page_content = ob_get_clean();

$current_page = $page;
include 'layout.php';
