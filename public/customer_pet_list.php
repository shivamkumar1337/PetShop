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
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #print-area,
            #print-area * {
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

        <main id="print-area">
        <div style="display: flex; flex-direction:row; justify-content: center; align-items: center;">
            <form method="get" action="" class="history_search_wrap">
                <input type="text" name="keyword" placeholder="顧客名を入力" value="<?= xss($keyword) ?>"
                    class="history_search_input">
                <input type="submit" value="🔍"
                    class="history_search_btn">
            </form>

            <div style="display: flex; justify-content: flex-end;">
                <button onclick="window.print()" class="history_delete_btn">🖨 印刷</button>
            </div>
        </div>

            <?php
            if ($keyword !== '') {
                try {
                    $stmt = $pdo->prepare("SELECT * FROM customers WHERE customer_name LIKE :kw");
                    $stmt->execute([':kw' => '%' . $keyword . '%']);
                    $customers = $stmt->fetchAll();

                    if (empty($customers)) {
                        echo "<p style='text-align: center;'>該当する顧客は見つかりませんでした。</p>";
                    } else {
                        foreach ($customers as $customer):
            ?>
                            <table class="history_table" style="margin-top: 20px;">
                                <tr>
                                    <th>顧客名</th>
                                    <td><?= xss($customer['customer_name']) ?></td>
                                </tr>
                                <tr>
                                    <th>住所</th>
                                    <td><?= xss($customer['customer_zipcode'] . ' ' . $customer['address']) ?></td>
                                </tr>
                                <tr>
                                    <th>電話番号</th>
                                    <td><?= xss($customer['customer_number']) ?></td>
                                </tr>
                                <tr>
                                    <th>メールアドレス</th>
                                    <td><?= xss($customer['customer_mail']) ?></td>
                                </tr>
                            </table>

                            <?php
                            $stmt2 = $pdo->prepare("SELECT * FROM pets WHERE customer_id = :id");
                            $stmt2->execute([':id' => $customer['customer_id']]);
                            $pets = $stmt2->fetchAll();

                            if (empty($pets)) {
                                echo "<p style='text-align: center;'>ペット情報は登録されていません。</p>";
                            } else {
                            ?>
                                <table class="history_table">
                                    <thead class="table_header">
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
                                                <td class="h2">
                                                    <?php
                                                    $dob = new DateTime($pet['pet_DOB']);
                                                    $today = new DateTime();
                                                    $age = $today->diff($dob)->y;
                                                    echo $age;
                                                    ?>
                                                </td>
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
                    echo "<p class='error_message'>エラー: " . xss($e->getMessage()) . "</p>";
                }
            } else {
                echo "<p style='text-align: center;'>顧客名を入力してください。</p>";
            }
            ?>
        </main>

        <div class="link">
            <a href="list_select.php">一覧表示選択画面へ</a>
        </div>

</body>

</html>