<?php
// セッション開始（エラーと入力値を保持するため）
session_start();

// 必要ファイルの読み込み
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/../config/config.php');

// フォームから送られたデータを取得（null合体演算子 ?? で未定義防止）
$service_name  = $_POST['service_name'] ?? '';
$pet_type      = $_POST['pet_type'] ?? '';
$pet_size      = $_POST['pet_size'] ?? '';
$service_price = $_POST['service_price'] ?? '';

// 全角・半角スペースを除去して「空かどうか」を判定するための関数
function is_blank($value) {
    // 全角スペース・半角スペース・改行・タブなどを除去
    $no_space = preg_replace('/\s|　/u', '', $value);
    return $no_space === '';
}

// エラー格納用配列
$errors = [];

// ▼ バリデーション（入力チェック）
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

// ▼ エラーがある場合はセッションに保存してフォームに戻す
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old']    = $_POST;
    header('Location: service_add.php');
    exit;
}

// ▼ ここまで来たらDB登録処理に進む
try {
    // SQL文を用意（プレースホルダ使用）
    $stmt = $pdo->prepare("
        INSERT INTO services (service_name, pet_type, pet_size, service_price)
        VALUES (:service_name, :pet_type, :pet_size, :service_price)
    ");

    // プレースホルダに値をバインドして実行
    $stmt->execute([
        ':service_name'  => trim($service_name),
        ':pet_type'      => trim($pet_type),
        ':pet_size'      => trim($pet_size),
        ':service_price' => (int)$service_price
    ]);

    // 成功したら一覧ページへリダイレクト（完了メッセージ付き）
    header('Location: service.php?added=1');
    exit;

} catch (PDOException $e) {
    // もしDBエラーが発生した場合もフォームに戻す
    $_SESSION['errors'] = ['データベースエラー：' . $e->getMessage()];
    $_SESSION['old']    = $_POST;
    header('Location: service_add.php');
    exit;
}
