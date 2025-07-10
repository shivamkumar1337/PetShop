<?php

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/session_check.php');

$message = '';
$customer_id = $_GET['customer_id'] ?? null;
$pet_id = $_GET['pet_id'] ?? null;

if (!$customer_id && !$pet_id) {
    die("Invalid access");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = $_POST['service_id'] ?? null;
    $appointment_date = $_POST['appointment_date'] ?? null;

    if (!$service_id || !$appointment_date) {
        $message = "サービスと日付を選択してください";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT service_id FROM services WHERE service_id = ?");
            $stmt->execute([$service_id]);
            if ($stmt->rowCount() === 0) {
                throw new Exception("そのようなサービスはありません");
            }

            $stmt = $pdo->prepare("INSERT INTO appointments (customer_id, pet_id, service_id, appointment_date) VALUES (?, ?, ?, ?)");
            $stmt->execute([$customer_id, $pet_id, $service_id, $appointment_date]);
            $message = "予約が完了しました";
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), "integrity constraint error")) {
                $message = "予約を登録できませんでした。service_id が無効です";
            } else {
                $message = "登録中にエラーが発生しました";
            }
        } catch (Exception $e) {
            $message = "データーベスエラー!";
        }
    }
}

try {
    $stmt = $pdo->prepare("SELECT pet_type, pet_size FROM pets WHERE pet_id = ?");
    $stmt->execute([$pet_id]);
    $pet = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pet) {
        throw new Exception("指定されたペットが見つかりません。");
    }

    $stmt = $pdo->prepare("SELECT * FROM services WHERE pet_type = ? AND pet_size = ? ORDER BY service_name");
    $stmt->execute([$pet['pet_type'], $pet['pet_size']]);
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($services)) {
        $message = "一致するサービスは登録されていません";
    }
} catch (PDOException $e) {
    $message = "サービス一覧を取得できませんでした!";
} catch (Exception $e) {
    $message = "データーベスエラー!";
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>サービス選択</title>
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
</head>
<body>
    <div>
        <header>
            <h1>サービス選択</h1>
            <nav>
                <ul>
                    <li><a href="main.php">メインへ</a></li>
                </ul>
            </nav>
        </header>

        <main class="form_wrap">
            <h2>一覧からサービスを選ぶ</h2>

            <?php if ($message): ?>
                <p style="color: <?= strpos($message, '完了') !== false ? 'green' : 'red' ?>; font-weight: bold;">
                    <?= xss($message) ?>
                </p>
            <?php endif; ?>

            <form method="POST">
                <div class="service_table">
                    <table class="history_table">
                        <thead class="">
                            <tr>
                                <th>サービス名</th>
                                <th>価格</th>
                                <th>ペット種類</th>
                                <th>大きさ</th>
                                <th>選択</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($services as $service): ?>
                                <tr>
                                    <td><?= xss($service['service_name']) ?></td>
                                    <td>¥<?= number_format($service['service_price']) ?></td>
                                    <td><?= xss($service['pet_type']) ?></td>
                                    <td><?= xss($service['pet_size']) ?></td>
                                    <td><input type="radio" name="service_id" value="<?= xss($service['service_id']) ?>" required></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="form_wrap" style="margin-top: 30px;">
                    <label style="font-weight: bold;">予約日時を選ぶ:</label><br>
                    <input type="datetime-local" name="appointment_date"
                           style="margin-top: 8px; padding: 10px; border: 1px solid #ccc; border-radius: 4px; width: 250px;" required>
                </div>

                <div class="submit_btn">
                    <input type="submit" value="登録" class="my_submit_btn">
                </div>
            </form>

            <div class="link">
                <a href="select_customer.php">利用登録へ</a>
            </div>
        </main>
    </div>
</body>
</html>
