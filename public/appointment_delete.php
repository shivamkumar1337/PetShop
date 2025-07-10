<?php

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/session_check.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_ids'])) {
    $ids = $_POST['appointment_ids'];

    $ids = array_filter($ids, fn($id) => is_numeric($id));

    if(!empty($ids)) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "DELETE FROM appointments WHERE appointment_id IN ($placeholders)";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($ids);
        } catch (PDOException $e) {
            echo "エラー! 削除できませんでした。";
        }
    }
}

header('Location: appointment_list.php');
exit;
