<?php
require_once '../includes/functions.php';
require_once '../config/config.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>UHD商事ペットショップ -- サービス</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header>
    <h1>サービス</h1>
    <nav>
        <ul>
            <li><a href="main.php">メインへ</a></li>
        </ul>
    </nav>
</header>

<main>

    <?php
    try {
        $stmt = $pdo->prepare("SELECT service_id, service_name, pet_type, pet_size, service_price FROM services ORDER BY service_name");
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

    <form method="post" action="service_delete.php" onsubmit="return confirm('選択したサービスを削除してもよろしいですか？');">
    <div style="display: flex; justify-content: space-between;">
        <button class="service_add_btn" type="button" onclick="location.href='service_add.php'">サービス登録</button>    
        <button class="history_delete_btn" type="submit">削除</button>
    </div>
        
        <table class="history_table">
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
                    <td>¥<?= number_format($service['service_price']); ?></td>
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

    <?php
        }
    } catch (PDOException $e) {
        echo "<p style='color: red;'>エラー: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    ?>

</main>

</body>
</html>