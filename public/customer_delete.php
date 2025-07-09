<?php
require_once '../config/config.php';
require_once __DIR__ . '/../includes/functions.php'; // xss 関数の読み込み

if (!isset($_POST['customer_delete_ids']) || !is_array($_POST['customer_delete_ids'])) {
    header('Location: customer_list.php');
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("DELETE FROM customers WHERE customer_id = :id");

    foreach ($_POST['customer_delete_ids'] as $id) {
        if (ctype_digit($id)) {
            $stmt->execute([':id' => $id]);
        }
    }

    $pdo->commit();

    header('Location: customer_list.php?deleted=1');
    exit;
} catch (PDOException $e) {
    $pdo->rollBack();
    echo "削除中にエラーが発生しました: " . xss($e->getMessage());
}