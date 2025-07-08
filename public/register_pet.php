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
    $wt = xss($_POST['pet_weight'] ?? '');
    $type = xss($_POST['pet_type'] ?? '');
    $size = xss($_POST['pet_size'] ?? '');
    $dob = xss($_POST['pet_DOB'] ?? '');


    if (empty($name) || empty($type) || empty($size)) {
        $message = "名前、 種類、サイズは必須です";
    } elseif (!is_numeric($wt)) {
        $message = "体重は数値で入力してください。";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO pets (customer_id, pet_name, pet_weight, pet_type, pet_size, pet_DOB) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$customer_id, $name, $wt, $type, $size, $dob]);

            $message = 'ペットが正常に追加されました';
            header("Location: view_pet.php?customer_id=" . $customer_id);
            exit;
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
        <title>ペットの登録</title>
        <style>
            html, body {
                margin: 0;
                padding: 0;
                height: 100%;
                background-color: #f5f5f5;
                font-family: Arial, sans-serif;
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
            <h1 style="margin-bottom: 20px;">新規ペット登録</h1>

            <?php if ($message): ?>
                <div class="message" style="color: <?= strpos($message, '正常') !== false ? 'green' : 'red' ?>">
                    <?= xss($message) ?>
                </div>
            <?php endif; ?>

            <form action="" method="post" class="form-box">
                <div class="form-group">
                    <label>名前:</label>
                    <input type="text" name="pet_name" required>
                </div>

                <div class="form-group">
                    <label>体重:</label>
                    <input type="number" name="pet_weight" min="0" step="0.1" max="200" required>
                </div>

                <div class="form-group">
                    <label>種類:</label>
                    <select name="pet_type">
                    <option value="">  </option>
                    <option value="dog">犬</option>
                    <option value="cat">猫</option>
                    <option value="others">その地</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>サイズ:</label>
                    <select name="pet_size" required>
                    <option value="">  </option>
                    <option value="small">小型</option>
                    <option value="medium">中型</option>
                    <option value="large">大型</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>生年月日:</label>
                    <input type="date" name="pet_DOB">
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