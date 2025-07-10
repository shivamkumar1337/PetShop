<?php
require_once '../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

$keyword = trim($_GET['keyword'] ?? '');
?>

<!DOCTYPE html>
<html lang='ja'>

<head>
    <meta charset='utf-8'>
    <title>ペット一覧</title>
    <link rel="stylesheet" href=" assets/css/style.css">
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
            <form method="get" action="pet_list.php" class="history_search_wrap">
                <input type="text" name="keyword" placeholder="ペット名・顧客名・誕生月を入力" value="<?= xss($keyword) ?>"
                    class="history_search_input">
                <input type="submit" value="🔍"
                    class="history_search_btn">
            </form>

            <form method="post" action="pet_delete.php">
                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="history_delete_btn" onclick="return confirm('選択したペットを削除してよろしいですか？');">削除</button>
                </div>

                <?php
                try {
                    $sql = "SELECT pets.pet_id, customers.customer_name, pets.pet_name,
                            pets.pet_weight, pets.pet_type, pets.pet_size, pets.pet_DOB
                        FROM pets
                        JOIN customers ON pets.customer_id = customers.customer_id";

                    $params = [];
                    if ($keyword !== '') {
                        $sql .= " WHERE (pets.pet_name LIKE :kw OR customers.customer_name LIKE :kw OR MONTH(pets.pet_DOB) = :month)";
                        $params[':kw'] = '%' . $keyword . '%';

                if (preg_match('/^\d{1,2}$/', $keyword)) {
                    $params[':month'] = (int)$keyword;
                } else {
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
                        <table class="history_table">
                            <thead>
                                <tr>
                                    <th class="h1">ペット名</th>
                                    <th class="h2">年齢</th>
                                    <th class="h3">種類</th>
                                    <th class="h3">体重</th>
                                    <th class="h3">サイズ</th>
                                    <th class="h4">生年月日</th>
                                    <th class="h2">顧客名</th>
                                    <th class="h5">編集</th>
                                    <th class="h5">削除</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pets_table as $pet): ?>
                                    <tr>
                                        <td class="h1"><?= xss($pet['pet_name']) ?></td>
                                        <td class="h2">
                                            <?php
                                            $dob = new DateTime($pet['pet_DOB']);
                                            $today = new DateTime();
                                            $age = $today->diff($dob)->y;
                                            echo $age;
                                            ?>
                                        </td>
                                        <td class="h3"><?= xss($pet['pet_type']) ?></td>
                                        <td class="h3"><?= xss($pet['pet_weight']) ?></td>
                                        <td class="h3"><?= xss($pet['pet_size']) ?></td>
                                        <td class="h4"><?= xss($pet['pet_DOB']) ?></td>
                                        <td class="h2"><?= xss($pet['customer_name']) ?></td>
                                        <td class="h5"><a href="pet_edit.php?id=<?= xss($pet['pet_id']) ?>">🖋</a></td>
                                        <td class="h5"><input type="checkbox" name="pet_delete_ids[]" value="<?= xss($pet['pet_id']) ?>"></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                <?php
                    }
                } catch (PDOException $e) {
                    echo "<p>エラー: " . xss($e->getMessage()) . "</p>";
                }
                ?>
            </form>

            <div class="link">
                <a href="list_select.php">一覧表示選択画面へ</a>
            </div>
        </main>
    </div>
</body>

</html>