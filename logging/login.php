<?php

require __DIR__ . 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    error_log("entered password- " . $password);
    error_log("stored hash- " . $user['password']);
    error_log("match result- " . (password_verify($password, $user['password']) ? true : false));

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $username;
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'invalid credentials']);
    }
}

?>