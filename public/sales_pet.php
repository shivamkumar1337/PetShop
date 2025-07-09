<?php
require_once(__DIR__ . '/session_check.php');
require_once '../config/config.php';
?>
<!DOCTYPE html>
<html lang='ja'>
<head>
    <meta charset='utf-8'>
    <title>ペット種別画面</title>
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
</head>
<body>
<div>
    <header>
        <h1>ペット種別</h1>
        <nav>
            <ul>
                <li><a href="main.php">メインへ</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="sales_nav_buttons">
            <button onclick="location.href='sales_pet.php'" class="sales_nav_btn">ペット種別</button>
            <button onclick="location.href='sales_service.php'" class="sales_nav_btn">サービス別</button>
        </div>
        <?php
        try {
            $stmt = $pdo->prepare("
                SELECT 
                    p.pet_type, 
                    p.pet_size, 
                    SUM(s.service_price) AS total_price
                FROM service_history sh
                JOIN customers c ON sh.customer_id = c.customer_id
                JOIN pets p ON sh.pet_id = p.pet_id
                JOIN services s ON sh.service_id = s.service_id
                WHERE MONTH(sh.service_date) = 7
                GROUP BY p.pet_type, p.pet_size
                ORDER BY sh.service_id ASC
            ");
            $stmt->execute();
            $history_table = $stmt->fetchAll();

            if (empty($history_table)) {
                echo "<p>現在登録されている履歴情報はありません。</p>";
            } else {
                $total = 0;
                foreach ($history_table as $row) {
                    $total += $row['total_price'];
                }

                echo "<p class='pet_sales_summary'>売上合計: " . number_format($total) . "円</p>";
        ?>
        <table class="history_table">
            <thead class="table_header">
                <tr>
                    <th>ペット種類</th>
                    <th>大きさ</th>
                    <th>売上</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($history_table as $history): ?>
                <tr>
                    <td><?= htmlspecialchars($history['pet_type']) ?></td>
                    <td><?= htmlspecialchars($history['pet_size']) ?></td>
                    <td><?= number_format($history['total_price']) ?>円</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
            }
        } catch (PDOException $e) {
            echo "<p>エラー: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        ?>
        <nav class="pet_back_nav">
            <ul>
                <li><a href="sales.php">売上集計画面へ</a></li>
            </ul>
        </nav>
    </main>
</div>
</body>
</html>