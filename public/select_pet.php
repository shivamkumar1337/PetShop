<?php
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/session_check.php');

$customer_id = $_GET['customer_id'] ?? null;
if (!$customer_id) {
    header("Location: select_customer.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ペット選択 - ペットショップ</title>
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
</head>
<body>
    <div>
        <header>
            <h1>ペット選択</h1>
            <nav>
                <ul>
                    <li><a href="main.php">メインへ</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <div class="form_wrap">
                <h2>利用登録：ペットを選択してください</h2>

                <div class="my_btn">
                    <a href="view_pet.php?customer_id=<?= $customer_id ?>">
                        <button class="mypage_btn">ペットを選択する</button>
                    </a>
                </div>

                <div class="my_btn">
                    <a href="register_pet.php?customer_id=<?= $customer_id ?>">
                        <button class="mypage_btn">新規ペット登録</button>
                    </a>
                </div>

                <div class="link">
                    <a href="select_customer.php">利用登録へ</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
