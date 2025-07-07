<?php
require_once '../includes/db.php';
?>
<!DOCTYPE html>
<html lang='ja'>
    <head>
        <meta charset='utf-8'>
        <title>サービス別面面</title>
        <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
        th {
            /* background-color: #CC6633; */
            /* color: white; */
        }
    </style>
    </head> 
    <body>
        <div>
            <header>
                <h1>サービス別</h1>
                <nav>
                    <ul>
                        <li><a href="main.php">メインへ</a></li>
                    </ul>
                </nav>
            </header>
    <main>
         <?php
                require_once '../config/config.php';
                // ユーザーテーブルからデータを取得
                try {
                    // プリペアドステートメントの作成
                    $stmt = $pdo->prepare("SELECT service_history.history_id,service_history.service_date,
                     customers.customer_name, pets.pet_name, services.service_name, services.service_price,
                      pets.pet_type, pets.pet_size FROM service_history JOIN customers ON
                       service_history.customer_id = customers.customer_id JOIN pets ON
                        service_history.pet_id = pets.pet_id JOIN services ON
                         service_history.service_id = services.service_id ORDER BY service_history.service_id ASC");
                   
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
                <th>サービス</th>
                <th>売上</th>
            </tr>  
            </thead>  
            <tbody>
                                <?php foreach ($history_table as $history): ?>
                                <tr>                            
                                    <td><?php echo htmlspecialchars($history['service_name']); ?></td>
                                    <td><?php echo htmlspecialchars($history['service_price']); ?></td>
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
                    <li><a href="sales.php">売上集計画面へ</a></li>
                        </main> 
       </form>
     </body> 
   <div>   
</html>   