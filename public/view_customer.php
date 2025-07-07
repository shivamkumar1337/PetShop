<?php 

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/session_check.php');
require_once(__DIR__ . '/../includes/functions.php');

$message = '';

try {
    $stmt = $pdo->query("SELECT customer_id, customer_name, customer_number FROM customers ORDER BY customer_id");
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($customers)) {
        header("Refresh: 3; url=select_customer.php");
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
    <title>顧客様一覧</title>
</head>
<body style="margin: 0; font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 30px;">

    <div style="display: flex; justify-content: flex-end; align-items: flex-end; margin-bottom: 20px;">
        <a href="main.php"
           style="display: inline-block; width: 150px; text-align: center; text-decoration: none; font-weight: bold;
                  color: #000; padding: 10px; border: 1px solid #333; background-color: white;">
           メイン画面へ戻る
        </a>
    </div>

    <h1 style="margin-bottom: 20px;">一覧から顧客を選ぶ</h1>

    <?php if (!empty($message)): ?>
        <p style="color: red; font-weight: bold;"><?= xss($message) ?></p>
    <?php endif; ?>

    <?php if (!empty($customers)): ?>
        <div style="overflow-x: auto; background-color: white; border: 1px solid #ccc;">
            <table style="border-collapse: collapse; width: 100%;">
                <thead style="background-color: #CC6633; color: white;">
                    <tr>
                        <th style="padding: 10px; border: 1px solid #ccc;">顧客ID</th>
                        <th style="padding: 10px; border: 1px solid #ccc;">名前</th>
                        <th style="padding: 10px; border: 1px solid #ccc;">電話番号</th>
                        <th style="padding: 10px; border: 1px solid #ccc;">選択</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td style="padding: 10px; border: 1px solid #ccc;"><?= xss($customer['customer_id']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ccc;"><?= xss($customer['customer_name']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ccc;"><?= xss($customer['customer_number']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ccc;">
                                <a href="select_pet.php?customer_id=<?= $customer['customer_id'] ?>"
                                   style="display: inline-block; padding: 8px 16px; background-color: #CC6633; color: white;
                                          text-decoration: none; border-radius: 6px; font-weight: bold;">
                                   選択
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p style="padding: 10px; background-color: #fff; border: 1px solid #ccc;">登録された顧客が見つかりません。</p>
    <?php endif; ?>

</body>
</html>
