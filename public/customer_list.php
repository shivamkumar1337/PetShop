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
        <input type="text" name="keyword" placeholder="顧客名を入力" value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
        <input type="submit" value="🔍 検索">
    </form>
</main>

<main>
    <form method="post" action="customer_delete.php">
        <button type="submit" onclick="return confirm('選択した顧客を削除してよろしいですか？');">削除</button>

        <?php
        require_once '../config/config.php';

        try {
            $sql = "SELECT customers.customer_id, customers.customer_name, customers.customer_zipcode,
                        customers.customer_mail, customers.customer_number, customers.address,
                        pets.pet_name
                    FROM customers
                    LEFT JOIN pets ON pets.customer_id = customers.customer_id";

            $params = [];
            $keyword = trim($_GET['keyword'] ?? '');

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
                        <td><?= htmlspecialchars($customer['customer_name']) ?></td>
                        <td><?= htmlspecialchars($customer['pet_name'] ?? '―') ?></td>
                        <td><?= htmlspecialchars($customer['customer_zipcode']) ?><?= htmlspecialchars($customer['address']) ?></td>
                        <td><?= htmlspecialchars($customer['customer_number']) ?></td>
                        <td><?= htmlspecialchars($customer['customer_mail']) ?></td>
                        <td><a href="customer_edit.php?id=<?= $customer['customer_id'] ?>">🖋</a></td>
                        <td><input type="checkbox" name="customer_delete_ids[]" value="<?= $customer['customer_id'] ?>"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
            }
        } catch (PDOException $e) {
            echo "エラー: " . htmlspecialchars($e->getMessage());
        }
        ?>
    </form>

    <a href="list_select.php"
        style = "display: flex; justify-content:center; align-items:center;text-align: center; padding: 30px;">
        一覧表示選択画面へ
    </a>
</main>
</body>
</html>