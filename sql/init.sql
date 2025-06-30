CREATE DATABASE IF NOT EXISTS pet_service_db;
USE pet_service_db;

-- Create tables for the pet service management system

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);
 
CREATE TABLE customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    customer_mail VARCHAR(100) UNIQUE,
    customer_number INT(11) UNIQUE,
    address VARCHAR(255) NOT NULL
);
 
CREATE TABLE pets (
    pet_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    pet_name VARCHAR(50) NOT NULL,
    pet_age INT NOT NULL,
    pet_weight INT NOT NULL,
    pet_type VARCHAR(50) NOT NULL,
    pet_DOB DATE NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE
);
 
CREATE TABLE services (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    service_price INT NOT NULL,
    pet_type VARCHAR(100) NOT NULL,
    pet_size VARCHAR(100) NOT NULL
);
 
CREATE TABLE service_history (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    pet_id INT,
    service_id INT,
    service_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (customer_id),
    INDEX (pet_id),
    INDEX (service_id),
    CONSTRAINT fk_history_customer FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE SET NULL,
    CONSTRAINT fk_history_pet FOREIGN KEY (pet_id) REFERENCES pets(pet_id) ON DELETE SET NULL,
    CONSTRAINT fk_history_service FOREIGN KEY (service_id) REFERENCES services(service_id) ON DELETE SET NULL
);
 
CREATE INDEX idx_pet_customer ON pets(customer_id);
