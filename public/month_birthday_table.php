<?php
require_once(__DIR__ . '/../config/config.php');

$stmt = $pdo->prepare("
    SELECT 
        p.pet_DOB,
        p.pet_name,
        c.customer_name,
        c.customer_zipcode,
        c.address,
        c.customer_mail
    FROM 
        pets p
    JOIN customers c ON p.customer_id = c.customer_id
    WHERE 
        MONTH(p.pet_DOB) = MONTH(CURDATE())
    ORDER BY DAY(p.pet_DOB)
");
$stmt->execute();
$birthdays = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="padding-left:30px">
    <h3 style="text-align: flex-start; margin-bottom: 5px;">今月の誕生日</h3>

    <div style="max-height: 300px; min-height: 300px; overflow-y: auto; border: 1px solid #ddd; border-radius: 6px; width: 100%;">
        <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%; min-width: 100%; max-width: 100%;">
                 <thead style="background-color: #CC6633; color: white; position: sticky; top: 0;">
                <tr>
                <th style="text-align: center;">誕生日</th>
                <th style="text-align: center;">ペット</th>
                <th style="text-align: center;">顧客名</th>
                <th style="text-align: center;">郵便番号</th>
                <th style="text-align: center;">住所</th>
                <th style="text-align: center;">メールアドレス</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($birthdays) > 0): ?>
                <?php foreach ($birthdays as $row): ?>
                    <tr>
                        <td style="text-align: center;"><?= htmlspecialchars(date('Y-m-d', strtotime($row['pet_DOB']))) ?></td>
                        <td style="text-align: center;"><?= htmlspecialchars($row['pet_name']) ?></td>
                        <td style="text-align: center;"><?= htmlspecialchars($row['customer_name']) ?></td>
                        <td style="text-align: center;"><?= htmlspecialchars($row['customer_zipcode']) ?></td>
                        <td style="text-align: center;"><?= htmlspecialchars($row['address']) ?></td>
                        <td style="text-align: center;"><?= htmlspecialchars($row['customer_mail']) ?></td>
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