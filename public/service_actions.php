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
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div>
    <header>
        <h1>サービス編集</h1>
        <nav>
            <ul>
                <li><a href="main.php">メインへ</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <form action="service_update.php" method="post" class="service_form">
            <!-- IDをhiddenで送信 -->
            <input type="hidden" name="service_id" value="<?= xss($service_id) ?>">

            <!-- サービス名 -->
            <div class="form_la">
                <label>サービス名</label>
                <input type="text" name="service_name" value="<?= xss($service['service_name']) ?>">
            </div>

            <!-- 種類 -->
            <div class="form_la">
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

            <!-- 大きさ -->
            <div class="form_la">
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

            <!-- 料金 -->
            <div class="form_la">
                <label>料金</label>
                <input type="number" name="service_price" step="1" min="0" max="999999" value="<?= xss($service['service_price']) ?>">
            </div>

            <!-- 更新ボタン -->
            <div class="submit_btn">
                <input type="submit" value="更新" class="my_submit_btn">
            </div>
        </form>

        <div class="link">
            <a href="service.php">サービス一覧へ戻る</a>
        </div>
    </main>
</div>
</body>
</html>
