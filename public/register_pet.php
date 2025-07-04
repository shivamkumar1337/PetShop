<?php

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/session_check.php');

$message = '';
$customer_id = $_GET['customer_id'] ?? null;

if (!$customer_id) {
    header("Location: select_customer.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = xss($_POST['pet_name'] ?? '');
    $age = xss($_POST['pet_age'] ?? '');
    $wt = xss($_POST['pet_weight'] ?? '');
    $type = xss($_POST['pet_type'] ?? '');
    $size = xss($_POST['pet_size'] ?? '');
    $dob = xss($_POST['pet_DOB'] ?? '');


    if (empty($name) || empty($type) || empty($size)) {
        $message = "Name, type, size are required";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO pets (customer_id, pet_name, pet_age, pet_weight, pet_type, pet_size, pet_DOB) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$customer_id, $name, $age, $wt, $type, $size, $dob]);

            $message = 'pet added successfuly';
            header("Location: view_pet.php?customer_id=" . $customer_id);
            exit;
        } catch (PDOException $e) {
            $message = 'error occured' . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ペットの登録</title>
        <style>
            body {font-family: sans-serif; padding: auto;}
            form { max-width: 400px; margin: auto; padding: auto; text-align: left;}
            label {display: block; margin: auto; text-align: left;}
            input {width: 100%; padding: auto;}
            button {padding: 10px 20px;}
            .message {color: red;}
            .success {color: green;}
        </style>
    </head>
    <body>
        <h1><strong>新規ペット登録</strong></h1>

        <?php if ($message): ?>
            <div class="message"><?= xss($message) ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <label>名前: </label>
            <input type="text" name="pet_name" required>

            <label>年齢: </label>
            <input type="text" name="pet_age" required>

            <label>体重: </label>
            <input type="text" name="pet_weight" required>

            <label>サイズ: </label>
            <select name="pet_size" required>
                <option value="">  </option>
                <option value="small">小型</option>
                <option value="medium">中型</option>
                <option value="large">大型</option>
            </select>

            <label>種類: </label>
            <select name="pet_type">
                <option value="">  </option>
                <option value="dog">犬</option>
                <option value="cat">猫</option>
                <option value="others">その地</option>
            </select>

            <label>生年月日: </label>
            <input type="date" name="pet_DOB">

            <button type="submit">登録</button>
        </form>
    </body>
</html>