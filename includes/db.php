<?php
$env = require __DIR__ . '/../.env.php';

// try {
//     $db = new PDO(
//         "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=utf8mb4",
//         $env['DB_USER'],
//         $env['DB_PASS']
//     );
//     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     die("DB error: " . $e->getMessage());
// }

// FOR LOCAL TESTING

try {
    $db = new PDO(
        "mysql:host={$env['DB_LOCALHOST']};dbname={$env['DB_LOCALNAME']};charset=utf8mb4",
        $env['DB_LOCALUSER'],
        $env['DB_LOCALPASS']
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB error: " . $e->getMessage());
}

