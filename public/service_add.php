<?php
session_start();

<<<<<<< HEAD
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/config.php';
=======
require_once '../includes/db.php';
require_once(__DIR__ . '/session_check.php');
require_once '../config/config.php';
require_once '../includes/functions.php';
>>>>>>> 3efbac614213c85363b1b84c0ce4e71283a1fc91

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

<<<<<<< HEAD
            <!-- ▼ エラーがある場合は表示 -->
            <?php if (!empty($errors)): ?>
                <div style="color:red;">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= xss($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- ▼ 入力フォーム -->
            <form method="post" action="service_add_process.php" class="service_form">

                <!-- サービス名入力欄（テキスト） -->
                <div class="form_la">
                    <label for="service_name">サービス名</label>
                    <input type="text" name="service_name" id="service_name" required
                        value="<?= xss($old['service_name'] ?? '') ?>">
                </div>

                <!-- 種類（セレクトボックス） -->
                <div class="form_la">
                    <label for="pet_type">種類</label>
                    <select name="pet_type" id="pet_type" required>
                        <?php
                        // 選択肢の配列
                        $types = ['犬', '猫', 'その他'];
                        foreach ($types as $type) {
                            // 選択されていた値と一致する場合に selected をつける
                            $selected = ($old['pet_type'] ?? '') === $type ? 'selected' : '';
                            echo "<option value=\"$type\" $selected>$type</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- 大きさ（セレクトボックス） -->
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

                <!-- 料金（数値入力） -->
                <div class="form_la">
                    <label for="service_price">料金</label>
                    <input type="number" name="service_price" id="service_price" required min="0"
                        value="<?= xss($old['service_price'] ?? '') ?>">
                </div>

                <!-- 送信ボタン -->
                <div class="submit_btn">
                    <input type="submit" value="登録">
                </div>
            </form>
            <!-- ▲ 入力フォームここまで -->

        </main>
        <footer>
            <nav>
                <li><a href="service.php">サービス一覧へ</a></li>
            </nav>
        </footer>
=======
    <div style="display: flex; justify-content: flex-end; align-items: flex-end; margin-bottom: 20px;">
        <a href="main.php" style="display: inline-block; width: 150px; text-align: center; text-decoration: none; font-weight: bold; color: #000; padding: 10px; border: 1px solid #333; background-color: white;">メインへ</a>
>>>>>>> 3efbac614213c85363b1b84c0ce4e71283a1fc91
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
