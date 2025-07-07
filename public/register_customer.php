<?php

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../includes/functions.php');

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = xss($_POST['customer_name'] ?? '');
    $number = xss($_POST['customer_number'] ?? '');
    $email = xss($_POST['customer_mail'] ?? '');
    $zip = xss($_POST['customer_zipcode'] ?? '');
    $address = xss($_POST['address']);

    if (empty($name) || empty($number) || empty($email)) {
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
            body {font-family: sans-serif; padding: auto;}
            form { max-width: 400px; margin: auto; padding: auto; text-align: left;}
            label {display: block; margin: auto; text-align: left;}
            input {width: 100%; padding: auto;}
            button {padding: 10px 20px;}
            .message {color: red;}
            .success {color: green;}
            .btn {
                display: inline-block;
                padding: 12px 25px;
                margin: 15px;
                background-color: #CC6633;
                color: white;
                border: none;
                border-radius: 6px;
                text-decoration: none;
                font-size: 16px;
            }
            .top-right {
                position: absolute;
                top: 30px;
                right: 35px;
            }
        </style>
    </head>
    <body>

        <div class="top-right">
            <a href="main.php" class="btn">メイン画面へ戻る</a>
        </div>
        <h1><strong>新規顧客登録</strong></h1>

        <?php if ($message): ?>
            <div class="message"><?= xss($message) ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <label>名前: </label>
            <input type="text" name="customer_name" required>

            <label>電話番号: </label>
            <input type="text" name="customer_number" required>

            <label>メールアドレス: </label>
            <input type="email" name="customer_mail" required>

            <label>郵便番号: </label>
            <input type="number" name="customer_zipcode" min="0" max="9999999">

            <label>住所: </label>
            <input type="text" name="address">

            <button type="submit">登録</button>
        </form>
    </body>
</html>