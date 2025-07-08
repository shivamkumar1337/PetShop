<?php
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/session_check.php');

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
                <img src="assets/logo.png" alt="logo" style="height: 40px; margin-right: 10px; cursor: pointer;">
            </a>
        </div>

        <!-- Navigation Links -->
        <div style="display: flex; gap: 10px;">
            <a href="select_customer.php" style="display: inline-block; width: 100px; text-align: center; text-decoration: none; font-weight: bold; color: #000; padding: 10px; border: 1px solid #333;">利用登録</a>
            <a href="service.php" style="display: inline-block; width: 100px; text-align: center; text-decoration: none; font-weight: bold; color: #000; padding: 10px; border: 1px solid #333;">サービス</a>
            <a href="sales.php" style="display: inline-block; width: 100px; text-align: center; text-decoration: none; font-weight: bold; color: #000; padding: 10px; border: 1px solid #333;">売上</a>
            <a href="history.php" style="display: inline-block; width: 100px; text-align: center; text-decoration: none; font-weight: bold; color: #000; padding: 10px; border: 1px solid #333;">履歴</a>
            <a href="list_select.php" style="display: inline-block; width: 100px; text-align: center; text-decoration: none; font-weight: bold; color: #000; padding: 10px; border: 1px solid #333;">表示</a>
            <a href="mypage.php" style="display: inline-block; width: 100px; text-align: center; text-decoration: none; font-weight: bold; color: #000; padding: 10px; border: 1px solid #333;">マイページ</a>
        </div>

        <!-- Logout -->
        <div style="margin-left: 10px;">
            <a href="logout.php">
                <img src="assets/exit.png" alt="logout" style="height: 30px; cursor: pointer;">
            </a>
        </div>
    </nav>
    <div style="display: flex; flex-direction: column; align-items: center; justify-content:space-between;">
        <div style="margin: horizontal 5px; display: flex; flex-direction: row; align-items: flex-start; justify-content:space-between; width:100wh">
            <?php include_once 'today_appointment_table.php'; ?>
            <?php include_once 'month_birthday_table.php'; ?>
        </div>
                <div">
            <?php include_once 'last_month_service_used_table.php'; ?>
        </div>
    </div>
    

    
</body>

</html>