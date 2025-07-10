<?php 
require_once(__DIR__ . '/session_check.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/../config/config.php');

// ▼ バリデーション用関数：空白（全角・半角）を除去して空か判定
function is_blank($value) {
    return preg_replace('/\s|　/u', '', $value) === '';
}

// ▼ 初期データ取得
$service_id     = $_POST['service_id'] ?? '';
$service_name   = $_POST['service_name'] ?? '';
$pet_type       = $_POST['pet_type'] ?? '';
$pet_size       = $_POST['pet_size'] ?? '';
$service_price  = $_POST['service_price'] ?? '';

$errors = [];

// ▼ IDチェック
if (!preg_match('/^\d+$/', $service_id)) {
    $errors[] = '不正なIDです。';
}

// ▼ サービス名
if (is_blank($service_name)) {
    $errors[] = 'サービス名は空白のみでは登録できません。';
}

// ▼ 種類（選択肢チェック）
$valid_types = ['犬', '猫', 'その他'];
if (!in_array($pet_type, $valid_types, true)) {
    $errors[] = '種類の値が不正です。';
}

// ▼ 大きさ（選択肢チェック）
$valid_sizes = ['小型', '中型', '大型'];
if (!in_array($pet_size, $valid_sizes, true)) {
    $errors[] = '大きさの値が不正です。';
}

// ▼ 料金（空白・数値・0以上）
if (is_blank($service_price)) {
    $errors[] = '料金は必須です。';
} elseif (!ctype_digit($service_price) || (int)$service_price < 0) {
    $errors[] = '料金は0以上の整数で入力してください。';
}

// ▼ エラーがある場合は元のフォームに戻す
if (!empty($errors)) {
    $_SESSION['form_error'] = implode('<br>', $errors);
    $_SESSION['form_data'] = $_POST;
    header("Location: service_actions.php?id=" . urlencode($service_id));
    exit;
}

// ▼ 整形
$service_id     = (int)$service_id;
$service_name   = trim($service_name);
$pet_type       = trim($pet_type);
$pet_size       = trim($pet_size);
$service_price  = (int)$service_price;

// ▼ 更新処理
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

    header("Location: service.php?updated=1");
    exit;
} catch (PDOException $e) {
    echo "データベースエラー!";
    exit;
}
?>
