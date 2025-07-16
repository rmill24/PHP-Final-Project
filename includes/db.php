<?php
$config = require __DIR__ . '/../localconfig.php';

$mysqli = new mysqli(
    $config['host'],
    $config['username'],
    $config['password'],
    $config['dbname']
);

if ($mysqli->connect_error) {
    die('Database connection failed: ' . $mysqli->connect_error);
}
