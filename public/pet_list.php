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
    <form method="get" action="pet_list.php">
        <input type="text" name="keyword" placeholder="ペット名・顧客名・誕生月を入力" value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
        <input type="submit" value="🔍 検索">
    </form>
</main>

<main>
    <form method="post" action="pet_delete.php">
        <button type="submit" onclick="return confirm('選択したペットを削除してよろしいですか？');">削除</button>

        <?php
        require_once '../config/config.php';

        try {
            $sql = "SELECT pets.pet_id, customers.customer_name, pets.pet_name, pets.pet_age,
                        pets.pet_weight, pets.pet_type, pets.pet_size, pets.pet_DOB
                    FROM pets
                    JOIN customers ON pets.customer_id = customers.customer_id";

            $params = [];
            $keyword = trim($_GET['keyword'] ?? '');

            if ($keyword !== '') {
                $sql .= " WHERE (pets.pet_name LIKE :kw OR customers.customer_name LIKE :kw OR MONTH(pets.pet_DOB) = :month)";
                $params[':kw'] = '%' . $keyword . '%';
                
                // 誕生月の抽出が可能か確認（01〜12 で2桁 or 1桁）
                if (preg_match('/^\d{1,2}$/', $keyword)) {
                    $params[':month'] = (int)$keyword;
                } else {
                    // 数値以外なら month にマッチしない値を入れて誤動作防止
                    $params[':month'] = -1;
                }
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $pets_table = $stmt->fetchAll();

            if (empty($pets_table)) {
                echo "<p>該当するペット情報はありません。</p>";
            } else {
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
                        <?php foreach ($pets_table as $pets): ?>
                            <tr>
                                <td><?= htmlspecialchars($pets['pet_name']) ?></td>
                                <td><?= htmlspecialchars($pets['pet_age']) ?></td>
                                <td><?= htmlspecialchars($pets['pet_type']) ?></td>
                                <td><?= htmlspecialchars($pets['pet_weight']) ?></td>
                                <td><?= htmlspecialchars($pets['pet_size']) ?></td>
                                <td><?= htmlspecialchars($pets['pet_DOB']) ?></td>
                                <td><?= htmlspecialchars($pets['customer_name']) ?></td>
                                <td><a href="pet_Edit.php?id=<?= $pets['pet_id'] ?>">🖋</a></td>
                                <td><input type="checkbox" name="pet_delete_ids[]" value="<?= $pets['pet_id'] ?>"></td>
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
    </form>
            <div class="link">
        <a href="list_select.php">一覧表示選択画面へ</a>
            </div>
</main>
</html>
