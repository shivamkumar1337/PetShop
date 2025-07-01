<?php

require __DIR__ . '/config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $raw_password = $_POST['password'] ?? '';

    if ($username && $raw_password) {
        $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        try {
            $stmt->execute([$username, $hashed_password]);
            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'username already exists']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'invalid input']);
    }
}

?>