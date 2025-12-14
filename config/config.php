<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$db   = "nicetees";
$user = "root";   // XAMPP default
$pass = "";       // XAMPP default is blank
$charset = "utf8mb4";

// IMPORTANT: If you install this folder under /nicetees in XAMPP (htdocs/nicetees),
// BASE_URL should be "/nicetees". If you rename the folder, update this accordingly.
define('BASE_URL', '/nicetees');

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
