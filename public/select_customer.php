<?php
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/session_check.php');
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>顧客選択 - ペットショップ</title>
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
</head>
<body>
    <div>
        <header>
            <h1>顧客選択</h1>
            <nav>
                <ul>
                    <li><a href="main.php">メインへ</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <div class="form_wrap">
                <h2>利用登録：顧客を選択してください</h2>

                <div class="my_btn">
                    <a href="view_customer.php">
                        <button class="mypage_btn">顧客を選択する</button>
                    </a>
                </div>

                <div class="my_btn">
                    <a href="register_customer.php">
                        <button class="mypage_btn">新規顧客登録</button>
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
