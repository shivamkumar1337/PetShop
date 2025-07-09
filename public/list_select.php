<!DOCTYPE html>
<html lang="ja">
    <meta charset="utf-8">
<head>
    <title>一覧表示選択画面</title>
</head>
<body>
    <h1>一覧表示　選択画面</h1>
    <div class="button-grid">
        <form action="pet_list.php" method="Get">
            <button type="submit">ペット一覧</button>
        </form>

        <form action="customer_list.php" method="Get">
            <button type="submit">顧客一覧</button>
        </form>

        <form action="customer_pet_list.php" method="Get">
            <button type="submit">飼い主別ペット一覧</button>
        </form>

        <form action="pet_service_list.php" method="Get">
            <button type="submit">サービス別ペット一覧</button>
        </form>
    </div>

    <div class="link">
        <a href="main.php">メインへ</a>
    </div>
</body>

</head>
</html>
