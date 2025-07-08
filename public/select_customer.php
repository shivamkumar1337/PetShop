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

<div style="display: flex; justify-content: flex-end; align-items: flex-end; margin-bottom: 20px;">
    <a href="main.php"
        style="display: inline-block; width: 150px; text-align: center; text-decoration: none; font-weight: bold;
        color: #000; padding: 10px; border: 1px solid #333; background-color: white;">
        メインへ
    </a>
</div>

<div class="container">
    <h2>利用登録：顧客を選択してください</h2>

    <a href="view_customer.php" class="btn">顧客を選択する</a>

    <a href="register_customer.php" class="btn">新規顧客登録</a>
</div>

</body>
</html>