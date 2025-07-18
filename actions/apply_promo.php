<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = strtoupper(trim($_POST['promo_code'] ?? ''));

    // Define valid promo codes server-side
    $validPromoCodes = [
        'VENUSIA20' => ['discount' => 0.20, 'type' => 'percent'],
        'FREESHIP'  => ['discount' => 10.00, 'type' => 'fixed-shipping'],
        'SUMMER15'  => ['discount' => 0.15, 'type' => 'percent'],
    ];

    if (array_key_exists($code, $validPromoCodes)) {
        $promo = $validPromoCodes[$code];

        $_SESSION['discount'] = [
            'code' => $code,
            'amount' => $promo['discount'],
            'type' => $promo['type']
        ];

        echo json_encode([
            'success' => true,
            'discount' => $promo['discount'],
            'type' => $promo['type']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid promo code'
        ]);
    }
}
?>
