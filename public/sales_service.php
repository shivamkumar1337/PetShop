<?php
require_once(__DIR__ . '/session_check.php');
require_once '../config/config.php';
require_once(__DIR__ . '/history_update.php');

// 年の初期値（現在の年）
$year = date('Y');

// フォームからの入力を検証（年）
if (isset($_GET['year'])) {
    $input_year = filter_input(INPUT_GET, 'year', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1900, 'max_range' => 2200]
    ]);
    if ($input_year !== false) {
        $year = $input_year;
    }
}

// 月の初期値（現在の月）
$month = date('n');

// フォームからの入力を検証（月）
if (isset($_GET['month'])) {
    $input_month = filter_input(INPUT_GET, 'month', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => 12]
    ]);
    if ($input_month !== false) {
        $month = $input_month;
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>サービス別売上</title>
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
</head>
<body>
<div>
    <header>
        <h1>サービス別売上</h1>
        <nav>
            <ul>
                <li><a href="main.php">メインへ</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div style="display: flex; flex-direction: row; align-items: center; justify-content: center;">
            <form method="get" action="" class="sales_form">
                <label for="year">年を選択：</label>
                <select name="year" id="year">
                    <?php for ($i = 1900; $i <= 2200; $i++): ?>
                        <option value="<?= $i ?>" <?= $i == $year ? 'selected' : '' ?>>
                            <?= $i ?>年
                        </option>
                    <?php endfor; ?>
                </select>
                <label for="month">月を選択：</label>
                <select name="month" id="month">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= $i ?>" <?= $i == $month ? 'selected' : '' ?>>
                            <?= $i ?>月
                        </option>
                    <?php endfor; ?>
                </select>
                <button type="submit" style="padding: 10px;">表示</button>
            </form>

            <button onclick="location.href='sales_pet.php'" class="sales_nav_btn">ペット種別</button>
            <button onclick="location.href='sales_service.php'" class="sales_nav_btn">サービス別</button>
        

        <?php
        try {
            $stmt = $pdo->prepare("
                SELECT 
                        service_name, 
                        sum(service_price) AS total_sales, 
                        pet_type, 
                        pet_size
                    FROM service_history
                    WHERE YEAR(service_date) = :year AND MONTH(service_date) = :month
                    GROUP BY pet_type, pet_size, service_name
                    ORDER BY service_name ASC
            ");
            $stmt->bindValue(':year', $year, PDO::PARAM_INT);
            $stmt->bindValue(':month', $month, PDO::PARAM_INT);
            $stmt->execute();
            $history_table = $stmt->fetchAll();

            if (empty($history_table)) {
                echo "<p>現在登録されている履歴情報はありません。</p>";
            } else {
                $total = 0;
                foreach ($history_table as $row) {
                    $total += $row['total_sales'];
                }

                echo "<p class='sales_summary'>【" . htmlspecialchars($year) . "年" . htmlspecialchars($month) . "月分】売上合計: " . number_format($total) . "円</p>";
        ?>
        </div>
        <table class="history_table">
            <thead class="table_header">
                <tr>
                    <th>サービス</th>
                    <th>売上</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($history_table as $history): ?>
                    <tr>
                        <td><?= htmlspecialchars($history['service_name']) ?></td>
                        <td><?= number_format($history['total_sales']) ?>円</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
            }
        } catch (PDOException $e) {
            echo "<p>エラー!</p>";
        }
        ?>

</main>
<nav class="link">
    <ul>
        <li><a href="sales.php">売上集計へ</a></li>
    </ul>
</nav>
</div>
</body>
</html>
