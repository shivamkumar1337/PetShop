<?php 

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/session_check.php');
require_once(__DIR__ . '/../includes/functions.php');

$message = '';

try {
    $stmt = $pdo->query("SELECT customer_id, customer_name, customer_number FROM customers ORDER BY customer_id");
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($customers)) {
        $message = "登録済みの顧客なし";
    }
} catch (PDOException $e) {
    $message = "顧客リストを取得できませんでした" . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>一覧から顧客を選ぶ</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header>
    <h1>一覧から顧客を選ぶ</h1>
    <nav>
        <ul>
            <li><a href="main.php">メインへ</a></li>
        </ul>
    </nav>
</header>

<main>
    <?php if (!empty($message)): ?>
        <p style="color: red; font-weight: bold;"><?= xss($message) ?></p>
    <?php endif; ?>

    <?php if (!empty($customers)): ?>
        <table class="history_table">
            <thead>
                <tr>
                    <th>名前</th>
                    <th>電話番号</th>
                    <th>選択</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= xss($customer['customer_name']) ?></td>
                        <td><?= xss($customer['customer_number']) ?></td>
                        <td>
                            <a href="select_pet.php?customer_id=<?= $customer['customer_id'] ?>" class="mypage_btn">
                               選択
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
    <?php else: ?>
        <p>登録された顧客が見つかりません。</p>
    <?php endif; ?>
    <div class="link">
        <a href="select_customer.php">利用登録へ</a>
    </div>

</main>

</body>
</html>