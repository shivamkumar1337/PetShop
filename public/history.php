<?php
require_once '../includes/db.php';
?>

<!DOCTYPE html>
<html lang='ja'>
    <head>
        <meta charset='utf-8'>
        <title>履歴画面</title>
        <link rel="stylesheet" href="style.css">
    </head> 
    <body>
        <div>
            <header>
                <h1>履歴一覧</h1>
                <nav>
                    <ul>
                        <li><a href="main.php">メインへ</a></li>
                    </ul>
                </nav>
            </header>
    <main>
        <form method="get" action="history.php">
        <input type="text" name="search" placeholder="検索ワードを入力">
        <input type="submit" value="🔍">
    </main>
    <main>
        <button>削除</button>
        <form method="post" action="history_delete.php">
                <?php
                require_once '../config/config.php';
                // ユーザーテーブルからデータを取得
                try {
                    // プリペアドステートメントの作成
                    $stmt = $pdo->prepare("SELECT service_history.history_id,service_history.service_date,
                     customers.customer_name, pets.pet_name, services.service_name, pets.pet_type,
                      pets.pet_size FROM service_history JOIN customers ON service_history.customer_id =
                       customers.customer_id JOIN pets ON service_history.pet_id =
                        pets.pet_id JOIN services ON service_history.service_id =
                         services.service_id ORDER BY service_history.service_id ASC");
                   
                    // パラメータのバインド
                    //$stmt->bindParam(':status', $status, PDO::PARAM_STR);
                   
                    // クエリの実行
                    $stmt->execute();
                   
                    // 結果の取得
                    $history_table = $stmt->fetchAll();
                   
                    // 結果がない場合の処理
                    if (empty($history_table)) {
                        echo "<p>現在登録されている履歴情報はありません。</p>";
                    } else {
                        // HTMLテーブルとして表示
                    
                ?>
        <table border="1">
            <thead>
            <tr>
                <th>日付</th>
                <th>名前</th>
                <th>ペットの名前</th>
                <th>ペット種類</th>
                <th>大きさ</th>
                <th>サービス</th>
                <th>削除</th>
            </tr>  
            </thead>  
            <tbody>
                                <?php foreach ($history_table as $history): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($history['service_date']); ?></td>
                                    <td><?php echo htmlspecialchars($history['customer_name']); ?></td>
                                    <td><?php echo htmlspecialchars($history['pet_name']); ?></td>
                                    <td><?php echo htmlspecialchars($history['pet_type']); ?></td>
                                    <td><?php echo htmlspecialchars($history['pet_size']); ?></td>
                                    <td><?php echo htmlspecialchars($history['service_name']); ?></td>
                                    <td><input type="checkbox" name="history_delete_ids[]" value="<?= $service_history ?>"></td>
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
       </form>
     </body> 
   <div>   
</html>   