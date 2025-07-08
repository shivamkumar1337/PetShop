<?php
<<<<<<< HEAD
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/config.php';
=======
require_once '../includes/db.php';
require_once '../config/config.php';
require_once(__DIR__ . '/session_check.php');
>>>>>>> 3efbac614213c85363b1b84c0ce4e71283a1fc91

if (!isset($_POST['service_delete_ids']) || !is_array($_POST['service_delete_ids'])) {
    header('Location: service.php');
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("DELETE FROM services WHERE service_id = :id");

    foreach ($_POST['service_delete_ids'] as $id) {
        if (preg_match('/^\d+$/', $id)) { // 数字だけを許容
            $stmt->execute([':id' => $id]);
        }
    }

    $pdo->commit();

    header('Location: service.php?deleted=1');
    exit;
} catch (PDOException $e) {
    $pdo->rollBack();
    echo "削除中にエラーが発生しました: " . $e->getMessage();
}
