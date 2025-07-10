<?php

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/session_check.php');

$message = '';

try {
    $pdo->beginTransaction();

    $insert_sql = "
        INSERT INTO service_history (
            customer_id, service_id, pet_id, service_date,
            customer_name, pet_name, service_name, service_price, pet_type, pet_size
        )
        SELECT 
            a.customer_id,
            a.service_id,
            a.pet_id,
            a.appointment_date,
            c.customer_name,
            p.pet_name,
            s.service_name,
            s.service_price,
            p.pet_type,
            p.pet_size
        FROM appointments a
        JOIN customers c ON a.customer_id = c.customer_id
        JOIN pets p ON a.pet_id = p.pet_id
        JOIN services s ON a.service_id = s.service_id
        WHERE a.appointment_date <= NOW()
        AND NOT EXISTS (
            SELECT 1
            FROM service_history sh
            WHERE 
                sh.customer_id = a.customer_id
                AND sh.service_id = a.service_id
                AND sh.pet_id = a.pet_id
                AND sh.service_date = a.appointment_date
        )
    ";
    $insert_stmt = $pdo->prepare($insert_sql);
    $insert_stmt->execute();
    $inserted = $insert_stmt->rowCount();

    $delete_sql = "
        DELETE FROM appointments
        WHERE appointment_date <= NOW()
        AND EXISTS (
            SELECT 1
            FROM service_history sh
            WHERE 
                sh.customer_id = appointments.customer_id
                AND sh.service_id = appointments.service_id
                AND sh.pet_id = appointments.pet_id
                AND sh.service_date = appointments.appointment_date
        )
    ";
    $delete_stmt = $pdo->prepare($delete_sql);
    $delete_stmt->execute();
    $deleted = $delete_stmt->rowCount();

    $pdo->commit();

    $message = "サービス履歴へ {$inserted} 件追加、{$deleted} 件の予約を削除しました。";
} catch (PDOException $e) {
    $pdo->rollBack();
    $message = "処理中にエラーが発生しました!";
}
