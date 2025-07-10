<?php
require_once(__DIR__ . '/session_check.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/../config/config.php');

$errors = $_SESSION['errors'] ?? [];
$old    = $_SESSION['old'] ?? [];

unset($_SESSION['errors'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>サービス登録</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div>
    <header>
        <h1>サービス登録</h1>
        <nav>
            <ul>
                <li><a href="main.php">メインへ</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?php if (!empty($errors)): ?>
            <div style="color:red; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($errors as $error): ?>
                        <li><?= xss($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="service_add_process.php" class="service_form">
            <div class="form_la">
                <label for="service_name">サービス名</label>
                <input type="text" name="service_name" id="service_name" required value="<?= xss($old['service_name'] ?? '') ?>">
            </div>

            <div class="form_la">
                <label for="pet_type">種類</label>
                <select name="pet_type" id="pet_type" required>
                    <?php
                    $types = ['犬', '猫', 'その他'];
                    foreach ($types as $type) {
                        $selected = ($old['pet_type'] ?? '') === $type ? 'selected' : '';
                        echo "<option value=\"$type\" $selected>$type</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form_la">
                <label for="pet_size">大きさ</label>
                <select name="pet_size" id="pet_size" required>
                    <?php
                    $sizes = ['小型', '中型', '大型'];
                    foreach ($sizes as $size) {
                        $selected = ($old['pet_size'] ?? '') === $size ? 'selected' : '';
                        echo "<option value=\"$size\" $selected>$size</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form_la">
                <label for="service_price">料金</label>
                <input type="number" name="service_price" id="service_price" required min="0" max="9999999999" value="<?= xss($old['service_price'] ?? '') ?>">
            </div>

            <div class="my_btn">
                <input type="submit" value="登録" class="my_submit_btn">
            </div>
        </form>

        <div class="link" style="margin-top: 30px;">
            <a href="service.php">サービス一覧へ戻る</a>
        </div>
    </main>
</div>
</body>
</html>
