<?php
require_once '../config/config.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    echo "不正なアクセスです。";
    exit;
}

$customer_id = $_GET['id'];
$customer = null;
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['customer_name']);
    $zipcode = trim($_POST['customer_zipcode']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['customer_number']);
    $mail = trim($_POST['customer_mail']);

    try {
        $stmt = $pdo->prepare("UPDATE customers SET customer_name = :name, customer_zipcode = :zipcode, address = :address, customer_number = :phone, customer_mail = :mail WHERE customer_id = :id");
        $stmt->execute([
            ':name' => $name,
            ':zipcode' => $zipcode,
            ':address' => $address,
            ':phone' => $phone,
            ':mail' => $mail,
            ':id' => $customer_id
        ]);

        header("Location: customer_list.php?updated=1");
        exit;
    } catch (PDOException $e) {
        $error = "更新エラー: " . htmlspecialchars($e->getMessage());
    }
}

try {
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE customer_id = :id");
    $stmt->execute([':id' => $customer_id]);
    $customer = $stmt->fetch();

    if (!$customer) {
        echo "顧客が見つかりません。";
        exit;
    }
} catch (PDOException $e) {
    echo "読み込みエラー: " . htmlspecialchars($e->getMessage());
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>顧客情報の編集</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>顧客情報の編集</h1>
         <a href="main.php">メインへ</a>
<form method="post" action="">

    <?php if ($error): ?>
        <p style="color:red"><?= $error ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label>顧客名：<input type="text" name="customer_name" value="<?= htmlspecialchars($customer['customer_name']) ?>" required></label><br>
        <label>郵便番号：<input type="text" name="customer_zipcode" value="<?= htmlspecialchars($customer['customer_zipcode']) ?>"></label><br>
        <label>住所：<input type="text" name="address" value="<?= htmlspecialchars($customer['address']) ?>"></label><br>
        <label>電話番号：<input type="text" name="customer_number" value="<?= htmlspecialchars($customer['customer_number']) ?>"></label><br>
        <label>メールアドレス：<input type="email" name="customer_mail" value="<?= htmlspecialchars($customer['customer_mail']) ?>"></label><br><br>
        
        <button type="submit">更新</button>
        
    </form>
    <a href="customer_list.php">顧客一覧へ</a>
</body>
</html>