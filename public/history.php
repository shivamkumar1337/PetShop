<?php

require_once '../config/config.php';
require_once(__DIR__ . '/session_check.php');
require_once(__DIR__ . '/history_update.php');

$search = $_GET['search'] ?? '';

$sql = "SELECT history_id, service_date,
        customer_name, pet_name, pet_type, pet_size, service_name FROM service_history";

$params = [];
if (!empty($search)) {
    $sql .= " WHERE customer_name LIKE :search 
              OR pet_name LIKE :search 
              OR pet_type LIKE :search 
              OR service_name LIKE :search";
    $params[':search'] = "%$search%";
}
$sql .= " ORDER BY service_date DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$history_table = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset='utf-8'>
    <title>履歴画面</title>
    <link rel="stylesheet" href="assets/css/style.css">
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
    <form method="get" action="history.php" class="history_search_wrap">
        <input type="text" name="search" placeholder="検索" value="<?= htmlspecialchars($search) ?>" class="history_search_input">
        <input type="submit" value="🔍" class="history_search_btn">
    </form>

    <form method="post" action="history_delete.php" onsubmit="return confirm('選択した履歴を削除してよろしいですか？');">
    <div style="display: flex; justify-content: flex-end;">
        <button type="submit"ss class="history_delete_btn">削除</button>
    </div>        
        <table class="history_table">
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
                    <td colspan="7">現在登録されている履歴情報はありません。</td>
                </tr>
            <?php else: ?>
                <?php foreach ($history_table as $history): ?>
                    <tr>
                        <td><?= htmlspecialchars($history['service_date']) ?></td>
                        <td><?= htmlspecialchars($history['customer_name']) ?></td>
                        <td><?= htmlspecialchars($history['pet_name']) ?></td>
                        <td><?= htmlspecialchars($history['pet_type']) ?></td>
                        <td><?= htmlspecialchars($history['pet_size']) ?></td>
                        <td><?= htmlspecialchars($history['service_name']) ?></td>
                        <td><input type="checkbox" name="history_delete_ids[]" value="<?= $history['history_id'] ?>"></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </form>
</main>

</body>
</html>
