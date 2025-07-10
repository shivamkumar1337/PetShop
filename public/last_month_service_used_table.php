<?php
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/session_check.php');


$stmt = $pdo->prepare("
    SELECT 
    DATE(sh.service_date) AS last_service_date,
    p.pet_name,
    s.service_name,
    c.customer_name,
    c.customer_zipcode,
    c.address,
    c.customer_mail
FROM service_history sh
JOIN pets p ON sh.pet_id = p.pet_id
JOIN customers c ON sh.customer_id = c.customer_id
JOIN services s ON sh.service_id = s.service_id
WHERE sh.service_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 365 DAY) AND DATE_SUB(CURDATE(), INTERVAL 30 DAY)
ORDER BY sh.service_date ASC;
");
$stmt->execute();
$last_service_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div>
    <h3 style="text-align: flex-start;  margin-bottom: 5px;">前回の利用から1か月経過したペット</h3>
    <div style="max-height: 200px; min-height: 200px; overflow-y: auto; border: 1px solid #ddd; border-radius: 6px; width: 100%;">
        <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%; min-width: 100%; max-width: 100%;">
     
            <thead style="background-color: #CC6633; color: white; position: sticky; top: 0;">
                <tr>
            <th style="text-align: center;">前回ご利用日</th>
            <th style="text-align: center;">ペット</th>
            <th style="text-align: center;">サービス</th>
            <th style="text-align: center;">顧客名</th>
            <th style="text-align: center;">郵便番号</th>
            <th style="text-align: center;">住所</th>
            <th style="text-align: center;">メールアドレス</th>
        </tr>
            </thead>
            <tbody>
                <?php if (count($last_service_users) > 0): ?>
        <?php foreach ($last_service_users as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['last_service_date']) ?></td>
                <td><?= htmlspecialchars($row['pet_name']) ?></td>
                <td><?= htmlspecialchars($row['service_name']) ?></td>
                <td><?= htmlspecialchars($row['customer_name']) ?></td>
                <td><?= htmlspecialchars($row['customer_zipcode']) ?></td>
                <td><?= htmlspecialchars($row['address']) ?></td>
                <td><?= htmlspecialchars($row['customer_mail']) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center; color: gray; padding: 20px;">
                            ご利用から1ヵ月経過したペットはいません。
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
    </table>
</div>
</div>