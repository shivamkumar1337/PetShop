<?php

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/session_check.php');

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>顧客選択 - ペットショップ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding-top: 50px;
            text-align: center;
        }

        .container {
            max-width: 500px;
            margin: auto;
        }

        h2 {
            margin-bottom: 30px;
        }

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

        .btn:hover {
            background-color: #CC6633;
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

<div class="container">
    <h2>利用登録：顧客を選択してください</h2>

    <!-- Button 1: Select existing customer -->
    <a href="view_customer.php" class="btn">顧客を選択する</a>

    <!-- Button 2: Register new customer -->
    <a href="register_customer.php" class="btn">新規顧客登録</a>
</div>

</body>
</html>