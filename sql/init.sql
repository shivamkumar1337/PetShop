CREATE DATABASE IF NOT EXISTS pet_service_db;
USE pet_service_db;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    customer_mail VARCHAR(100) UNIQUE,
    customer_number VARCHAR(15) UNIQUE,
    customer_zipcode INT(7) NOT NULL,
    address VARCHAR(255) NOT NULL
);

CREATE TABLE pets (
    pet_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    pet_name VARCHAR(50) NOT NULL,
    pet_weight INT NOT NULL,
    pet_type VARCHAR(50) NOT NULL,
    pet_size VARCHAR(50) NOT NULL,
    pet_DOB DATE NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE
);

CREATE INDEX idx_pet_customer ON pets(customer_id);

CREATE TABLE services (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    service_price INT(11) NOT NULL,
    pet_type VARCHAR(100) NOT NULL,
    pet_size VARCHAR(100) NOT NULL
);

CREATE TABLE service_history (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    pet_id INT,
    service_id INT,
    service_date DATETIME NOT NULL,

    customer_name VARCHAR(100) NOT NULL,
    pet_name VARCHAR(50) NOT NULL,
    service_name VARCHAR(100) NOT NULL,
    service_price INT(11) NOT NULL,
    pet_type VARCHAR(50) NOT NULL,
    pet_size VARCHAR(50) NOT NULL,    
    INDEX (customer_id),
    INDEX (pet_id),
    INDEX (service_id)
);


CREATE TABLE appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    service_id INT NOT NULL,
    pet_id INT NOT NULL,
    appointment_date DATETIME NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX (customer_id),
    INDEX (service_id),
    INDEX (pet_id),

    CONSTRAINT fk_appoint_customer FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE,
    CONSTRAINT fk_appoint_service FOREIGN KEY (service_id) REFERENCES services(service_id) ON DELETE CASCADE,
    CONSTRAINT fk_appoint_pet FOREIGN KEY (pet_id) REFERENCES pets(pet_id) ON DELETE CASCADE
);