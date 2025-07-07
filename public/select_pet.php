<?php

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/session_check.php');

$customer_id = $_GET['customer_id'] ?? null;
if (!$customer_id) {
    header("Location: select_customer.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ペット選択 - ペットショップ</title>
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

<div class="top-right">
    <a href="main.php" class="btn">メインへ</a>
</div>

<div class="container">
    <h2>利用登録：ペットを選択してください</h2>

    <a href="view_pet.php?customer_id=<?= $customer_id ?>" class="btn">ペットを選択する</a>

    <a href="register_pet.php?customer_id=<?= $customer_id ?>" class="btn">新規ペット登録</a>
</div>

</body>
</html>