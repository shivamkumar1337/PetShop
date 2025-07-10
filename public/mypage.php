<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/config.php';
require_once(__DIR__ . '/session_check.php');

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
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <title>マイページ</title>
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
                            <table border=1 class="mypage_table">
                                <thead>
                                    <tr>
                                        <th>登録日</th>
                                        <th>名前</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($mypage_table as $mypage): ?>
                                        <tr>
                                            <td><?php echo xss($mypage['creation_date']); ?></td>
                                            <td><?php echo xss($mypage['username']); ?></td>
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
