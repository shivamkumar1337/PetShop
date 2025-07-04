<?php

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/session_check.php');

$message = '';
$customer_id = $_GET['custmoer_id'] ?? null;
$pet_id = $_GET['pet_id'] ?? null;

if (!$customer_id && !$pet_id) {
    die("Invalid access");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = $_POST['service_id'] ?? null;
    $appointment_date = $_POST['appointment_date'] ?? null;

    if (!$service_id || !$appointment_date) {
        $message = "Select a service and date";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO appointments (customer_id, pet_id, service_id, appointment_date) VALUES (?, ?, ?, ?)");
            $stmt->execute([$customer_id, $pet_id, $service_id, $appointment_date]);
            $message = "予約を登録されました";
        } catch (PDOException $e) {
            $message = "登録できませんでした" . $e->getMessage();
        }
    }
}

try {
    $stmt = $pdo->query("SELECT * FROM services ORDER BY service_id");
    $services=$stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($services)) {
        $message = "No services registered";
    }
} catch (PDOException $e) {
    $message = "Failed to get service list" . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>サービス選択画面</title>
        <style>
            body {font-family: sans-serif; padding: auto;}
            table {width: 80%; margin: auto; border-collapse: collapse;}
            th, td {padding: auto; border: 1px solid red;}
            th {background-color: rgb(211, 211, 211);}
            input[type="radio"] {transform: scale(1.2);}
            .message {color: red;}
            .submit-btn {margin-top: auto; padding: auto; font-size: medium;}
        </style>
    </head>
    <body>
        <h1><strong>一覧からサービスを選ぶ</strong></h1>

        <?php if ($message): ?>
            <div class="message"><?= xss($message) ?></div>
        <?php endif ?>

        <form method="POST">
            <table>
                <tr>
                    <th>サービスID</th>
                    <th>サービス名</th>
                    <th>サービス値段</th>
                    <th>ペット種類</th>
                    <th>ペット大きさ</th>
                </tr>
                <?php foreach ($services as $service): ?>
                    <tr>
                        <td><?= $service['service_id'] ?></td>
                        <td><?= $service['service_name'] ?></td>
                        <td><?= $service['service_price'] ?></td>
                        <td><?= $service['pet_type'] ?></td>
                        <td><?= $service['pet_size'] ?></td>
                        <td><?= $service['service_id'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <label>
                予約日付を選ぶ: 
                <input type="datetime-local" name="appointment_date" required>
            </label>

            <button type="submit" class="submit-btn">登録</button>
        </form>

    </body>
</html>