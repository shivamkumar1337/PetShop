<?php
require_once '../config/config.php';
require_once __DIR__ . '/../includes/functions.php'; 

$keyword = trim($_GET['keyword'] ?? '');
?>

<!DOCTYPE html>
<html lang='ja'>
<head>
    <meta charset='utf-8'>
    <title>飼い主別ペット一覧画面</title>
    <link rel="stylesheet" href="style.css">
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #print-area, #print-area * {
                visibility: visible;
            }
            #print-area {
                position: absolute;
                left: 0;
                top: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
<div>
    <header>
        <h1>飼い主別ペット一覧</h1>
        <nav>
            <ul>
                <li><a href="main.php">メインへ</a></li>
            </ul>
        </nav>
    </header>

<main>
    <form method="get" action="" class="no-print">
        <input type="text" name="keyword" placeholder="顧客名を入力" value="<?= xss($keyword) ?>">
        <input type="submit" value="🔍 検索">
    </form>

    <button onclick="window.print()" class="no-print">🖨 印刷</button>
</main>

<main id="print-area">
    <?php
    if ($keyword !== '') {
        try {
            $stmt = $pdo->prepare("SELECT * FROM customers WHERE customer_name LIKE :kw");
            $stmt->execute([':kw' => '%' . $keyword . '%']);
            $customers = $stmt->fetchAll();

            if (empty($customers)) {
                echo "<p>該当する顧客は見つかりませんでした。</p>";
            } else {
                foreach ($customers as $customer):
    ?>
                <table border="1" style="margin-top: 20px;">
                    <tr><th>顧客名</th><td><?= xss($customer['customer_name']) ?></td></tr>
                    <tr><th>住所</th><td><?= xss($customer['customer_zipcode'] . ' ' . $customer['address']) ?></td></tr>
                    <tr><th>電話番号</th><td><?= xss($customer['customer_number']) ?></td></tr>
                    <tr><th>メールアドレス</th><td><?= xss($customer['customer_mail']) ?></td></tr>
                </table>

                <?php
                    $stmt2 = $pdo->prepare("SELECT * FROM pets WHERE customer_id = :id");
                    $stmt2->execute([':id' => $customer['customer_id']]);
                    $pets = $stmt2->fetchAll();

                    if (empty($pets)) {
                        echo "<p>ペット情報は登録されていません。</p>";
                    } else {
                ?>
                    <table border="1" style="margin-top: 10px;">
                        <thead>
                            <tr>
                                <th>ペット名</th>
                                <th>年齢</th>
                                <th>種類</th>
                                <th>体重</th>
                                <th>サイズ</th>
                                <th>生年月日</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pets as $pet): ?>
                                <tr>
                                    <td><?= xss($pet['pet_name']) ?></td>
                                    <td><?= xss($pet['pet_age']) ?></td>
                                    <td><?= xss($pet['pet_type']) ?></td>
                                    <td><?= xss($pet['pet_weight']) ?></td>
                                    <td><?= xss($pet['pet_size']) ?></td>
                                    <td><?= xss($pet['pet_DOB']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php } ?>
    <?php
                endforeach;
            }
        } catch (PDOException $e) {
            echo "<p>エラー: " . xss($e->getMessage()) . "</p>";
        }
    } else {
        echo "<p>顧客名を入力してください。</p>";
    }
    ?>
</main>

<div class="link no-print">
    <a href="list_select.php">一覧表示選択画面へ</a>
</div>
</div>
</body>
</html>