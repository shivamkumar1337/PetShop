<?php
session_start();

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// 初期値取得
$form_data = $_SESSION['form_data'] ?? [];
$errors = $_SESSION['form_errors'] ?? [];

// セッションに残さないよう削除（1回限りの表示用）
unset($_SESSION['form_data'], $_SESSION['form_errors']);

$user_id = $_SESSION['user_id'];
$user = getUserById($user_id); // 必要に応じてDBから取得

$display_username = $form_data['username'] ?? $user['username'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>マイページ -ログイン情報編集-</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: sans-serif;
        }

        body {
            font-size: 1.2rem;
        }

        header {
            background-color: #CC6633;
            padding: 30px 100px;
            color: #fff;
        }

        header h1 {
            font-size: 2rem;
        }

        header nav ul {
            text-align: right;
        }

        header nav ul li {
            display: inline-block;
            margin-left: 20px;
        }

        header nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        main {
            padding: 30px 0;
        }

        .mypage_form {
            width: 700px;
            margin: 0 auto;
            border: 1px solid #999;
            padding: 30px;
        }

        .form_my {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            align-items: center;
        }

        .form_my label {
            width: 220px;
            text-align: right;
            padding-right: 20px;
        }

        .form_my input {
            width: 350px;
            padding: 8px;
            font-size: 1rem;
        }

        .my_btn {
            text-align: center;
            margin-top: 30px;
        }

        .my_submit_btn {
            width: 150px;
            height: 50px;
            font-size: 1rem;
            cursor: pointer;
        }

        footer {
            margin-top: 50px;
            text-align: center;
        }

        footer a {
            color: #000;
            text-decoration: underline;
        }

        /* Error messages */
        ul.errors {
            margin: 20px auto;
            width: 600px;
            color: red;
        }

        ul.errors li {
            margin-left: 20px;
            list-style: disc;
        }
    </style>
</head>
<body>
    <header>
        <h1>マイページ -ログイン情報編集-</h1>
        <nav>
            <ul>
                <li><a href="main.php">メインへ</a></li>
            </ul>
        </nav>
    </header>

    <?php if (!empty($errors)): ?>
        <ul class="errors">
            <?php foreach ($errors as $error): ?>
                <li><?= xss($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <main>
        <form class="mypage_form" action="mypage_update.php" method="post">
            <div class="form_my">
                <label>ユーザー名</label>
                <input type="text" name="username" value="<?= xss($display_username) ?>" required>
            </div>
            <div class="form_my">
                <label>現在のパスワード</label>
                <input type="password" name="current_password" required>
            </div>
            <div class="form_my">
                <label>新しいパスワード</label>
                <input type="password" name="new_password" required>
            </div>
            <div class="form_my">
                <label>新しいパスワード(確認)</label>
                <input type="password" name="confirm_password" required>
            </div>
            <div class="my_btn">
                <button class="my_submit_btn" type="submit">更新</button>
            </div>
        </form>
    </main>

    <footer>
        <a href="mypage.php">マイページへ戻る</a>
    </footer>
</body>
</html>
