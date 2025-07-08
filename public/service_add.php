<?php
require_once(__DIR__ . '/session_check.php');
require_once '../config/config.php';
require_once '../includes/functions.php';

$errors = $_SESSION['errors'] ?? [];
$old    = $_SESSION['old'] ?? [];

unset($_SESSION['errors'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>サービス登録</title>
</head>
<body style="margin: 0; background-color: #f5f5f5; font-family: Arial, sans-serif; padding: 30px;">

    <div style="display: flex; justify-content: flex-end; align-items: flex-end; margin-bottom: 20px;">
        <a href="main.php" style="display: inline-block; width: 150px; text-align: center; text-decoration: none; font-weight: bold; color: #000; padding: 10px; border: 1px solid #333; background-color: white;">メインへ</a>
    </div>

    <h1 style="margin-bottom: 20px;">サービス登録</h1>

    <?php if (!empty($errors)): ?>
        <div style="color:red; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                <?php foreach ($errors as $error): ?>
                    <li><?= xss($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="service_add_process.php"
        style="max-width: 600px; background-color: white; border: 1px solid #ccc; padding: 30px; border-radius: 8px; margin: auto;">
        
        <div style="margin-bottom: 15px;">
            <label for="service_name" style="font-weight: bold;">サービス名</label>
            <input type="text" name="service_name" id="service_name" required
                value="<?= xss($old['service_name'] ?? '') ?>"
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="pet_type" style="font-weight: bold;">種類</label>
            <select name="pet_type" id="pet_type" required
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                <?php
                $types = ['犬', '猫', 'その他'];
                foreach ($types as $type) {
                    $selected = ($old['pet_type'] ?? '') === $type ? 'selected' : '';
                    echo "<option value=\"$type\" $selected>$type</option>";
                }
                ?>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="pet_size" style="font-weight: bold;">大きさ</label>
            <select name="pet_size" id="pet_size" required
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                <?php
                $sizes = ['小型', '中型', '大型'];
                foreach ($sizes as $size) {
                    $selected = ($old['pet_size'] ?? '') === $size ? 'selected' : '';
                    echo "<option value=\"$size\" $selected>$size</option>";
                }
                ?>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="service_price" style="font-weight: bold;">料金</label>
            <input type="number" name="service_price" id="service_price" required min="0"
                value="<?= xss($old['service_price'] ?? '') ?>"
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <button type="submit"
            style="width: 100%; padding: 12px; background-color: #CC6633; color: white; font-weight: bold; border: none; border-radius: 6px; cursor: pointer;">
            登録
        </button>
    </form>

    <div style="margin-top: 30px; text-align: center;">
        <a href="service.php" >サービス一覧へ戻る</a>
    </div>

</body>
</html>