USE pet_service_db;

-- Users
INSERT INTO users (username, password) VALUES
('admin', 'adminpass123'),
('staff1', 'staffpass456'),
('userA', 'userpassA'),
('userB', 'userpassB'),
('userC', 'userpassC');

-- Customers
INSERT INTO customers (customer_name, customer_mail, customer_number, customer_zipcode, address) VALUES
('Tanaka Taro', 'tanaka@example.com', '09012345678', 1000001, 'Tokyo, Chiyoda-ku'),
('Sato Yuki', 'sato@example.com', '08098765432', 1500002, 'Tokyo, Shibuya-ku'),
('Kobayashi Ken', 'ken@example.com', '07076543210', 5300003, 'Osaka, Kita-ku'),
('Yamada Hanako', 'hanako@example.com', '09011223344', 4600004, 'Nagoya, Naka-ku'),
('Suzuki Ichiro', 'ichiro@example.com', '08044332211', 2200005, 'Yokohama, Nishi-ku'),
('Nakamura Mei', 'mei@example.com', '07099887766', 1600006, 'Sapporo, Chuo-ku'),
('Matsuda Koji', 'koji@example.com', '09077889900', 7000007, 'Fukuoka, Hakata-ku'),
('Ishikawa Kana', 'kana@example.com', '08033445566', 3000008, 'Kobe, Chuo-ku'),
('Ota Naoki', 'naoki@example.com', '07022334455', 9800009, 'Sendai, Aoba-ku'),
('Abe Mari', 'mari@example.com', '09055667788', 6000010, 'Kyoto, Sakyo-ku');

-- Pets
INSERT INTO pets (customer_id, pet_name, pet_weight, pet_type, pet_size, pet_DOB) VALUES
(1, 'Pochi', 5, '犬', '小型', '2020-05-01'),
(1, 'Tama', 4, '猫', '小型', '2021-01-15'),
(2, 'Leo', 20, '犬', '大型', '2018-10-10'),
(3, 'Mimi', 8, '猫', '中型', '2019-07-20'),
(4, 'Choco', 3, '犬', '小型', '2022-04-22'),
(5, 'Sora', 6, '猫', '中型', '2020-11-11'),
(6, 'Hana', 25, '犬', '大型', '2017-12-25'),
(7, 'Maru', 2, '猫', '小型', '2023-01-01'),
(8, 'Kuro', 12, '犬', '中型', '2021-06-30'),
(9, 'Shiro', 9, '猫', '中型', '2020-03-14'),
(10, 'Luna', 15, '犬', '中型', '2019-09-09'),
(2, 'Tsubaki', 4, '猫', '小型', '2021-03-05'),
(3, 'Sakura', 18, '犬', '大型', '2018-08-08'),
(4, 'Kuma', 7, '犬', '中型', '2020-02-02');

-- Services
INSERT INTO services (service_name, service_price, pet_type, pet_size) VALUES
('シャンプー', 3000, '犬', '小型'),
('トリミング', 5000, '犬', '大型'),
('爪切り', 1500, '猫', '中型'),
('健康診断', 7000, '猫', '小型'),
('しつけ教室', 4500, '犬', '中型'),
('歯磨き', 2000, '犬', '小型'),
('耳掃除', 1800, '猫', '小型'),
('目薬', 1200, '猫', '中型'),
('栄養相談', 3500, '犬', '大型'),
('散歩代行', 4000, '犬', '中型');

-- Appointments
INSERT INTO appointments (customer_id, service_id, pet_id, appointment_date) VALUES
(1, 1, 1, '2024-07-01 10:00:00'),
(1, 4, 2, '2024-07-02 14:00:00'),
(2, 2, 3, '2024-07-03 09:30:00'),
(3, 3, 4, '2024-07-04 16:00:00'),
(4, 5, 5, '2024-07-05 11:15:00'),
(5, 6, 6, '2024-07-06 13:20:00'),
(6, 7, 7, '2024-07-07 15:00:00'),
(7, 8, 8, '2024-07-08 12:10:00'),
(8, 9, 9, '2024-07-09 10:30:00'),
(9, 10, 10, '2024-07-10 17:00:00'),
(2, 1, 11, '2024-07-11 09:00:00'),
(3, 2, 12, '2024-07-12 13:00:00'),
(4, 3, 13, '2024-07-13 14:00:00');

-- Service History
INSERT INTO service_history (customer_id, pet_id, service_id, service_date) VALUES
(1, 1, 1, '2024-06-10 10:00:00'),
(1, 2, 4, '2024-06-15 14:00:00'),
(2, 3, 2, '2024-06-20 09:30:00'),
(3, 4, 3, '2024-06-25 16:00:00'),
(4, 5, 5, '2024-06-26 11:15:00'),
(5, 6, 6, '2024-06-27 13:20:00'),
(6, 7, 7, '2024-06-28 15:00:00'),
(7, 8, 8, '2024-06-29 12:10:00'),
(8, 9, 9, '2024-06-30 10:30:00'),
(9, 10, 10, '2024-06-30 17:00:00'),
(2, 11, 1, '2024-06-01 09:00:00'),
(3, 12, 2, '2024-06-02 13:00:00'),
(4, 13, 3, '2024-06-03 14:00:00');
