<?php
require_once '../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once(__DIR__ . '/session_check.php');

$keyword = trim($_GET['keyword'] ?? '');
$error_message = '';


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['keyword'])) {
    if (strlen($keyword) > 100) {
        $error_message = 'エラー：100文字以内で入力してください。';
    } elseif (preg_match('/^\d+$/', $keyword) && strlen($keyword) > 11) {
        $error_message = 'エラー：数字は11桁以内で入力してください。';
    }
}
?>

<!DOCTYPE html>
<html lang='ja'>

<head>
    <meta charset='utf-8'>
    <title>顧客一覧</title>
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
                <input type="submit" value="🔍" class="history_search_btn">
            </form>

            
            <?php if ($error_message): ?>
                <p style="color: red; font-weight: bold; text-align: center; margin-top: 10px;">
                    <?= xss($error_message) ?>
                </p>
            <?php endif; ?>

            <?php if (!$error_message): ?>
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
                    echo "<p class='error_message'>エラー! </p>";
                }
                ?>
            </form>
            <?php endif; ?>

            <div class="link">
                <a href="list_select.php">一覧表示選択へ</a>
            </div>
        </main>
    </div>
</body>

</html>