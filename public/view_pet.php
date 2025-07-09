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
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header>
    <h1>一覧からペットを選ぶ</h1>
    <nav>
        <ul>
            <li><a href="main.php">メインへ</a></li>
        </ul>
    </nav>
</header>

<main>
    <p>顧客ID: <?= htmlspecialchars($customer_id) ?></p>

    <?php if (!empty($pets)): ?>
        <table class="history_table">
            <thead>
                <tr>
                    <th>ペットID</th>
                    <th>顧客名</th>
                    <th>ペット名</th>
                    <th>種類</th>
                    <th>サイズ</th>
                    <th>生年月日</th>
                    <th>選択</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pets as $pet): ?>
                    <tr>
                        <td><?= xss($pet['pet_id']) ?></td>
                        <td><?= xss($pet['customer_name']) ?></td>
                        <td><?= xss($pet['pet_name']) ?></td>
                        <td><?= xss($pet['pet_type']) ?></td>
                        <td><?= xss($pet['pet_size']) ?></td>
                        <td><?= xss($pet['pet_DOB']) ?></td>
                        <td>
                            <a class="mypage_btn" href="select_service.php?customer_id=<?= $pet['customer_id'] ?>&pet_id=<?= $pet['pet_id'] ?>">選択</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="my_btn">
            <button class="mypage_btn" onclick="location.href='select_customer.php'">利用登録へ</button>
        </div>
    <?php else: ?>
        <p>登録されたペットが見つかりません。</p>
    <?php endif; ?>

</main>

</body>
</html>