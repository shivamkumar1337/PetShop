<?php
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/session_check.php');

$message = '';
$customer_id = $_GET['customer_id'] ?? null;

if (!$customer_id) {
    header("Location: select_customer.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = xss($_POST['pet_name'] ?? '');
    $wt = xss($_POST['pet_weight'] ?? '');
    $type = xss($_POST['pet_type'] ?? '');
    $size = xss($_POST['pet_size'] ?? '');
    $dob = xss($_POST['pet_DOB'] ?? '');

    if (empty($name) || empty($type) || empty($size)) {
        $message = "名前、 種類、サイズは必須です";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO pets (customer_id, pet_name, pet_weight, pet_type, pet_size, pet_DOB) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$customer_id, $name, $wt, $type, $size, $dob]);

            $message = 'ペットが正常に追加されました';
            header("Location: view_pet.php?customer_id=" . $customer_id);
            exit;
        } catch (PDOException $e) {
            $message = 'エラーが発生しました' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ペットの登録</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>
    <h1>ペットの登録</h1>
    <nav>
        <ul>
            <li><a href="main.php">メインへ</a></li>
        </ul>
    </nav>
</header>

<main>
    <?php if ($message): ?>
        <div style="text-align:center; margin-bottom:20px; color:<?= strpos($message, '正常') !== false ? 'green' : 'red' ?>">
            <?= xss($message) ?>
        </div>
    <?php endif; ?>

    <form action="" method="post" class="service_form">
        <div class="form_la">
            <label>名前:</label>
            <input type="text" name="pet_name" required value="<?= xss($name ?? '') ?>">
        </div>

        <div class="form_la">
            <label>体重（㎏）:</label>
            <input type="number" name="pet_weight" min="0" step="0.1" max="200" required value="<?= xss($wt ?? '') ?>">
        </div>

        <div class="form_la">
            <label>種類:</label>
            <select name="pet_type" required>
                <option value="">　</option>
                <option value="dog">犬</option>
                <option value="cat">猫</option>
                <option value="others">その他</option>
            </select>
        </div>

        <div class="form_la">
            <label>サイズ:</label>
            <select name="pet_size" required>
                <option value="">　</option>
                <option value="small">小型</option>
                <option value="medium">中型</option>
                <option value="large">大型</option>
            </select>
        </div>

        <div class="form_la">
            <label>生年月日:</label>
            <input type="date" name="pet_DOB" value="<?= xss($dob ?? '') ?>">
        </div>

        <div class="my_btn">
            <button class="my_submit_btn" type="submit">登録</button>
        </div>
    </form>
</main>

<div class="link">
    <a href="select_customer.php">利用登録へ</a>
</div>
</body>
</html>
