<?php

require(__DIR__ . '/../config/config.php');
require(__DIR__ . '/../includes/functions.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$message = '';

if (isset($_GET['registered'])) {
    $message = "Registration successful";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = xss($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $message = "Both fields required";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                header("Location: main.php");
                exit;
            } else {
                $message = "Invalid username or password";
            }
        } catch (PDOException $e) {
            $message = "Login failed";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ログイン</title>
        <style>
            body {font-family: sans-serif; padding: auto;}
            form { max-width: 400px; margin: auto; padding: auto; text-align: left;}
            label {display: block; margin: auto; text-align: left;}
            input {width: 100%; padding: auto;}
            button {padding: 10px 20px;}
            .message {color: red;}
            .success {color: green;}
        </style>
    </head>
    <body>
        <h1><strong>ユーザーログイン</strong></h1>

        <?php if ($message): ?>
            <div class="<?= isset($_GET['registered']) ? 'success' : 'message' ?>">
                <?= xss($message) ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="username">ユーザー名: </label>
            <input type="text" name="username" required>

            <label for="password">パスワード: </label>
            <input type="text" name="password" required>

            <button type="submit">ログイン</button>
        </form>
    </body>
</html>