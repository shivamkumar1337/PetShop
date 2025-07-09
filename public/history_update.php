<?php

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/session_check.php');

$message = '';

$sql = "INSERT INTO service_history (customer_id, service_id, pet_id, service_date)
    SELECT a.customer_id, a.service_id, a.pet_id, a.appointment_date
    FROM appointments a
    WHERE a.appointment_date <= NOW()
    AND NOT EXISTS (
        SELECT 1
        FROM service_history sh
        WHERE 
            sh.customer_id = a.customer_id
            AND sh.service_id = a.service_id
            AND sh.pet_id = a.pet_id
            AND sh.service_date = a.appointment_date
        )";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $message = "History updated successfully. Row affected : " . $stmt->rowCount();
} catch (PDOException $e) {
    $message = "Error during insertion" . $e->getMessage();
}
