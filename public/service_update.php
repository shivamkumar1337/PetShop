<?php 
session_start();
require_once '../includes/db.php';
require_once '../config/config.php';
require_once '../includes/functions.php';

if (
    empty($_POST['service_id']) || !preg_match('/^\d+$/', $_POST['service_id']) ||
    trim($_POST['service_name'] ?? '') === '' ||
    trim($_POST['pet_type'] ?? '') === '' ||
    trim($_POST['pet_size'] ?? '') === '' ||
    !isset($_POST['service_price']) || !is_numeric($_POST['service_price'])
) {
    $_SESSION['form_error'] = '入力値に不備があります。';
    $_SESSION['form_data'] = $_POST;
    header("Location: service_actions.php?id=" . urlencode($_POST['service_id']));
    exit;
}

$service_id = (int)$_POST['service_id'];
$service_name = trim($_POST['service_name']);
$pet_type = trim($_POST['pet_type']);
$pet_size = trim($_POST['pet_size']);
$service_price = (int)$_POST['service_price'];

try {
    $sql = "UPDATE services
            SET service_name = :service_name,
                pet_type = :pet_type,
                pet_size = :pet_size,
                service_price = :service_price
            WHERE service_id = :service_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':service_id', $service_id, PDO::PARAM_INT);
    $stmt->bindValue(':service_name', $service_name, PDO::PARAM_STR);
    $stmt->bindValue(':pet_type', $pet_type, PDO::PARAM_STR);
    $stmt->bindValue(':pet_size', $pet_size, PDO::PARAM_STR);
    $stmt->bindValue(':service_price', $service_price, PDO::PARAM_INT);

    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        $_SESSION['form_error'] = '更新対象が見つかりませんでした。';
        header("Location: service_actions.php?id=" . urlencode($service_id));
        exit;
    }

    header("Location: service.php?updated=1");
    exit;
} catch (PDOException $e) {
    echo "データベースエラー: " . xss($e->getMessage());
    exit;
}
?>
