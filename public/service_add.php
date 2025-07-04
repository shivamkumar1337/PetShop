<?php
require_once '../includes/db.php';
require_once '../config/config.php';
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
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
                <form method="post" action="service_add_process.php">
                    <div>
                        <label for="service_name">サービス名</label>
                        <input type="text" name="service_name" id="service_name" required>
                    </div>
                    <div>
                        <label for="pet_type">種類</label>
                        <select name="pet_type" id="pet_type" required>
                            <option value="犬">犬</option>
                            <option value="猫">猫</option>
                            <option value="その他">その他</option>
                        </select>
                    </div>
                    <p>
                        <label for="pet_size">大きさ</label>
                        <select name="pet_size" id="pet_size" required>
                            <option value="小型">小型</option>
                            <option value="中型">中型</option>
                            <option value="大型">大型</option>
                        </select>
                    </p>
                    <div>
                        <label for="service_price" step=1 min=0 max=999999>料金</label>
                        <input type="number" name="service_price" id="service_price" required>
                    </div>
                    <input type="submit" value="登録">
                </form>                
            </main>
        </div>
    </body>
</html>
