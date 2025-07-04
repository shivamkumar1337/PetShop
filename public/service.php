<?php
require_once '../includes/db.php';
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <div>
            <header>
                <h1>サービス</h1>
                <nav>
                    <ul>
                        <li><a href="main.php">メインへ</a></li>
                    </ul>
                </nav>
            </header>
            <main>
                <button onclick="location.href='service_add.php'">サービス登録</button>
                <button>削除</button>
                <?php
                require_once '../config/config.php';
                // ユーザーテーブルからデータを取得
                try {
                    // プリペアドステートメントの作成
                    $stmt = $pdo->prepare("SELECT service_name, pet_type, pet_size, service_price FROM services");
                    
                    // パラメータのバインド
                    //$stmt->bindParam(':status', $status, PDO::PARAM_STR);
                    
                    // クエリの実行
                    $stmt->execute();
                    
                    // 結果の取得
                    $services_table = $stmt->fetchAll();
                    
                    // 結果がない場合の処理
                    if (empty($services_table)) {
                        echo "<p>現在登録されているサービスはありません。</p>";
                    } else {
                        // HTMLテーブルとして表示
                ?>
                        <table class="services-table">
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
                                <?php foreach ($services_table as $services): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($services['service_name']); ?></td>
                                    <td><?php echo htmlspecialchars($services['pet_type']); ?></td>
                                    <td><?php echo htmlspecialchars($services['pet_size']); ?></td>
                                    <td><?php echo htmlspecialchars($services['service_price']); ?></td>
                                    <td><a href="service_edit.php?id=<?php echo urlencode($services['id']); ?>">✐</a></td>
                                    <td><input type="checkbox"></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                <?php
                    }
                } catch (PDOException $e) {
                    echo "エラー: " . $e->getMessage();
                }
                ?>
                
            </main>
        </body>
    </div>
</html>