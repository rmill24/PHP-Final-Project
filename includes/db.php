<?php
$config = require __DIR__ . '/../localconfig.php';

try {
    $db = new PDO(
        "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
        $config['username'],
        $config['password']
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB error: " . $e->getMessage());
}

// debugging
// try {
//     $pdo = new PDO("mysql:host=localhost;dbname=venusia_db", "root", "");
//     echo "Connected!";
// } catch (PDOException $e) {
//     echo "Error: " . $e->getMessage();
// }

?>