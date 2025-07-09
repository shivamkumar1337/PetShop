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
    <link rel="stylesheet" href=" assets/css/style.css">
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
            <form method="get" action="customer_list.php" class="history_search_wrap">
                <input type="text" name="keyword" placeholder="顧客名を入力" value="<?= xss($keyword) ?>"
                    class="history_search_input">
                <input type="submit" value="🔍"
                    class="history_search_btn">
            </form>

            <form method="post" action="customer_delete.php">
                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="history_delete_btn" onclick="return confirm('選択した顧客を削除してよろしいですか？');">削除</button>
                </div>

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
                        echo "<p style='text-align: center;'>該当する顧客情報はありません。</p>";
                    } else {
                ?>
                        <table class="history_table">
                            <thead >
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
                                        <td><?= xss($customer['customer_name']) ?></td>
                                        <td><?= xss($customer['pet_name'] ?? '―') ?></td>
                                        <td><?= xss($customer['customer_zipcode']) ?> <?= xss($customer['address']) ?></td>
                                        <td><?= xss($customer['customer_number']) ?></td>
                                        <td><?= xss($customer['customer_mail']) ?></td>
                                        <td><a href="customer_edit.php?id=<?= xss($customer['customer_id']) ?>">🖋</a></td>
                                        <td><input type="checkbox" name="customer_delete_ids[]" value="<?= xss($customer['customer_id']) ?>"></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                <?php
                    }
                } catch (PDOException $e) {
                    echo "<p class='error_message'>エラー: " . xss($e->getMessage()) . "</p>";
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