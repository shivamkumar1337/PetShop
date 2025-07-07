USE pet_service_db;

-- Insert sample customers
INSERT INTO customers (customer_name, customer_mail, customer_number, customer_zipcode, address) VALUES
('Tanaka Ichiro', 'tanaka@example.com', '08099998888', 3050018, '3-3 Takezono, Tsukuba'),
('Sato Keiko', 'sato@example.com', '07011112222', 3050033, '4-4 Azuma, Tsukuba');

-- Insert pets with birthday in current month (e.g., July)
INSERT INTO pets (customer_id, pet_name, pet_age, pet_weight, pet_type, pet_size, pet_DOB) VALUES
(5, 'Kuro', 14, 'Dog', 'Medium', '2020-07-10'),  -- Birthday in July
(6, 'Shiro', 6, 'Cat', 'Small', '2022-07-20');     -- Birthday in July

-- Insert services
INSERT INTO services (service_name, service_price, pet_type, pet_size) VALUES
('Shampoo', 2000.00, 'Dog', 'Medium'),
('Checkup', 3500.00, 'Cat', 'Small');

-- Insert today's appointments (e.g., '2025-07-04')
INSERT INTO appointments (customer_id, service_id, pet_id, appointment_date) VALUES
(5, 5, 5, '2025-07-04 10:30:00'),
(6, 6, 6, '2025-07-04 15:00:00');
