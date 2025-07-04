<?php
require_once(__DIR__ . '/../config/config.php');

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
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
        <!-- Content goes here -->
    </div>

</body>
</html>
