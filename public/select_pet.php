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
    <h2>利用登録：ペットを選択してください</h2>

    <a href="view_pet.php?customer_id=<?= $customer_id ?>" class="btn">ペットを選択する</a>

    <a href="register_pet.php?customer_id=<?= $customer_id ?>" class="btn">新規ペット登録</a>
</div>
<a href="select_customer.php"
    style = "display: flex; justify-content:center; align-items:center;text-align: center; padding: 30px;">
    利用登録へ
</a>
</body>
</html>