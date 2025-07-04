<?php

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../includes/functions.php');

//header('Content_Type: application/json');
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = xss($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($password) || empty($confirm_password)) {
    //echo json_encode(['error' => 'All feilds required']);
    $message = "All fields required";
    exit;
    }

    if ($password !== $confirm_password) {
        //echo json_encode(['error' => 'Password do not match']);
        $message = "Password do not match";
        exit;
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hashed_password]);

            //echo json_encode(['success' => 'User registered successfully']);
            header("Location: login.php?registered=1");
            exit;
        } catch(PDOException $e) {
            if ($e->getCode() == 23000) {
                //echo json_encode(['error' => 'Username taken']);
                $message = "Username taken";
            } else {
                //echo json_encode(['error' => 'Registration failed']);
                $message = "Registration failed";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ユーザー登録</title>
        <style>
            body {font-family: sans-serif; padding: auto;}
            form { max-width: 400px; margin: auto; padding: auto; text-align: left;}
            label {display: block; margin: auto; text-align: left;}
            input {width: 100%; padding: auto;}
            button {padding: 10px 20px;}
            .message {color: red;}
        </style>
    </head>
    <body>
        <h1><strong>新しいユーザーを登録する</strong></h1>
        
        <?php if ($message): ?>
            <div class="message"><?= xss($message) ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="username">ユーザー名: </label>
            <input type="text" name="username" required>

            <label for="password">パスワード: </label>
            <input type="text" name="password" required>

            <label for="confirm_password">パスワードを再入力: </label>
            <input type="text" name="confirm_password" required>

            <button type="submit">登録</button>
        </form>
    </body>
</html>
