<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/config.php';
require_once(__DIR__ . '/session_check.php');

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
        <link rel="stylesheet" href="assets/css/style.css" type="text/css">
        <title>ログイン情報編集</title>
    </head>
    <body>
    <header>
        <h1>ログイン情報編集</h1>
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
                <li style="color:red;"><?= xss($error) ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
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
        <nav>
            <li><a href="mypage.php">マイページへ</a></li>
        </nav>
    </footer>
</body>
</html>
