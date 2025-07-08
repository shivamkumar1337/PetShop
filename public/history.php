<?php
require_once '../includes/db.php';
require_once '../config/config.php';
require_once(__DIR__ . '/session_check.php');
require_once(__DIR__ . '/history_update.php');

$search = $_GET['search'] ?? '';

$sql = "SELECT service_history.history_id, service_history.service_date,
         customers.customer_name, pets.pet_name, services.service_name,
         pets.pet_type, pets.pet_size
        FROM service_history
        JOIN customers ON service_history.customer_id = customers.customer_id
        JOIN pets ON service_history.pet_id = pets.pet_id
        JOIN services ON service_history.service_id = services.service_id";

$params = [];
if (!empty($search)) {
    $sql .= " WHERE customers.customer_name LIKE :search 
              OR pets.pet_name LIKE :search 
              OR pets.pet_type LIKE :search 
              OR services.service_name LIKE :search";
    $params[':search'] = "%$search%";
}
$sql .= " ORDER BY service_history.service_date DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$history_table = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset='utf-8'>
    <title>履歴画面</title>
</head>
<body>
    <header>
        <h1>履歴一覧</h1>
        <nav>
            <ul>
                <li><a href="main.php">メインへ</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <form method="get" action="history.php">
            <input type="text" name="search" placeholder="検索" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>" maxlength="50" required>
            <input type="submit" value="🔍">
        </form>

        <form method="post" action="history_delete.php" onsubmit="return confirm('選択した履歴を削除してよろしいですか？');">
            <button type="submit">削除</button>
            <table border="1">
                <thead>
                    <tr>
                        <th>日付</th>
                        <th>名前</th>
                        <th>ペットの名前</th>
                        <th>ペット種類</th>
                        <th>大きさ</th>
                        <th>サービス</th>
                        <th>削除</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($history_table)): ?>
                    <tr>
                        <td colspan="7" style="padding: 10px; text-align: center;">現在登録されている履歴情報はありません。</td>
                    </tr>
                <?php else: ?>
                    <form method="post">
                    <?php foreach ($history_table as $history): ?>
                        <tr>
                            <td style="padding: 10px; border: 1px solid #ccc;"><?= htmlspecialchars($history['service_date']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ccc;"><?= htmlspecialchars($history['customer_name']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ccc;"><?= htmlspecialchars($history['pet_name']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ccc;"><?= htmlspecialchars($history['pet_type']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ccc;"><?= htmlspecialchars($history['pet_size']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ccc;"><?= htmlspecialchars($history['service_name']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ccc;">
                                <input type="checkbox" name="history_delete_ids[]" value="<?= $history['history_id'] ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </form>
    </main>
</body>
</html>

