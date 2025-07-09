<?php
require_once '../config/config.php';
require_once __DIR__ . '/../includes/functions.php';  

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    echo "無効なIDです。";
    exit;
}

$pet_id = (int)$_GET['id'];
$message = '';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = $pdo->prepare("UPDATE pets SET pet_name = :name, pet_type = :type,
                                pet_weight = :weight, pet_size = :size, pet_DOB = :dob
                                WHERE pet_id = :id");

        $stmt->execute([
            ':name' => $_POST['pet_name'],
            ':type' => $_POST['pet_type'],
            ':weight' => $_POST['pet_weight'],
            ':size' => $_POST['pet_size'],
            ':dob' => $_POST['pet_dob'],
            ':id' => $pet_id,
        ]);

        $message = "✅ 更新が完了しました。";
    }

    $stmt = $pdo->prepare("SELECT * FROM pets WHERE pet_id = :id");
    $stmt->execute([':id' => $pet_id]);
    $pet = $stmt->fetch();

    if (!$pet) {
        echo "該当のペットが見つかりません。";
        exit;
    }

} catch (PDOException $e) {
    echo "エラー: " . xss($e->getMessage());
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ペット編集</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1>ペット編集</h1>
            <nav>
                <ul>
                    <li><a href="main.php">メインへ</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="form_wrap">
            <?php if ($message): ?>
                <p style="color: green; font-weight: bold;"><?= $message ?></p>
            <?php endif; ?>

            <form method="post">
                <div class="form_la">
                    <label for="pet_name">ペット名:</label>
                    <input type="text" name="pet_name" id="pet_name" value="<?= xss($pet['pet_name']) ?>" required>
                </div>

                <div class="form_la">
                    <label for="pet_type">種類:</label>
                    <select name="pet_type" id="pet_type" required>
                        <option value="犬" <?= $pet['pet_type'] === '犬' ? 'selected' : '' ?>>犬</option>
                        <option value="猫" <?= $pet['pet_type'] === '猫' ? 'selected' : '' ?>>猫</option>
                        <option value="その他" <?= $pet['pet_type'] === 'その他' ? 'selected' : '' ?>>その他</option>
                    </select>
                </div>

                <div class="form_la">
                    <label for="pet_weight">体重:</label>
                    <input type="number" step="0.1" name="pet_weight" id="pet_weight" value="<?= xss($pet['pet_weight']) ?>" required>
                </div>

                <div class="form_la">
                    <label for="pet_size">サイズ:</label>
                    <select name="pet_size" id="pet_size" required>
                        <option value="大型" <?= $pet['pet_size'] === '大型' ? 'selected' : '' ?>>大型</option>
                        <option value="中型" <?= $pet['pet_size'] === '中型' ? 'selected' : '' ?>>中型</option>
                        <option value="小型" <?= $pet['pet_size'] === '小型' ? 'selected' : '' ?>>小型</option>
                    </select>
                </div>

                <div class="form_la">
                    <label for="pet_dob">生年月日:</label>
                    <input type="date" name="pet_dob" id="pet_dob" value="<?= xss($pet['pet_DOB']) ?>" required>
                </div>

                <div class="submit_btn">
                    <input type="submit" class="my_submit_btn" value="更新">
                </div>
            </form>

            <div class="link">
                <a href="pet_list.php">ペット一覧へ</a>
            </div>
        </div>
    </main>
</body>
</html>
