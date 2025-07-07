<?php
require_once(__DIR__ . '/../config/config.php');

$stmt = $pdo->prepare("
    SELECT 
        p.pet_name,
        c.customer_name,
        a.appointment_date,
        s.service_name
    FROM 
        appointments a
    JOIN customers c ON a.customer_id = c.customer_id
    JOIN pets p ON a.pet_id = p.pet_id
    JOIN services s ON a.service_id = s.service_id
    WHERE DATE(a.appointment_date) = CURDATE()
    ORDER BY a.appointment_date
");
$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div>
    <h3 style="text-align: flex-start; margin-bottom: 5px;">今日の予定</h3>

    <div style="max-height: 300px; min-height: 300px; overflow-y: auto; border: 1px solid #ddd; border-radius: 6px; width: 100%;">
        <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%; min-width: 100%; max-width: 100%;">
            <thead style="background-color: #CC6633; color: white; position: sticky; top: 0;">
                <tr>
                    <th style="text-align: center;">ペット名</th>
                    <th style="text-align: center;">顧客名</th>
                    <th style="text-align: center;">時間</th>
                    <th style="text-align: center;">サービス名</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($appointments) > 0): ?>
                    <?php foreach ($appointments as $row): ?>
                        <tr>
                            <td style="text-align: center;"><?= htmlspecialchars($row['pet_name']) ?></td>
                            <td style="text-align: center;"><?= htmlspecialchars($row['customer_name']) ?></td>
                            <td style="text-align: center;"><?= date('H:i', strtotime($row['appointment_date'])) ?></td>
                            <td style="text-align: center;"><?= htmlspecialchars($row['service_name']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center; color: gray; padding: 20px;">
                            本日の予定はありません。
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
