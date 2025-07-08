<?php
require_once '../config/config.php';
require_once __DIR__ . '/../includes/functions.php';  

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    echo "無効なIDです。";
    exit;
}

$pet_id = (int)$_GET['id'];

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

        echo "<p style='color:green;'>更新が完了しました。</p>";
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
</head>
<body>
    <h1>ペット編集</h1>
    <a href="main.php">メインへ</a>
<form method="post" action="">
    <label>ペット名: <input type="text" name="pet_name" value="<?= htmlspecialchars($pet['pet_name']) ?>" required></label><br>
    
    <label>種類:
        <select name="pet_type" required>
            <option value="犬" <?= $pet['pet_type'] === '犬' ? 'selected' : '' ?>>犬</option>
            <option value="猫" <?= $pet['pet_type'] === '猫' ? 'selected' : '' ?>>猫</option>
            <option value="その他" <?= $pet['pet_type'] === 'その他' ? 'selected' : '' ?>>その他</option>
        </select>
    </label><br>

    <label>体重: <input type="number" step="0.1" name="pet_weight" value="<?= xss($pet['pet_weight']) ?>" required></label><br>
    
    <label>サイズ:
        <select name="pet_size" required>
            <option value="大型" <?= $pet['pet_size'] === '大型' ? 'selected' : '' ?>>大型</option>
            <option value="中型" <?= $pet['pet_size'] === '中型' ? 'selected' : '' ?>>中型</option>
            <option value="小型" <?= $pet['pet_size'] === '小型' ? 'selected' : '' ?>>小型</option>
        </select>
    </label><br>

    <label>生年月日: <input type="date" name="pet_dob" value="<?= xss($pet['pet_DOB']) ?>" required></label><br><br>
    
    <input type="submit" value="更新">
</form>

    <p><a href="pet_list.php">ペット一覧へ</a></p>
</body>
</html>