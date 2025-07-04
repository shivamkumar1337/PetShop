<?php
require_once '../config/config.php';

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $message = "すべてのフィールドが必須です。";
    } elseif ($password !== $confirm_password) {
        $message = "パスワードが一致しません。";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

        try {
            $stmt->execute([$username, $hashed_password]);
            $success = true;
            $message = "登録が完了しました！";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $message = "ユーザー名は既に存在します。";
            } else {
                $message = "Database error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - PetShop</title>
</head>
<body style="display: flex; justify-content: center; align-items: center; height: 100vh;">

<div class="login-container" style="padding: 30px; border: 1px solid #CC6633; border-radius: 10px; width: 500px; text-align: center; display: flex; flex-direction: column; justify-content: center; align-items: center;">

    <div class="header" style="text-align: center; display: flex; flex-direction: row; align-items: flex-start; justify-content: flex-start; margin-bottom: 10px; width: 100%;">
        <img src="assets/logo.png" class="logo" alt="logo" style="height: 60%; width: 60%;" />
    </div>

    <h2>ユーザー新規登録</h2>

    <?php if (!empty($message)): ?>
        <div style="color: <?= $success ? 'green' : 'red' ?>; text-align: center; margin-bottom: 10px;">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="register_user.php" style="width: 100%;">
        <input type="text" name="username" placeholder="名前" required
            style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 6px;" />
        <input type="password" name="password" placeholder="パスワード" required
            style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 6px;" />
        <input type="password" name="confirm_password" placeholder="確認パスワード" required
            style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 6px;" />
        <button type="submit"
            style="width: 100%; padding: 10px; background: #CC6633; color: white; font-weight: bold; border: none; border-radius: 6px;">
            登録
        </button>
    </form>

    <a href='login.php' style="margin-top: 15px;">ログインへ</a>
</div>

</body>
</html>