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
    $message = "予約一覧を取得できませんでした: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>予約一覧</title>
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
</head>
<body>
    <div>
        <header>
            <h1>予約一覧</h1>
            <nav>
                <ul>
                    <li><a href="main.php">メインへ</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <?php if (!empty($message)): ?>
                <p class="error_message"><?= xss($message) ?></p>
            <?php endif; ?>

            <?php if (!empty($appointments)): ?>
                <form method="post" action="appointment_delete.php">
                    <div class="delete_btn_wrap">
                        <button type="button" class="service_delete_btn" onclick=confirmdelete()>削除</button>
                    </div>

                    <table class="history_table">
                        <thead class="table_header">
                            <tr>
                                <th>予約日時</th>
                                <th>顧客名</th>
                                <th>ペット名</th>
                                <th>ペット種類</th>
                                <th>サービス名</th>
                                <th>削除</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($appointments as $appointment): ?>
                                <tr>
                                    <td><?= xss($appointment['appointment_date']) ?></td>
                                    <td><?= xss($appointment['customer_name']) ?></td>
                                    <td><?= xss($appointment['pet_name']) ?></td>
                                    <td><?= xss($appointment['pet_type']) ?></td>
                                    <td><?= xss($appointment['service_name']) ?></td>
                                    <td>
                                        <input type="checkbox" name="appointment_ids[]" value="<?= xss($appointment['appointment_id']) ?>">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </form>
            <?php else: ?>
                <p>登録された予約が見つかりません。</p>
            <?php endif; ?>
        </main>
    </div>
    <div class="link" style="text-align: center; margin-top: 20px;">
        <a href="list_select.php">一覧表示選択画面へ</a>
    </div>
    <script>
        function confirmdelete() {
            const form = document.querySelector('form');
            const checked = form.querySelectorAll('input[name="appointment_ids[]"]:checked');
            if (checked.length === 0) {
                alert("削除する予約を選択してください。");
                return;
            }

            if (confirm("以下のデータを削除しますか？")) {
                form.submit();
            }
        }
    </script>
</body>
</html>
