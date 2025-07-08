<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>一覧表示選択画面</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .button-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin: 50px auto;
        }
        .button-grid form {
            margin: 0;
        }
        .button-grid button {
            width: 200px;
            height: 60px;
            font-size: 1.2rem;
            background-color: #CC6633;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .button-grid button:hover {
            background-color: #a44d25;
        }
    </style>
</head>
<body>
    <header>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1>一覧表示　選択画面</h1>
            <nav>
                <ul>
                    <li><a href="main.php">メインへ</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="button-grid">
            <form action="pet_list.php" method="get">
                <button type="submit">ペット一覧</button>
            </form>

            <form action="customer_list.php" method="get">
                <button type="submit">顧客一覧</button>
            </form>

            <form action="customer_pet_list.php" method="get">
                <button type="submit">飼い主別ペット一覧</button>
            </form>

            <form action="pet_service_list.php" method="get">
                <button type="submit">サービス別ペット一覧</button>
            </form>

            <form action="appointment_list.php" method="get">
                <button type="submit">予約一覧</button>
            </form>
        </div>
    </main>
</body>
</html>
