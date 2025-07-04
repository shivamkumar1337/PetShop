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
        $message = "No registered customer";
    }
} catch (PDOException $e) {
    $message = "Failed to get customer list" . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>顧客様一覧</title>
        <style>
            body {font-family: sans-serif; padding: auto;}
            table {width: 80%; margin: auto; border-collapse: collapse;}
            th, td {padding: auto; border: 1px solid red;}
            th {background-color: rgb(211, 211, 211);}
            a.select-btn {
                display: inline-block;
                padding: auto;
                background-color: rgb(101, 101, 101);
                color: #fff;
                text-decoration: none;
                border-radius: 5px;
            }
            a.select-btn:hover {
                background-color: rgb(50, 50, 50);
            }
            .message {color: red;}
        </style>
    </head>
    <body>
        <h1>一覧から顧客を選ぶ</h1>
        <?php if (count($customers) > 0): ?>
            <table>
                <tr>
                    <th>顧客ID</th>
                    <th>名前</th>
                    <th>電話番号</th>
                </tr>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= xss($customer['customer_id']) ?></td>
                        <td><?= xss($customer['customer_name']) ?></td>
                        <td><?= xss($customer['customer_number']) ?></td>
                        <td>
                            <a class="select-btn" href="select_pet.php?customer_id=<?= $customer['customer_id'] ?>">選択</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>登録された顧客が見つかりません</p>
        <?php endif; ?>
    </body>
</html>