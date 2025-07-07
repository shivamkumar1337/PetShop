<?php
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/session_check.php');
require_once(__DIR__ . '/../includes/functions.php');

$customer_id = isset($_GET['customer_id']) ? (int)$_GET['customer_id'] : null;

if (!$customer_id) {
    echo "無効なアクセス:顧客IDが指定されていません。";
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT pets.pet_id, pets.customer_id, customers.customer_name, pets.pet_name, pets.pet_type, pets.pet_size, pets.pet_DOB
        FROM pets
        JOIN customers ON pets.customer_id = customers.customer_id
        WHERE pets.customer_id = ?
        ORDER BY pets.pet_id
    ");
    $stmt->execute([$customer_id]);
    $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ペット一覧</title>
    <style>
        body {padding: 30px; text-align: center; }
        table { width: 90%; margin: auto; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; }
        th { background-color: #CC6633; }
        a.select-btn {
            display: inline-block;
            padding: 6px 12px;
            background-color: #CC6633;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        a.select-btn:hover {
            background-color: #CC6633;
        }
        .message { color: red; font-weight: bold; margin-top: 20px; }
        .btn {
            display: inline-block;
                padding: 12px 25px;
                margin: 15px;
                background-color: #CC6633;
                color: white;
                border: none;
                border-radius: 6px;
                text-decoration: none;
                font-size: 16px;
        }
        .top-right {
            position: absolute;
            top: 30px;
            right: 35px;
        }
    </style>
</head>
<body>

<div class="top-right">
    <a href="main.php" class="btn">メイン画面へ戻る</a>
</div>

<h1>一覧からペットを選ぶ</h1>
<p>顧客ID: <?= htmlspecialchars($customer_id) ?></p>

<?php if (!empty($pets)): ?>
    <table>
        <tr>
            <th>ペットID</th>
            <th>顧客名</th>
            <th>ペット名</th>
            <th>種類</th>
            <th>サイズ</th>
            <th>生年月日</th>
            <th>選択</th>
        </tr>
        <?php foreach ($pets as $pet): ?>
            <tr>
                <td><?= xss($pet['pet_id']) ?></td>
                <td><?= xss($pet['customer_name']) ?></td>
                <td><?= xss($pet['pet_name']) ?></td>
                <td><?= xss($pet['pet_type']) ?></td>
                <td><?= xss($pet['pet_size']) ?></td>
                <td><?= xss($pet['pet_DOB']) ?></td>
                <td>
                    <a class="select-btn" href="select_service.php?customer_id=<?= $pet['customer_id'] ?>&pet_id=<?= $pet['pet_id'] ?>">選択</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p class="message">登録されたペットが見つかりません。</p>
<?php endif; ?>

</body>
</html>
