<?php
require_once '../config/config.php';
require_once __DIR__ . '/../includes/functions.php'; 


if (!isset($_POST['pet_delete_ids']) || !is_array($_POST['pet_delete_ids'])) {
    header('Location: history.php');
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("DELETE FROM pets WHERE pet_id = :id");

    foreach ($_POST['pet_delete_ids'] as $id) {
        if (ctype_digit($id)) { 
            $stmt->execute([':id' => $id]);
        }
    }

    $pdo->commit();

    header('Location: pet_list.php?deleted=1');
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    
    echo "削除中にエラーが発生しました: " . str2html($e->getMessage());
}