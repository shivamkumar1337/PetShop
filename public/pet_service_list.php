<?php
require_once '../config/config.php';
require_once __DIR__ . '/../includes/functions.php';  

$services = [];
try {
    $stmt = $pdo->query("SELECT service_id, service_name FROM services ORDER BY service_name");
    $services = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "<p>サービス一覧取得エラー: " . xss($e->getMessage()) . "</p>";
}

$selected_service_id = $_GET['service_id'] ?? '';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>サービス別ペット一覧画面</title>
    <link rel="stylesheet" href="style.css">
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #print-area, #print-area * {
                visibility: visible;
            }
            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
<div>
    <header>
        <h1>サービス別ペット一覧</h1>
        <nav>
            <ul>
                <li><a href="main.php" class="no-print">メインへ</a></li>
            </ul>
        </nav>
    </header>

<main>
    <form method="get" action="" class="no-print" style="margin-bottom: 1em;">
        <label for="service">サービスを選択：</label>
        <select name="service_id" id="service" required>
            <option value="">-- 選択してください --</option>
            <?php foreach ($services as $service): ?>
                <option value="<?= xss($service['service_id']) ?>"
                    <?= $service['service_id'] == $selected_service_id ? 'selected' : '' ?>>
                    <?= xss($service['service_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">表示</button>
    </form>

    <?php if ($selected_service_id !== ''): ?>
        <button onclick="window.print()" class="no-print" style="margin-bottom: 1em;">🖨 印刷</button>

        <div id="print-area">
            <h2>選択中のサービス：
                <?php
                foreach ($services as $s) {
                    if ($s['service_id'] == $selected_service_id) {
                        echo xss($s['service_name']);
                        break;
                    }
                }
                ?>
            </h2>

            <?php
            try {
                $sql = "
                    SELECT DISTINCT
                        p.pet_id,
                        p.pet_name,
                        p.pet_age,
                        p.pet_type,
                        p.pet_weight,
                        p.pet_size,
                        p.pet_DOB,
                        c.customer_name
                    FROM pets p
                    INNER JOIN customers c ON p.customer_id = c.customer_id
                    INNER JOIN service_history sh ON p.pet_id = sh.pet_id
                    WHERE sh.service_id = :service_id
                    ORDER BY p.pet_name
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':service_id' => $selected_service_id]);
                $pets = $stmt->fetchAll();

                if (empty($pets)) {
                    echo "<p>このサービスを利用したペットはいません。</p>";
                } else {
                    echo "<table border='1' style='margin-top:20px; border-collapse: collapse; width: 100%;'>";
                    echo "<thead><tr style='background-color: #eee;'>
                        <th>ペット名</th>
                        <th>年齢</th>
                        <th>種類</th>
                        <th>体重</th>
                        <th>サイズ</th>
                        <th>生年月日</th>
                        <th>飼い主名</th>
                    </tr></thead><tbody>";
                    foreach ($pets as $pet) {
                        echo "<tr>";
                        echo "<td>" . xss($pet['pet_name']) . "</td>";
                        echo "<td>" . xss($pet['pet_age']) . "</td>";
                        echo "<td>" . xss($pet['pet_type']) . "</td>";
                        echo "<td>" . xss($pet['pet_weight']) . "</td>";
                        echo "<td>" . xss($pet['pet_size']) . "</td>";
                        echo "<td>" . xss($pet['pet_DOB']) . "</td>";
                        echo "<td>" . xss($pet['customer_name']) . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                }
            } catch (PDOException $e) {
                echo "<p>データ取得エラー: " . xss($e->getMessage()) . "</p>";
            }
            ?>
        </div>
    <?php endif; ?>
</main>

<div class="link no-print" style="margin-top: 1em;">
    <a href="list_select.php">一覧表示選択画面へ</a>
</div>
</div>
</body>
</html>