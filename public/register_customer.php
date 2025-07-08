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
    } elseif (!preg_match('/^\d{10}$/', $number)) {
        $message = "電話番号は１０桁の数字で入力してください。";
    } elseif (empty($name) || empty($number) || empty($email)) {
        $message = "名前、電話番号、メールアドレスは必須です";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO customers (customer_name, customer_number, customer_mail, customer_zipcode, address) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $number, $email, $zip, $address]);
            $customer_id = $pdo->lastInsertId();
            $message = '顧客が正常に追加されました';
            header("Location: register_pet.php?customer_id=" . $customer_id);
        } catch (PDOException $e) {
            $message = 'エラーが発生しました' . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>顧客の登録</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #f5f5f5;
        }

        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            padding: 30px;
        }

        .form-box {
            max-width: 600px;
            width: 100%;
            background-color: white;
            border: 1px solid #ccc;
            padding: 30px;
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #CC6633;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .top-right {
            position: absolute;
            top: 30px;
            right: 35px;
        }

        .message {
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div style="display: flex; justify-content: flex-end; align-items: flex-end; margin-bottom: 20px;">
    <a href="main.php"
        style="display: inline-block; width: 150px; text-align: center; text-decoration: none; font-weight: bold;
        color: #000; padding: 10px; border: 1px solid #333; background-color: white;">
        メインへ
    </a>
</div>

<div class="container">
    <h1 style="margin-bottom: 20px;">新規顧客登録</h1>

    <?php if ($message): ?>
        <div class="message" style="color: <?= strpos($message, '正常') !== false ? 'green' : 'red' ?>">
            <?= xss($message) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="" class="form-box">
        <div class="form-group">
            <label>名前:</label>
            <input type="text" name="customer_name" required>
        </div>

        <div class="form-group">
            <label>電話番号:</label>
            <input type="number" name="customer_number" required>
        </div>

        <div class="form-group">
            <label>メールアドレス:</label>
            <input type="email" name="customer_mail" required>
        </div>

        <div class="form-group">
            <label>郵便番号:</label>
            <input type="number" name="customer_zipcode">
        </div>

        <div class="form-group">
            <label>住所:</label>
            <input type="text" name="address">
        </div>

        <button type="submit">登録</button>
    </form>
</div>
<a href="select_customer.php"
    style = "display: flex; justify-content:center; align-items:center;text-align: center; padding: 30px;">
    利用登録へ
</a>
</body>
</html>
