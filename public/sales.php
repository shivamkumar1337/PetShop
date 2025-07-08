<<?php
require_once '../includes/db.php';
require_once(__DIR__ . '/session_check.php');
require_once '../config/config.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>売上集計画面</title>
</head>
<body>
    <div>
        <header>
            <h1>売上集計</h1>
            <nav>
                <ul>
                    <li><a href="main.php">メインへ</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <button onclick="location.href='sales_pet.php'">ペット種別</button>
            <button onclick="location.href='sales_service.php'">サービス別</button>

            <?php
            try {
                // 集計クエリ（ペットの種類・大きさ・サービスごとの売上合計）
                $stmt = $pdo->prepare("
                    SELECT 
                        pets.pet_type,
                        pets.pet_size,
                        services.service_name,
                        SUM(services.service_price) AS total_sales
                    FROM service_history
                    JOIN pets ON service_history.pet_id = pets.pet_id
                    JOIN services ON service_history.service_id = services.service_id
                    GROUP BY pets.pet_type, pets.pet_size, services.service_name
                    ORDER BY pets.pet_type, pets.pet_size
                ");
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (empty($result)) {
                    echo "<p>現在登録されている履歴情報はありません。</p>";
                } else {
                    echo "<table border='1'>";
                    echo "<thead>
                            <tr>
                                <th>ペット種類</th>
                                <th>大きさ</th>
                                <th>サービス</th>
                                <th>売上合計 (円)</th>
                            </tr>
                          </thead>";
                    echo "<tbody>";
                    foreach ($result as $row) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['pet_type']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['pet_size']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['service_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['total_sales']) . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                }
            } catch (PDOException $e) {
                echo "エラー: " . htmlspecialchars($e->getMessage());
            }
            ?>
        </main>
    </div>
</body>
</html>
