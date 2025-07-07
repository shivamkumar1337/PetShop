<?php
require_once '../includes/db.php';
?>
 
<!DOCTYPE html>
<html lang='ja'>
    <head>
        <meta charset='utf-8'>
        <title>ペット一覧</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div>
            <header>
                <h1>ペット一覧</h1>
                <nav>
                    <ul>
                        <li><a href="main.php">メインへ</a></li>
                    </ul>
                </nav>
            </header>
    <main>
        <form method="get" action="list_select.php">
        <input type="text" name="search">
        <input type="submit" value="🔍">
    </main>
    <main>
        <button>削除</button>
        <form method="post" action="delete.php">
                <?php
                require_once '../config/config.php';
                // ユーザーテーブルからデータを取得
                try {
                    // プリペアドステートメントの作成
                    $stmt = $pdo->prepare("SELECT pets.pet_id, customers.customer_name,
                     pets.pet_name, pets.pet_age,
                      pets.pet_weight, pets.pet_type, pets.pet_size, pets.pet_DOB
                      from pets
                      join customers on pets.customer_id = customers.customer_id");
                   
                    // パラメータのバインド
                    //$stmt->bindParam(':status', $status, PDO::PARAM_STR);
                   
                    // クエリの実行
                    $stmt->execute();
                   
                    // 結果の取得
                    $pets_table = $stmt->fetchAll();
                   
                    // 結果がない場合の処理
                    if (empty($pets_table)) {
                        echo "<p>現在登録されているペット情報はありません。</p>";
                    } else {
                        // HTMLテーブルとして表示
                   
                ?>
        <table border="1">
            <thead>
            <tr>
                   <th>ペット名</th>
                    <th>年齢</th>
                    <th>種類</th>
                    <th>体重</th>
                    <th>サイズ</th>
                    <th>生年月日</th>
                    <th>顧客名</th>
                    <th>編集</th>
                    <th>削除</th>
            </tr>  
            </thead>  
            <tbody>
                                <?php foreach ($history_table as $history): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($history['service_date']); ?></td>
                                    <td><?php echo htmlspecialchars($history['customer_name']); ?></td>
                                    <td><?php echo htmlspecialchars($history['pet_name']); ?></td>
                                    <td><?php echo htmlspecialchars($history['service_name']); ?></td>
                                    <td><?php echo htmlspecialchars($history['service_price']); ?></td>
                                    <td><?php echo htmlspecialchars($history['service_date']); ?></td>
                                     <td><a href="edit_pet.php?id=<?= $pet['pet_id'] ?>">🖋</a></td>
                                    <td><a href="delete_pet.php?id=<?= $pet['pet_id'] ?>" onclick="return confirm('削除してよろしいですか？');">ゴミ</a></td>
                                    <td><input type="checkbox" name="delete_ids[]" value="<?= $service_history ?>"></td>
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
   
        <div class="link">
        <a href="list_select.php">一覧表示選択画面へ</a>
            </div>
</html>  
 