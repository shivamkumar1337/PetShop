<?php
require_once '../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    echo "不正なアクセスです。";
    exit;
}

$customer_id = $_GET['id'];
$customer = null;
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['customer_name'] ?? '');
    $zipcode = trim($_POST['customer_zipcode'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['customer_number'] ?? '');
    $mail = trim($_POST['customer_mail'] ?? '');

    try {
        $stmt = $pdo->prepare("
            UPDATE customers 
            SET customer_name = :name, customer_zipcode = :zipcode, 
                address = :address, customer_number = :phone, customer_mail = :mail 
            WHERE customer_id = :id
        ");
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
        $error = "更新エラー: " . xss($e->getMessage());
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
    echo "読み込みエラー: " . xss($e->getMessage());
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>顧客情報の編集</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header>
    <h1>顧客情報の編集</h1>
    <nav>
        <ul>
            <li><a href="main.php">メインへ</a></li>
        </ul>
    </nav>
</header>

<main>
    <?php if ($error): ?>
        <p class="error_message"><?= $error ?></p>
    <?php endif; ?>

    <form method="post" action="" class="mypage_form">
        <div class="form_my">
            <label for="customer_name">顧客名：</label>
            <input type="text" name="customer_name" id="customer_name" value="<?= xss($customer['customer_name']) ?>" required>
        </div>

        <div class="form_my">
            <label for="customer_zipcode">郵便番号：</label>
            <input type="text" name="customer_zipcode" id="customer_zipcode" value="<?= xss($customer['customer_zipcode']) ?>">
        </div>

        <div class="form_my">
            <label for="address">住所：</label>
            <input type="text" name="address" id="address" value="<?= xss($customer['address']) ?>">
        </div>

        <div class="form_my">
            <label for="customer_number">電話番号：</label>
            <input type="text" name="customer_number" id="customer_number" value="<?= xss($customer['customer_number']) ?>">
        </div>

        <div class="form_my">
            <label for="customer_mail">メールアドレス：</label>
            <input type="email" name="customer_mail" id="customer_mail" value="<?= xss($customer['customer_mail']) ?>">
        </div>

        <div class="my_btn">
            <button type="submit" class="my_submit_btn">更新</button>
        </div>
    </form>

    <div class="my_btn">
        <a href="customer_list.php" class="history_back_btn">顧客一覧へ</a>
    </div>
</main>

</body>
</html>
