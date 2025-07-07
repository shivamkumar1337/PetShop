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
    <title>ペット選択 - PetShop</title>
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
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>

<div class="container">
    <h2>利用登録：ペットを選択してください</h2>

    <!-- Button 1: Select existing customer -->
    <a href="view_pet.php?customer_id=<?= $customer_id ?>" class="btn">ペットを選択する</a>

    <!-- Button 2: Register new customer -->
    <a href="register_pet.php?customer_id=<?= $customer_id ?>" class="btn">新規ペット登録</a>
</div>

</body>
</html>