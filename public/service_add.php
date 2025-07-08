<?php
// セッションの開始（エラーや入力値の保持に使う）
session_start();

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/config.php';

// エラーと入力値をセッションから取得（あれば）
$errors = $_SESSION['errors'] ?? [];
$old    = $_SESSION['old'] ?? [];

// 一度取得したらセッションから削除（F5で再表示されないように）
unset($_SESSION['errors'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/sheet_style.css" type="text/css">
    <title>サービス登録</title>
</head>
<body>
    <div>
        <header>
            <h1>サービス -登録-</h1>
            <nav>
                <ul>
                    <li><a href="main.php">メインへ</a></li>
                </ul>
            </nav>
        </header>
        <main>

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
    </div>
</body>
</html>
