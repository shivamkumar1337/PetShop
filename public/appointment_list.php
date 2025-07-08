<?php 

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/session_check.php');
require_once(__DIR__ . '/../includes/functions.php');

$message = '';

try {
    $stmt = $pdo->query("SELECT customers.customer_name, pets.pet_name, pets.pet_type, services.service_name, appointments.appointment_date, appointments.appointment_id
        FROM appointments
        JOIN customers ON appointments.customer_id = customers.customer_id
        JOIN pets ON appointments.pet_id = pets.pet_id
        JOIN services ON appointments.service_id = services.service_id
        ORDER BY appointment_date DESC");
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($appointments)) {
        $message = "予約はなし";
    }
} catch (PDOException $e) {
    $message = "予約一覧を取得できませんでした" . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>予約一覧</title>
</head>
<body style="margin: 0; background-color: #f5f5f5; padding: 30px;">

    <div style="display: flex; justify-content: flex-end; align-items: flex-end; margin-bottom: 20px;">
        <a href="main.php"
           style="display: inline-block; width: 150px; text-align: center; text-decoration: none; font-weight: bold;
                  color: #000; padding: 10px; border: 1px solid #333; background-color: white;">
           メインへ
        </a>
    </div>

    <h1 style="margin-bottom: 20px;">全部の予約</h1>

    <?php if (!empty($message)): ?>
        <p style="color: red; font-weight: bold;"><?= xss($message) ?></p>
    <?php endif; ?>

    <?php if (!empty($appointments)): ?>
        <form method="post" action="appointment_delete.php">
            <div style="overflow-x: auto; background-color: white; border: 1px solid #ccc;">
                <table style="border-collapse: collapse; width: 100%;">
                    <thead style="background-color: #CC6633; color: white;">
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ccc;">予約日時</th>
                            <th style="padding: 10px; border: 1px solid #ccc;">顧客名</th>
                            <th style="padding: 10px; border: 1px solid #ccc;">ペット名</th>
                            <th style="padding: 10px; border: 1px solid #ccc;">ペット種類</th>
                            <th style="padding: 10px; border: 1px solid #ccc;">サービス名</th>
                            <th style="padding: 10px; border: 1px solid #ccc;">削除</th>  
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appointment): ?>
                            <tr style="text-align: center;">
                                <td style="padding: 10x; border: 1px solid #ccc;"><?= xss($appointment['appointment_date']) ?></td>
                                <td style="padding: 10x; border: 1px solid #ccc;"><?= xss($appointment['customer_name']) ?></td>
                                <td style="padding: 10x; border: 1px solid #ccc;"><?= xss($appointment['pet_name']) ?></td>
                                <td style="padding: 10x; border: 1px solid #ccc;"><?= xss($appointment['pet_type']) ?></td>
                                <td style="padding: 10x; border: 1px solid #ccc;"><?= xss($appointment['service_name']) ?></td>
                                <td style="padding: 10x; border: 1px solid #ccc;">
                                    <input type="checkbox" name="appointment_ids[]" value="<?= xss($appointment['appointment_id']) ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div style="margin: 20px; display: flex; justify-content: flex-end; align-items:flex-end;">
                <button type="submit"
                    style="padding: 5px 10px; background-color: #CC6633; color: white; border: none; border-radius: 6px;">
                削除
                </button>
            </div>
        </form>
        <a href="list_select.php"
            style = "display: flex; justify-content:center; align-items:center;text-align: center; padding: 30px;">
                  一覧表示選択画面へ
        </a>
    <?php else: ?>
        <p style="padding: 10px; background-color: #fff; border: 1px solid #ccc;">登録された予約が見つかりません。</p>
    <?php endif; ?>

</body>
</html>
