-- Create the database
CREATE DATABASE IF NOT EXISTS pet_service_db;
USE pet_service_db;

-- Table: users
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: customers
CREATE TABLE customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    customer_mail VARCHAR(100) UNIQUE,
    customer_number VARCHAR(15) UNIQUE,
    customer_zipcode INT(7) NOT NULL,
    address VARCHAR(255) NOT NULL
);

-- Table: pets
CREATE TABLE pets (
    pet_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    pet_name VARCHAR(50) NOT NULL,
    pet_age INT NOT NULL,
    pet_weight INT NOT NULL,
    pet_type VARCHAR(50) NOT NULL,
    pet_size VARCHAR(50) NOT NULL,
    pet_DOB DATE NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE
);

-- Explicit index on customer_id in pets table
CREATE INDEX idx_pet_customer ON pets(customer_id);

-- Table: services
CREATE TABLE services (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    service_price DECIMAL(10,2) NOT NULL,
    pet_type VARCHAR(100) NOT NULL,
    pet_size VARCHAR(100) NOT NULL
);

-- Table: service_history
CREATE TABLE service_history (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    pet_id INT,
    service_id INT,
    service_date DATETIME NOT NULL,

    INDEX (customer_id),
    INDEX (pet_id),
    INDEX (service_id),

    CONSTRAINT fk_history_customer FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE SET NULL,
    CONSTRAINT fk_history_pet FOREIGN KEY (pet_id) REFERENCES pets(pet_id) ON DELETE SET NULL,
    CONSTRAINT fk_history_service FOREIGN KEY (service_id) REFERENCES services(service_id) ON DELETE SET NULL
);

-- Table: appointments
CREATE TABLE appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    service_id INT NOT NULL,
    pet_id INT NOT NULL,
    appointment_date DATETIME NOT NULL,
    registeration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX (customer_id),
    INDEX (service_id),
    INDEX (pet_id),

    CONSTRAINT fk_appoint_customer FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE,
    CONSTRAINT fk_appoint_service FOREIGN KEY (service_id) REFERENCES services(service_id) ON DELETE CASCADE,
    CONSTRAINT fk_appoint_pet FOREIGN KEY (pet_id) REFERENCES pets(pet_id) ON DELETE CASCADE
);