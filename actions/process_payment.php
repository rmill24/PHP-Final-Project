<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/CartModel.php';

// Enable exceptions for PDO errors
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php?page=home');
    exit;
}

$userId = $_SESSION['user_id'];
$cartModel = new CartModel($db, $userId);
$cartItems = $cartModel->getSelectedCartItems();

// if (empty($cartItems)) {
//     echo "No items selected for checkout.";
//     exit;
// }

// Calculate subtotal from cart items (price * quantity)
$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

// Convert POST strings to floats and sanitize inputs
$discountAmount = isset($_POST['discount']) ? floatval($_POST['discount']) : 0;
$discountCode = isset($_POST['discount_code']) ? trim($_POST['discount_code']) : '';
$shipping = 0;
$tax = isset($_POST['tax']) ? floatval($_POST['tax']) : 0;

// Calculate total server-side
$total = $subtotal - $discountAmount + $shipping + $tax;
if ($total < 0) {
    $total = 0;
}

// Format decimal values for DB insert
$total = number_format($total, 2, '.', '');
$discountAmount = number_format($discountAmount, 2, '.', '');
$shipping = number_format($shipping, 2, '.', '');
$tax = number_format($tax, 2, '.', '');

try {
    // Start transaction
    $db->beginTransaction();

    // Insert order
    $stmt = $db->prepare("
        INSERT INTO orders (user_id, total, discount_code, discount_amount, shipping_cost, tax)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    if (!$stmt->execute([
        $userId,
        $total,
        $discountCode,
        $discountAmount,
        $shipping,
        $tax
    ])) {
        $errorInfo = $stmt->errorInfo();
        throw new Exception("Order insert failed: " . $errorInfo[2]);
    }

    $orderId = $db->lastInsertId();

    // Insert order items
    foreach ($cartItems as $item) {
        if (!isset($item['size_id'])) {
            throw new Exception("Cart item missing size_id.");
        }

        $stmt = $db->prepare("
            INSERT INTO order_items (order_id, product_id, size_id, quantity, price)
            VALUES (?, ?, ?, ?, ?)
        ");
        if (!$stmt->execute([
            $orderId,
            $item['product_id'],
            $item['size_id'],
            $item['quantity'],
            $item['price']
        ])) {
            $errorInfo = $stmt->errorInfo();
            throw new Exception("Order item insert failed: " . $errorInfo[2]);
        }
    }

    // Clear only the selected items from the user's cart
    $stmt = $db->prepare("
        DELETE FROM cart_item 
        WHERE cart_id IN (SELECT id FROM cart WHERE user_id = ?) 
        AND selected = 1
    ");
    if (!$stmt->execute([$userId])) {
        $errorInfo = $stmt->errorInfo();
        throw new Exception("Cart clear failed: " . $errorInfo[2]);
    }

    $db->commit();

    // Redirect to success page
    header("Location: ../index.php?page=order_success");
    exit;
} catch (Exception $e) {
    $db->rollBack();
    header("Location: ../index.php?page=error");
    exit;
}
