<?php
require_once '../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

$keyword = trim($_GET['keyword'] ?? '');
?>

<!DOCTYPE html>
<html lang='ja'>
<head>
    <meta charset='utf-8'>
    <title>顧客一覧画面</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div>
    <header>
        <h1>顧客一覧</h1>
        <nav>
            <ul>
                <li><a href="main.php">メインへ</a></li>
            </ul>
        </nav>
    </header>

<main>
    <form method="get" action="customer_list.php">
        <input type="text" name="keyword" placeholder="顧客名を入力" value="<?= str2html($keyword) ?>">
        <input type="submit" value="🔍 検索">
    </form>
</main>

<main>
    <form method="post" action="customer_delete.php">
        <button type="submit" onclick="return confirm('選択した顧客を削除してよろしいですか？');">削除</button>

        <?php
        try {
            $sql = "SELECT customers.customer_id, customers.customer_name, customers.customer_zipcode,
                        customers.customer_mail, customers.customer_number, customers.address,
                        pets.pet_name
                    FROM customers
                    LEFT JOIN pets ON pets.customer_id = customers.customer_id";

            $params = [];

            if ($keyword !== '') {
                $sql .= " WHERE customers.customer_name LIKE :kw";
                $params[':kw'] = '%' . $keyword . '%';
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $customers_table = $stmt->fetchAll();

            if (empty($customers_table)) {
                echo "<p>該当する顧客情報はありません。</p>";
            } else {
        ?>
        <table border="1">
            <thead>
                <tr>
                    <th>顧客名</th>
                    <th>ペット名</th>
                    <th>住所</th>
                    <th>電話番号</th>
                    <th>メールアドレス</th>
                    <th>編集</th>
                    <th>削除</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers_table as $customer): ?>
                    <tr>
                        <td><?= str2html($customer['customer_name']) ?></td>
                        <td><?= str2html($customer['pet_name'] ?? '―') ?></td>
                        <td><?= str2html($customer['customer_zipcode']) ?><?= str2html($customer['address']) ?></td>
                        <td><?= str2html($customer['customer_number']) ?></td>
                        <td><?= str2html($customer['customer_mail']) ?></td>
                        <td><a href="customer_edit.php?id=<?= str2html($customer['customer_id']) ?>">🖋</a></td>
                        <td><input type="checkbox" name="customer_delete_ids[]" value="<?= str2html($customer['customer_id']) ?>"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
            }
        } catch (PDOException $e) {
            echo "<p>エラー: " . str2html($e->getMessage()) . "</p>";
        }
        ?>
    </form>

    <div class="link">
        <a href="list_select.php">一覧表示選択画面へ</a>
    </div>
</main>
</body>
</html>