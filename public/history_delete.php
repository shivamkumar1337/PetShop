<?php
require_once '../config/config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_POST['history_delete_ids']) || !is_array($_POST['history_delete_ids'])) {
    header('Location: history.php');
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("DELETE FROM service_history WHERE history_id = :id");

    foreach ($_POST['history_delete_ids'] as $id) {
        if (ctype_digit($id)) { // ensure id is a numeric string
            $stmt->execute([':id' => $id]);
        }
    }

    $pdo->commit();

    header('Location: history.php?deleted=1');
    exit;
} catch (PDOException $e) {
    $pdo->rollBack();
    echo "削除中にエラーが発生しました: " . htmlspecialchars($e->getMessage());
}