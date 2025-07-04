<?php
require_once(__DIR__ . '/../config/config.php');

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

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
// print_r($appointments);

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

<!DOCTYPE html>
<html>
<head>
    <title>Main Screen - PetShop</title>
</head>
<body style="margin: 0;">

    <nav style="display: flex; align-items: center; justify-content: space-between; padding: 10px 20px; border-bottom: 1px solid #333; background-color: #fff;">

        <!-- Logo -->
        <div style="display: flex; align-items: center;">
            <a href="main.php">
                <img src="/assets/logo.png" alt="logo" style="height: 40px; margin-right: 10px; cursor: pointer;">
            </a>
        </div>

        <!-- Navigation Links -->
        <div style="display: flex; gap: 10px;">
            <a href="select_customer.php" style="display: inline-block; width: 100px; text-align: center; text-decoration: none; font-weight: bold; color: #000; padding: 10px; border: 1px solid #333;">利用登録</a>
            <a href="#" style="display: inline-block; width: 100px; text-align: center; text-decoration: none; font-weight: bold; color: #000; padding: 10px; border: 1px solid #333;">サービス</a>
            <a href="#" style="display: inline-block; width: 100px; text-align: center; text-decoration: none; font-weight: bold; color: #000; padding: 10px; border: 1px solid #333;">売上</a>
            <a href="#" style="display: inline-block; width: 100px; text-align: center; text-decoration: none; font-weight: bold; color: #000; padding: 10px; border: 1px solid #333;">表示</a>
            <a href="#" style="display: inline-block; width: 100px; text-align: center; text-decoration: none; font-weight: bold; color: #000; padding: 10px; border: 1px solid #333;">マイページ</a>
        </div>

        <!-- Logout -->
        <div style="margin-left: 10px;">
            <a href="logout.php">
                <img src="/assets/exit.png" alt="logout" style="height: 30px; cursor: pointer;">
            </a>
        </div>
    </nav>

    <div style="padding: 30px;">
        
    <h2>📅 今日の予定</h2>

    <?php if (count($appointments) > 0): ?>
        <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%;">
            <thead style="background-color: #CC6633; color: white;">
                <tr>
                    <th>ペット名</th>
                    <th>顧客名</th>
                    <th>時間</th>
                    <th>サービス名</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['pet_name']) ?></td>
                        <td><?= htmlspecialchars($row['customer_name']) ?></td>
                        <td><?= date('H:i', strtotime($row['appointment_date'])) ?></td>
                        <td><?= htmlspecialchars($row['service_name']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>今日は予定がありません。</p>
    <?php endif; ?>

    </div>


    <h2>今月の誕生日 🎂</h2>

    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%;">
        <thead style="background-color: #CC6633; color: white;">
            <tr>
                <th>誕生日</th>
                <th>ペット</th>
                <th>顧客名</th>
                <th>郵便番号</th>
                <th>住所</th>
                <th>メールアドレス</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($birthdays) > 0): ?>
                <?php foreach ($birthdays as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars(date('Y-m-d', strtotime($row['pet_DOB']))) ?></td>
                        <td><?= htmlspecialchars($row['pet_name']) ?></td>
                        <td><?= htmlspecialchars($row['customer_name']) ?></td>
                        <td><?= htmlspecialchars($row['customer_zipcode']) ?></td>
                        <td><?= htmlspecialchars($row['address']) ?></td>
                        <td><?= htmlspecialchars($row['customer_mail']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">今月は誕生日のペットがいません。</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
