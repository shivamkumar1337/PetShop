<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>サービス別ペット一覧画面</title>
    <link rel="stylesheet" href="style.css">
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
    <?php
    require_once '../config/config.php';

    // サービス一覧取得（プルダウン用）
    $services = [];
    try {
        $stmt = $pdo->query("SELECT service_id, service_name FROM services ORDER BY service_name");
        $services = $stmt->fetchAll();
    } catch (PDOException $e) {
        echo "<p>サービス一覧取得エラー: " . htmlspecialchars($e->getMessage()) . "</p>";
    }

    $selected_service_id = $_GET['service_id'] ?? '';
    ?>

    <form method="get" action="">
        <label for="service">サービスを選択：</label>
        <select name="service_id" id="service" required>
            <option value="">-- 選択してください --</option>
            <?php foreach ($services as $service): ?>
                <option value="<?= htmlspecialchars($service['service_id']) ?>"
                    <?= $service['service_id'] == $selected_service_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($service['service_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">表示</button>
    </form>

    <?php if ($selected_service_id !== ''): ?>
        <h2>選択中のサービス：  
            <?php
            foreach ($services as $s) {
                if ($s['service_id'] == $selected_service_id) {
                    echo htmlspecialchars($s['service_name']);
                    break;
                }
            }
            ?>
        </h2>

        <?php
        // 選択されたサービスを利用したペットの一覧を取得
        try {
            $sql = "
                SELECT DISTINCT p.pet_id, p.pet_name, c.customer_name
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
                echo "<table border='1' style='margin-top:20px;'>";
                echo "<thead><tr><th>ペット名</th><th>飼い主名</th></tr></thead><tbody>";
                foreach ($pets as $pet) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($pet['pet_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($pet['customer_name']) . "</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            }
        } catch (PDOException $e) {
            echo "<p>データ取得エラー: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        ?>
    <?php endif; ?>
</main>

<div class="link">
    <a href="list_select.php">一覧表示選択画面へ</a>
</div>
</body>
</html>