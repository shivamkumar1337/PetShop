<?php
session_start(); // セッション開始（ログイン情報を使うため）

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/config.php';

// ログインしていなければリダイレクトまたはエラーメッセージ
if (!isset($_SESSION['user_id'])) {
    echo "<p>ログインしてください。</p>";
    exit;
}

$user_id = $_SESSION['user_id']; // セッションからユーザーID取得
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>UHD商事ペットショップ -マイページ-</title>
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            list-style: none;
            font-size: 100%;
            border: none;
        }
        *, *::before, *::after {
            box-sizing: border-box;
        }

        /* Global */
        html {
            font-size: 1.2rem;
        }

        body {
            font-family: sans-serif;
        }

        /* Header */
        header {
            width: 100%;
            padding: 30px 100px;
            background-color: #CC6633;
        }

        header h1 {
            font-size: 2.5rem;
            color: #FFFFFF;
        }

        header nav {
            text-align: right;
        }

        header li a {
            color: #FFFFFF;
            text-decoration: none;
            font-weight: bold;
        }

        /* Main */
        main {
            padding: 20px 0;
        }

        /* Table */
        table, th, td {
            border: 1px solid;
            border-collapse: collapse;
        }

        table {
            margin: 0 auto;
        }

        th, td {
            width: 400px;
            height: 75px;
            text-align: center;
        }

        /* Button */
        .mypage_btn {
            margin: 40px 0;
            width: 250px;
            height: 80px;
            font-size: 1rem;
            cursor: pointer;
        }

        .my_btn {
            text-align: center;
        }

        /* Footer (if needed later) */
        footer {
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>
<body>
<div>
    <header>
        <h1>マイページ</h1>
        <nav>
            <ul>
                <li><a href="main.php">メインへ</a></li>
            </ul>
        </nav>
    </header>

    <main class="mypage">
        <?php
        try {
            // ログイン中のユーザーの情報を取得
            $stmt = $pdo->prepare("SELECT creation_date, username FROM users WHERE user_id = :id");
            $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            $mypage_table = $stmt->fetchAll();

            if (empty($mypage_table)) {
                echo "<p>ユーザー情報が見つかりません。</p>";
            } else {
                ?>
                <div>
                    <form>
                        <table class="mypage_table">
                            <thead>
                                <tr>
                                    <th>登録日</th>
                                    <th>名前</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mypage_table as $mypage): ?>
                                    <tr>
                                        <td><?= xss($mypage['creation_date']) ?></td>
                                        <td><?= xss($mypage['username']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </form>
                </div>
                <?php
            }
        } catch (PDOException $e) {
            echo "<p>エラー: " . xss($e->getMessage()) . "</p>";
        }
        ?>

        <div class="my_btn">
            <button class="mypage_btn" onclick="location.href='mypage_edit.php'">ログイン情報編集</button>
        </div>
    </main>
</div>
</body>
</html>
