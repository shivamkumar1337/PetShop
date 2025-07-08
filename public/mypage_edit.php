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

// 表示用ユーザー名
$display_username = $form_data['username'] ?? $user['username'];

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../css/sheet_style.css" type="text/css">
        <title>マイページ -ログイン情報編集-</title>
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
        <div>
            <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= xss($error) ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="mypage_update.php" method="post">
        <div class="form_la">
            <label>ユーザー名</label>
            <input type="text" name="username" value="<?= xss($display_username) ?>">
        </div>
        <div class="form_la">
            <label>現在のパスワード</label>
            <input type="password" name="current_password">
        </div>
        <div class="form_la">
            <label>新しいパスワード</label>
            <input type="password" name="new_password">
        </div>
        <div class="form_la">
            <label>新しいパスワード（確認）</label>
            <input type="password" name="confirm_password">
        </div>
        <button type="submit">更新</button>
    </form>
</body>
</html>
