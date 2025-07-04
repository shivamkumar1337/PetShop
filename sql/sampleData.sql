USE pet_service_db;

-- Insert sample users
INSERT INTO users (username, password) VALUES
('admin', SHA2('admin123', 256)),
('staff1', SHA2('password1', 256));

-- Insert sample customers
INSERT INTO customers (customer_name, customer_mail, customer_number, customer_zipcode, address) VALUES
('Taro Yamada', 'taro@example.com', '09012345678', 3050005, '1-1 Takezono, Tsukuba'),
('Hanako Suzuki', 'hanako@example.com', '08087654321', 3050035, '2-2 Azuma, Tsukuba');

-- Insert sample pets
INSERT INTO pets (customer_id, pet_name, pet_age, pet_weight, pet_type, pet_size, pet_DOB) VALUES
(1, 'Pochi', 3, 12, 'Dog', 'Medium', '2021-04-20'),
(2, 'Momo', 2, 5, 'Cat', 'Small', '2022-01-15');

-- Insert sample services
INSERT INTO services (service_name, service_price, pet_type, pet_size) VALUES
('Bathing', 3000.00, 'Dog', 'Medium'),
('Hair Cut', 2500.00, 'Cat', 'Small'),
('Nail Clipping', 1200.00, 'Dog', 'Medium'),
('Vaccination', 4500.00, 'Cat', 'Small');

-- Insert sample service history
INSERT INTO service_history (customer_id, pet_id, service_id, service_date) VALUES
(1, 1, 1, '2024-06-15 10:00:00'),
(2, 2, 2, '2024-06-16 14:00:00'),
(1, 1, 3, '2024-06-20 11:30:00');

-- Insert sample appointments
INSERT INTO appointments (customer_id, service_id, pet_id, appointment_date, status) VALUES
(1, 1, 1, '2025-07-01 10:00:00', '予定確定'),
(2, 2, 2, '2025-07-02 15:00:00', '予定確定'),
(1, 3, 1, '2025-07-05 09:30:00', 'キャンセル');
