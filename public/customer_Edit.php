<?php
require_once '../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    echo "無効なIDです。";
    exit;
}

$customer_id = (int)$_GET['id'];
$error_message = '';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 入力取得＆トリム
        $name = trim($_POST['customer_name'] ?? '');
        $zipcode = trim($_POST['customer_zipcode'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['customer_number'] ?? '');
        $mail = trim($_POST['customer_mail'] ?? '');

        // バリデーション
        if ($name === '' || $zipcode === '' || $address === '' || $phone === '' || $mail === '') {
            $error_message = 'すべての項目を入力してください。';
        } elseif (mb_strlen($name) > 100) {
            $error_message = '顧客名は100文字以内で入力してください。';
        } elseif (!preg_match('/\S/', $name)) {
            $error_message = '顧客名に空白だけは使用できません。';
        } elseif (preg_match('/^\d+$/', $name)) {
            $error_message = '顧客名に数字のみは使用できません。';
        } elseif (!preg_match('/^\d{7}$/', $zipcode)) {
            $error_message = '郵便番号は7桁の数字で入力してください。';
        } elseif (!preg_match('/^\d{10,11}$/', $phone)) {
            $error_message = '電話番号は10桁または11桁の数字で入力してください（ハイフンなし）。';
        } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $error_message = '正しいメールアドレス形式で入力してください。';
        } else {
            // 更新処理
            $stmt = $pdo->prepare("
                UPDATE customers 
                SET customer_name = :name, customer_zipcode = :zipcode, 
                    address = :address, customer_number = :phone, customer_mail = :mail 
                WHERE customer_id = :id
            ");
            $stmt->execute([
                ':name' => $name,
                ':zipcode' => $zipcode,
                ':address' => $address,
                ':phone' => $phone,
                ':mail' => $mail,
                ':id' => $customer_id
            ]);

            $error_message = "更新が完了しました。";
        }

        // 再取得
        $stmt = $pdo->prepare("SELECT * FROM customers WHERE customer_id = :id");
        $stmt->execute([':id' => $customer_id]);
        $customer = $stmt->fetch();
    } else {
        $stmt = $pdo->prepare("SELECT * FROM customers WHERE customer_id = :id");
        $stmt->execute([':id' => $customer_id]);
        $customer = $stmt->fetch();
    }

    if (!$customer) {
        echo "該当の顧客が見つかりません。";
        exit;
    }

} catch (PDOException $e) {
    $error_message = "エラー: " . xss($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>顧客編集</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>
        <h1>顧客編集</h1>
        <nav>
            <ul>
                <li><a href="main.php">メインへ</a></li>
            </ul>
        </nav>
</header>

<main>
    <?php if ($error_message): ?>
            <div style="text-align:center; margin-bottom:20px; color:<?= strpos($error_message, '正常') !== false ? 'green' : 'red' ?>">
                <?= xss($error_message) ?>
            </div>
        <?php endif; ?>

        <form method="post" class="mypage_form">
            <div class="form_my">
                <label for="customer_name">顧客名:</label>
                <input
                    type="text"
                    name="customer_name"
                    id="customer_name"
                    value="<?= xss($_POST['customer_name'] ?? $customer['customer_name']) ?>"
                    maxlength="100"
                    pattern=".*\S.*"
                    title="空白のみの入力はできません"
                    required
                >
            </div>

            <div class="form_my">
                <label for="customer_zipcode">郵便番号:</label>
                <input
                    type="text"
                    name="customer_zipcode"
                    id="customer_zipcode"
                    value="<?= xss($_POST['customer_zipcode'] ?? $customer['customer_zipcode']) ?>"
                    pattern="^\d{7}$"
                    title="例: 1234567（7桁の数字）"
                    required
                >
            </div>

            <div class="form_my">
                <label for="address">住所:</label>
                <input
                    type="text"
                    name="address"
                    id="address"
                    value="<?= xss($_POST['address'] ?? $customer['address']) ?>"
                    required
                >
            </div>

            <div class="form_my">
                <label for="customer_number">電話番号:</label>
                <input
                    type="text"
                    name="customer_number"
                    id="customer_number"
                    value="<?= xss($_POST['customer_number'] ?? $customer['customer_number']) ?>"
                    pattern="^\d{10,11}$"
                    title="10～11桁の数字のみ（ハイフンなし）"
                    required
                >
            </div>

            <div class="form_my">
                <label for="customer_mail">メールアドレス:</label>
                <input
                    type="email"
                    name="customer_mail"
                    id="customer_mail"
                    value="<?= xss($_POST['customer_mail'] ?? $customer['customer_mail']) ?>"
                    required
                >
            </div>

            <div class="submit_btn">
                <input type="submit" class="my_submit_btn" value="更新">
            </div>
        </form>

        <div class="link">
            <a href="customer_list.php">顧客一覧へ</a>
        </div>
    <!-- </div> -->
</main>
</body>
</html>