<?php
// セッション開始（エラーと入力値を保持するため）
session_start();

// 必要ファイルの読み込み
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/config.php';

// フォームから送られたデータを取得（null合体演算子 ?? で未定義防止）
$service_name  = trim($_POST['service_name'] ?? '');
$pet_type      = trim($_POST['pet_type'] ?? '');
$pet_size      = trim($_POST['pet_size'] ?? '');
$service_price = $_POST['service_price'] ?? '';

// エラー格納用配列
$errors = [];

// ▼ バリデーション（入力チェック）
if ($service_name === '') {
    $errors[] = 'サービス名は必須です。';
}
if (!in_array($pet_type, ['犬', '猫', 'その他'])) {
    $errors[] = '種類の値が不正です。';
}
if (!in_array($pet_size, ['小型', '中型', '大型'])) {
    $errors[] = '大きさの値が不正です。';
}
if (!is_numeric($service_price) || $service_price < 0) {
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
        ':service_name'  => $service_name,
        ':pet_type'      => $pet_type,
        ':pet_size'      => $pet_size,
        ':service_price' => $service_price
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
