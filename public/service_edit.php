<?php
//DB接続
require_once '../includes/db.php';
require_once(__DIR__ . '/session_check.php');
require_once '../config/config.php';
//XSS対策関数
require_once '../includes/functions.php';

// IDがGETで渡されているか確認
if (!isset($_GET['id']) || !preg_match('/^\d+$/', $_GET['id'])) {
    //数字以外が含まれているとき
    echo "不正なIDです。";
    exit;
}

$service_id = (int)$_GET['id'];

// データベースから該当レコードを取得
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
    echo "エラー: " . str2html($e->getMessage());
    exit;
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <div>
            <header>
                <h1>サービス -編集-</h1>
                <nav>
                    <ul>
                        <li><a href="main.php">メインへ</a></li>
                    </ul>
                </nav>
            </header>
            <main>       
                <form action="service_update.php" method="post">
                    <!-- IDをhiddenで送信 -->
                    <input type="hidden" name="service_id" value="<?= str2html($service_id) ?>">

                    <div>
                        <label>サービス名</label>
                        <input type="text" name="service_name" value="<?= str2html($service['service_name']) ?>">
                    </div>
                    <div>
                        <label>種類</label>
                        <select name="pet_type">
                            <?php
                            $types = ['犬', '猫', 'その他'];
                            foreach ($types as $type) {
                                $selected = ($service['pet_type'] === $type) ? 'selected' : '';
                                echo "<option value=\"" . str2html($type) . "\" $selected>" . str2html($type) . "</option>";
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
                                echo "<option value=\"" . str2html($size) . "\" $selected>" . str2html($size) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label>料金</label>
                        <input type="number" name="service_price" step=1 min=0 max=999999 value="<?= str2html($service['service_price']) ?>">
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