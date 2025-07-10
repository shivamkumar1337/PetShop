<?php
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/session_check.php');

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = xss($_POST['customer_name'] ?? '');
    $number = xss($_POST['customer_number'] ?? '');
    $email = xss($_POST['customer_mail'] ?? '');
    $zip = xss($_POST['customer_zipcode'] ?? '');
    $address = xss($_POST['address']);

    if (!preg_match('/^\d{7}$/', $zip)) {
        $message = "郵便番号は７桁の数字で入力してください。";
    } elseif (!preg_match('/^\d{10,11}$/', $number)) {
        $message = "電話番号は10桁か11桁の数字で入力してください。";
    } elseif (empty($name) || empty($number) || empty($email)) {
        $message = "名前、電話番号、メールアドレスは必須です";
    } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = '正しいメールアドレス形式で入力してください。';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO customers (customer_name, customer_number, customer_mail, customer_zipcode, address) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $number, $email, $zip, $address]);
            $customer_id = $pdo->lastInsertId();
            $message = '顧客が正常に追加されました';
            header("Location: register_pet.php?customer_id=" . $customer_id);
        } catch (PDOException $e) {
            $message = 'エラーが発生しました';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>顧客登録</title>
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
</head>
<body>
<header>
    <h1>顧客登録</h1>
    <nav>
        <ul>
            <li><a href="main.php">メインへ</a></li>
        </ul>
    </nav>
</header>

<main>
    <?php if ($message): ?>
        <div style="text-align:center; margin-bottom:20px; color:<?= strpos($message, '正常') !== false ? 'green' : 'red' ?>">
            <?= xss($message) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="" class="mypage_form">
        <div class="form_my">
            <label>名前:</label>
            <input type="text" name="customer_name" required value="<?= xss($name ?? '') ?>">
        </div>

        <div class="form_my">
            <label>電話番号:</label>
            <input type="number" name="customer_number" required value="<?= xss($number ?? '') ?>">
        </div>

        <div class="form_my">
            <label>メールアドレス:</label>
            <input type="email" name="customer_mail" required value="<?= xss($email ?? '') ?>">
        </div>

        <div class="form_my">
            <label>郵便番号:</label>
            <input type="number" name="customer_zipcode" value="<?= xss($zip ?? '') ?>">
        </div>

        <div class="form_my">
            <label>住所:</label>
            <input type="text" name="address" value="<?= xss($address ?? '') ?>">
        </div>

        <div class="my_btn">
            <button class="my_submit_btn" type="submit">登録</button>
        </div>
    </form>
</main>

<div class="link">
    <a href="select_customer.php">利用登録へ</a>
</div>
</body>
</html>
