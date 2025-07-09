<?php
session_start();
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../includes/functions.php');

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(xss($_POST['username'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $message = "ユーザー名とパスワードを入力してください。";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user'] = $username;
            header("Location: main.php");
            exit;
        } else {
            $message = "ユーザー名またはパスワードが正しくありません。";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン - PetShop</title>
</head>
<body style="display: flex; justify-content: center; align-items: center; height: 100vh;">

<div class="login-container" style="padding: 30px; border: 1px solid #CC6633; border-radius: 10px; width: 500px; text-align: center; display: flex; flex-direction: column; justify-content: center; align-items: center;">

    <div class="header" style="text-align: center; display: flex; flex-direction: row; align-items: flex-start; justify-content: flex-start; margin-bottom: 10px; width: 100%;">
        <img src="assets/logo.png" class="logo" alt="logo" style="height: 60%; width: 60%;" />
    </div>

    <h2>ログイン</h2>

    <?php if (!empty($message)): ?>
        <div style="color: red; text-align: center; margin-bottom: 10px;">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="login.php" style="width: 100%;">
        <input type="text" name="username" placeholder="ユーザー名" required
               style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 6px;" />
        <input type="password" name="password" placeholder="パスワード" required
               style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 6px;" />
        <button type="submit"
                style="width: 100%; padding: 10px; background: #CC6633; color: white; font-weight: bold; border: none; border-radius: 6px; cursor: pointer;">
            ログイン
        </button>
    </form>

    <a href="register_user.php" style="margin-top: 15px;">新規登録はこちら</a>
</div>

</body>
</html>
