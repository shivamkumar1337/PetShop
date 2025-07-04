<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=pet_service_db;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>