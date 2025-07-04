<?php if (!empty($_GET['deleted'])): ?>
    <p style="color:green;">削除が完了しました。</p>
<?php endif; ?>

<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>UHD商事ペットショップ -- サービス</title>
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
                <!-- サービス登録画面に画面遷移 -->
                <button onclick="location.href='service_add.php'">サービス登録</button>

                <?php
                require_once '../config/config.php';
                // ユーザーテーブルからデータを取得
                try {
                    // プリペアドステートメントの作成
                    $stmt = $pdo->prepare("SELECT service_id, service_name, pet_type, pet_size, service_price FROM services");
                    
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
                    <form method="post" action="service_delete.php" onsubmit="return confirm('選択したサービスを削除してもよろしいですか？');">
                        <button type="submit">削除</button>
                        <table border=1 class="services-table">
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
                                    <td><?php echo str2html($services['service_name']); ?></td>
                                    <td><?php echo str2html($services['pet_type']); ?></td>
                                    <td><?php echo str2html($services['pet_size']); ?></td>
                                    <td><?php echo str2html($services['service_price']); ?></td>
                                    <td><a href="service_actions.php?id=<?php echo urlencode($services['service_id']); ?>">✐</a></td>
                                    <td><input type="checkbox" name="service_delete_ids[]" value="<?php echo $services['service_id']; ?>"></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </form>
                <?php
                    }
                } catch (PDOException $e) {
                    echo "エラー: " . $e->getMessage();
                }
                ?>
                
            </main>
        </div>
    </body>
</html>