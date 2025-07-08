<?php


require_once(__DIR__ . '/session_check.php');
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../includes/functions.php');

if (!isset($_GET['id']) || !preg_match('/^\d+$/', $_GET['id'])) {
    echo "不正なIDです。";
    exit;
}

$service_id = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT service_name, pet_type, pet_size, service_price FROM services WHERE service_id = :id");
    $stmt->bindParam(':id', $service_id, PDO::PARAM_INT);
    $stmt->execute();
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$service) {
        echo "該当するサービスが見つかりません。";
        exit;
    }
} catch (PDOException $e) {
    echo "エラー: " . xss($e->getMessage());
    exit;
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>サービス編集画面</title>
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
            <h1 style="margin-bottom: 20px;">サービス編集</h1>
            <main>       
                <form action="service_update.php" method="post" class="service_form">
                    <input type="hidden" name="service_id" value="<?= xss($service_id) ?>">

                    <div>
                        <label>サービス名</label>
                        <input type="text" name="service_name" value="<?= xss($service['service_name']) ?>">
                    </div>
                    <div>
                        <label>種類</label>
                        <select name="pet_type">
                            <?php
                            $types = ['犬', '猫', 'その他'];
                            foreach ($types as $type) {
                                $selected = ($service['pet_type'] === $type) ? 'selected' : '';
                                echo "<option value=\"" . xss($type) . "\" $selected>" . xss($type) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label>大きさ</label>
                        <select name="pet_size">
                            <?php
                            $sizes = ['小型', '中型', '大型'];
                            foreach ($sizes as $size) {
                                $selected = ($service['pet_size'] === $size) ? 'selected' : '';
                                echo "<option value=\"" . xss($size) . "\" $selected>" . xss($size) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label>料金</label>
                        <input type="number" name="service_price" step=1 min=0 max=999999 value="<?= xss($service['service_price']) ?>">
                    </div>
                    <input type="submit" value="更新">
                </form>                
            </main>
            <footer>
                <nav>
                    <li><a href="service.php">サービス一覧へ</a></li>
                </nav>
            </footer>
        </body>
    </div>
</html>