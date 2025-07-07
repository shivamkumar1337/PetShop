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
                throw new Exception("そのようなサービスはありません" . $e->getMessage());
            }

            $stmt = $pdo->prepare("INSERT INTO appointments (customer_id, pet_id, service_id, appointment_date) VALUES (?, ?, ?, ?)");
            $stmt->execute([$customer_id, $pet_id, $service_id, $appointment_date]);
            $message = "予約が完了しました";
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), "integrity constraint error")) {
                $message = "予約を登録できませんでした。service_id が無効です";
            } else {
                $message = "登録中にエラーが発生しました" . $e->getMessage();
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
    }
}

try {
    $stmt = $pdo->query("SELECT * FROM services ORDER BY service_id");
    $services=$stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($services)) {
        $message = "登録されたサービスはありません";
    }
} catch (PDOException $e) {
    $message = "サービス一覧を取得できませんでした" . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>サービス選択画面</title>
</head>
<body style="margin: 0; font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 30px;">

    <div style="display: flex; justify-content: flex-end; align-items: flex-end; margin-bottom: 20px;">
        <a href="main.php"
           style="display: inline-block; width: 150px; text-align: center; text-decoration: none; font-weight: bold;
                  color: #000; padding: 10px; border: 1px solid #333; background-color: white;">
           メインへ
        </a>
    </div>

    <h1 style="margin-bottom: 20px;">一覧からサービスを選ぶ</h1>

    <?php if ($message): ?>
        <p style="color: red; font-weight: bold;"><?= xss($message) ?></p>
    <?php endif; ?>

    <form method="POST">
        <div style="overflow-x: auto; background-color: white; border: 1px solid #ccc; margin-bottom: 20px;">
            <table style="border-collapse: collapse; width: 100%;">
                <thead style="background-color: #CC6633; color: white;">
                    <tr>
                        <th style="padding: 10px; border: 1px solid #ccc;">サービスID</th>
                        <th style="padding: 10px; border: 1px solid #ccc;">サービス名</th>
                        <th style="padding: 10px; border: 1px solid #ccc;">価格</th>
                        <th style="padding: 10px; border: 1px solid #ccc;">ペット種類</th>
                        <th style="padding: 10px; border: 1px solid #ccc;">大きさ</th>
                        <th style="padding: 10px; border: 1px solid #ccc;">選択</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                        <tr>
                            <td style="padding: 10px; border: 1px solid #ccc;"><?= xss($service['service_id']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ccc;"><?= xss($service['service_name']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ccc;">¥<?= number_format($service['service_price']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ccc;"><?= xss($service['pet_type']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ccc;"><?= xss($service['pet_size']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ccc; text-align: center;">
                                <input type="radio" name="service_id" value="<?= xss($service['service_id']) ?>" required>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="font-weight: bold;">予約日時を選ぶ:</label><br>
            <input type="datetime-local" name="appointment_date"
                   style="margin-top: 8px; padding: 10px; border: 1px solid #ccc; border-radius: 4px; width: 250px;" required>
        </div>

        <button type="submit"
                style="padding: 10px 20px; background-color: #CC6633; color: white; font-weight: bold;
                       border: none; border-radius: 6px; cursor: pointer;">
            登録
        </button>
    </form>
    <a href="select_customer.php"
            style = "display: flex; justify-content:center; align-items:center;text-align: center; padding: 30px;">
                  利用登録へ
    </a>
</body>
</html>
