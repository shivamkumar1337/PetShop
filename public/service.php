<?php
require_once '../includes/functions.php';
require_once '../config/config.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>UHD商事ペットショップ -- サービス</title>
    <style>
        body {
            margin: 0;
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
            padding: 30px;
        }

        h1 {
            margin-bottom: 20px;
        }

        .top-right {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .top-right a {
            display: inline-block;
            text-decoration: none;
            font-weight: bold;
            color: #000;
            padding: 10px;
            border: 1px solid #333;
            background-color: white;
            width: 150px;
            text-align: center;
        }

        .message {
            color: green;
            margin-bottom: 20px;
        }

        .form_wrap {
            margin-top: 20px;
        }

        .service_table {
            border-collapse: collapse;
            width: 100%;
            background-color: white;
            border: 1px solid #ccc;
        }

        .service_table th,
        .service_table td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        .service_table th {
            background-color: #CC6633;
            color: white;
        }

        .service_add_btn,
        .service_delete_btn {
            background-color: #CC6633;
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .delete_btn_wrap {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="top-right">
        <a href="main.php">メインへ</a>
    </div>

    <h1>サービス</h1>

    <button class="service_add_btn" onclick="location.href='service_add.php'">サービス登録</button>

    <?php
    try {
        $stmt = $pdo->prepare("SELECT service_id, service_name, pet_type, pet_size, service_price FROM services");
        $stmt->execute();
        $services_table = $stmt->fetchAll();

        if (empty($services_table)) {
            echo "<p>現在登録されているサービスはありません。</p>";
        } else {
            if (!empty($_GET['added']) && $_GET['added'] == '1') {
                echo '<p class="message">サービスが登録されました！</p>';
            }
            if (!empty($_GET['deleted'])) {
                echo '<p class="message">削除が完了しました。</p>';
            }
    ?>

    <div class="form_wrap">
        <form class="s_form" method="post" action="service_delete.php" onsubmit="return confirm('選択したサービスを削除してもよろしいですか？');">
            <div class="delete_btn_wrap">
                <button class="service_delete_btn" type="submit">削除</button>
            </div>

            <table class="service_table">
                <thead>
                    <tr>
                        <th>サービス名</th>
                        <th>種類</th>
                        <th>大きさ</th>
                        <th>料金</th>
                        <th>編集</th>
                        <th>削除</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($services_table as $service): ?>
                    <tr>
                        <td><?= xss($service['service_name']); ?></td>
                        <td><?= xss($service['pet_type']); ?></td>
                        <td><?= xss($service['pet_size']); ?></td>
                        <td><?= xss($service['service_price']); ?></td>
                        <td>
                            <a href="service_actions.php?id=<?= urlencode($service['service_id']); ?>">✐</a>
                        </td>
                        <td>
                            <input type="checkbox" name="service_delete_ids[]" value="<?= $service['service_id']; ?>">
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>

    <?php
        }
    } catch (PDOException $e) {
        echo "<p style='color: red;'>エラー: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    ?>

</body>
</html>
