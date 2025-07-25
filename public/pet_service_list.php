<?php
require_once '../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once(__DIR__ . '/session_check.php');

$services = [];
try {
    $stmt = $pdo->query("SELECT service_id, service_name, pet_type, pet_size FROM services ORDER BY service_name");
    $services = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "<p>サービス一覧取得エラー!</p>";
}

$selected_service_id = $_GET['service_id'] ?? '';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>サービス別ペット一覧</title>
    <link rel="stylesheet" href=" assets/css/style.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

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
        }
    </style>
</head>
<body>
<div>
    <header>
            <h1>サービス別ペット一覧</h1>
            <nav>
                <ul>
                    <li><a href="main.php">メインへ</a></li>
                </ul>
            </nav>
    </header>

    <main>
        <div style="display: flex; flex-direction: row; justify-content: center; align-items: center;" class="no-print">
            <form method="get" action="" class="history_search_wrap" style="margin-right: 1em;">
                <label for="service">サービスを選択：</label>
                <select name="service_id" id="service" required>
                    <option value="">-- 選択してください --</option>
                    <?php foreach ($services as $service): ?>
                        <option value="<?= xss($service['service_id']) ?>" <?= $service['service_id'] == $selected_service_id ? 'selected' : '' ?>>
                            <?= xss($service['service_name']) ?>（<?= xss($service['pet_type']) ?>・<?= xss($service['pet_size']) ?>）
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="history_search_btn">表示</button>
            </form>

            <?php if ($selected_service_id !== ''): ?>
                <div style="display: flex; justify-content: flex-end;">
                    <button onclick="window.print()" class="history_delete_btn">🖨 印刷</button>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($selected_service_id !== ''): ?>
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
                        echo "<table class='history_table'>";
                        echo "<thead>";
                        echo "<tr>
                            <th class='h1'>ペット名</th>
                            <th class='h2'>年齢</th>
                            <th class='h3'>種類</th>
                            <th class='h3'>体重</th>
                            <th class='h3'>サイズ</th>
                            <th class='h4'>生年月日</th>
                            <th class='h2'>飼い主名</th>
                        </tr></thead><tbody>";

                        foreach ($pets as $pet) {
                            echo "<tr>";
                            echo "<td class='h1'>" . xss($pet['pet_name']) . "</td>";
                            $dob = new DateTime($pet['pet_DOB']);
                            $today = new DateTime();
                            $age = $today->diff($dob)->y;
                            echo "<td class='h2'>" . $age . "</td>";
                            echo "<td class='h3'>" . xss($pet['pet_type']) . "</td>";
                            echo "<td class='h3'>" . xss($pet['pet_weight']) . "</td>";
                            echo "<td class='h3'>" . xss($pet['pet_size']) . "</td>";
                            echo "<td class='h4'>" . xss($pet['pet_DOB']) . "</td>";
                            echo "<td class='h2'>" . xss($pet['customer_name']) . "</td>";
                            echo "</tr>";
                        }

                        echo "</tbody></table>";
                    }
                } catch (PDOException $e) {
                    echo "<p>データ取得エラー!</p>";
                }
                ?>
            </div>
        <?php endif; ?>
    </main>

    <div class="link no-print" style="margin-top: 1em;">
        <a href="list_select.php">一覧表示選択へ</a>
    </div>
</div>
</body>
</html>