<?php
require_once '../includes/db.php';
require_once '../config/config.php';
 
if (!isset($_POST['history_delete_ids']) || !is_array($_POST['history_delete_ids'])) {
    header('Location: history.php');
    exit;
}
 
try {
    $pdo->beginTransaction();
 
    $stmt = $pdo->prepare("DELETE FROM services_history WHERE history_id = :id");
 
    foreach ($_POST['history_delete_ids'] as $id) {
        if (preg_match('/^\d+$/', $id)) { // 数字だけを許容
            $stmt->execute([':id' => $id]);
        }
    }
 
    $pdo->commit();
 
    header('Location: history.php?deleted=1');
    exit;
} catch (PDOException $e) {
    $pdo->rollBack();
    echo "削除中にエラーが発生しました: " . $e->getMessage();
}