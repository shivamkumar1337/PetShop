<?php 

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/session_check.php');
require_once(__DIR__ . '/../includes/functions.php');

$message = '';

try {
    $stmt = $pdo->query("SELECT pets.pet_id, pets.customers.id, customers.customer_name, pets.pet_name, pets.pet_type, pets.pet_size, pets.pet_DOB 
        FROM pets 
        JOIN customers ON pets.customer_id = customers.customer_id 
        ORDER BY pet_id
    ");
    $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($pets)) {
        header("Refresh: 3; url=select_pet.php");
        $message = "No pets registered for the selected customer";
    }
} catch (PDOException $e) {
    $message = "Failed to get pet list" . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ペット一覧</title>
        <style>
            body {font-family: sans-serif; padding: auto;}
            table {width: 80%; margin: auto; border-collapse: collapse;}
            th, td {padding: auto; border: 1px solid red;}
            th {background-color: rgb(211, 211, 211);}
            a.select-btn {
                display: inline-block;
                padding: auto;
                background-color: rgb(101, 101, 101);
                color: #fff;
                text-decoration: none;
                border-radius: 5px;
            }
            a.select-btn:hover {
                background-color: rgb(50, 50, 50);
            }
            .message {color: red;}
        </style>
    </head>
    <body>
        <h1>一覧からペットを選ぶ</h1>
        <?php if (!empty($pets)): ?>
            <table>
                <tr>
                    <th>ペットID</th>
                    <th>顧客名</th>
                    <th>ペット名</th>
                    <th>種類</th>
                    <th>サイズ</th>
                    <th>生年月日</th>
                </tr>
                <?php foreach ($pets as $pet): ?>
                    <tr>
                        <td><?= xss($pet['pet_id']) ?></td>
                        <td><?= xss($pet['customer_name']) ?></td>
                        <td><?= xss($pet['pet_name']) ?></td>
                        <td><?= xss($pet['pet_type']) ?></td>
                        <td><?= xss($pet['pet_size']) ?></td>
                        <td><?= xss($pet['pet_DOB']) ?></td>
                        <td>
                            <a class="select-btn" href="select_service.php?customer_id=<?= $pet['customer_id'] ?>&pet_id=<?= $pet['pet_id'] ?>">選択</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>登録されたペットが見つかりません</p>
        <?php endif; ?>
    </body>
</html>