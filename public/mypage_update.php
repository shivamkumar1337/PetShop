<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/config.php';
require_once(__DIR__ . '/session_check.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$username = trim($_POST['username'] ?? '');
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

$errors = [];

if ($username === '') {
    $errors[] = 'ユーザー名を入力してください。空白は認めません。';
}
if ($current_password === '' || $new_password === '' || $confirm_password === '') {
    $errors[] = 'すべてのパスワード欄を入力してください。';
}
if ($new_password !== $confirm_password) {
    $errors[] = '新しいパスワードと確認用パスワードが一致しません。';
}

// 現在のパスワード確認
$stmt = $pdo->prepare("SELECT password FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user || !password_verify($current_password, $user['password'])) {
    $errors[] = '現在のパスワードが正しくありません。';
}

// エラー時：セッションに保存してリダイレクト
if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data'] = [
        'username' => $username,
        // パスワードは保存しない（セキュリティ上）
    ];
    header('Location: mypage_edit.php');
    exit;
}

// パスワード更新
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
$update_stmt = $pdo->prepare("UPDATE users SET username = ?, password = ? WHERE user_id = ?");
$update_stmt->execute([$username, $hashed_password, $user_id]);

// 完了メッセージ表示（またはリダイレクト）
header('Location: mypage.php?updated=1');
exit;
