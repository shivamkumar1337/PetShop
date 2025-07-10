<?php
require_once(__DIR__ . '/session_check.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/../config/config.php');

$service_name  = $_POST['service_name'] ?? '';
$pet_type      = $_POST['pet_type'] ?? '';
$pet_size      = $_POST['pet_size'] ?? '';
$service_price = $_POST['service_price'] ?? '';

function is_blank($value) {
    $no_space = preg_replace('/\s|　/u', '', $value);
    return $no_space === '';
}

$errors = [];

if (is_blank($service_name)) {
    $errors[] = 'サービス名は空白のみでは登録できません。';
}

if (!in_array($pet_type, ['犬', '猫', 'その他'])) {
    $errors[] = '種類の値が不正です。';
} elseif (is_blank($pet_type)) {
    $errors[] = '種類は必須です。';
}

if (!in_array($pet_size, ['小型', '中型', '大型'])) {
    $errors[] = '大きさの値が不正です。';
} elseif (is_blank($pet_size)) {
    $errors[] = '大きさは必須です。';
}

// 数値チェック
if (is_blank($service_price)) {
    $errors[] = '料金は必須です。';
} elseif (!ctype_digit($service_price) || (int)$service_price < 0) {
    $errors[] = '料金は0以上の数字で入力してください。';
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old']    = $_POST;
    header('Location: service_add.php');
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO services (service_name, pet_type, pet_size, service_price)
        VALUES (:service_name, :pet_type, :pet_size, :service_price)
    ");

    $stmt->execute([
        ':service_name'  => trim($service_name),
        ':pet_type'      => trim($pet_type),
        ':pet_size'      => trim($pet_size),
        ':service_price' => (int)$service_price
    ]);

    header('Location: service.php?added=1');
    exit;

} catch (PDOException $e) {
    $_SESSION['errors'] = ['データベースエラー!'];
    $_SESSION['old']    = $_POST;
    header('Location: service_add.php');
    exit;
}
