
CREATE DATABASE shop_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shop_db;


CREATE TABLE users (
id BIGINT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255) NOT NULL,
email VARCHAR(255) UNIQUE NOT NULL,
password VARCHAR(255) NOT NULL,
role ENUM('customer', 'admin') DEFAULT 'customer',
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE products (
id BIGINT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255) NOT NULL,
description TEXT,
price DECIMAL(10, 2) NOT NULL,
stock INT NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE orders (
id BIGINT AUTO_INCREMENT PRIMARY KEY,
user_id BIGINT NOT NULL,
total DECIMAL(10, 2) NOT NULL,
status ENUM('pending', 'shipped', 'cancelled') DEFAULT 'pending',
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE order_items (
id BIGINT AUTO_INCREMENT PRIMARY KEY,
order_id BIGINT NOT NULL,
product_id BIGINT NOT NULL,
quantity INT NOT NULL,
price DECIMAL(10, 2) NOT NULL,
FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);


CREATE TABLE sessions (
id VARCHAR(255) PRIMARY KEY,
user_id BIGINT NOT NULL,
session_data TEXT,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


INSERT INTO users (name, email, password, role) VALUES
('Admin User', 'admin@shop.com', 'admin_password_hash', 'admin'),
('John Doe', 'john@shop.com', 'john_password_hash', 'customer');

INSERT INTO products (name, description, price, stock) VALUES
('Smartphone', 'Latest smartphone with advanced features', 599.99, 50),
('Laptop', 'High-performance laptop for professionals', 999.99, 30),
('Book', 'A fascinating book about programming', 19.99, 100);

• 
ALTER TABLE products ADD COLUMN image_url VARCHAR(255) DEFAULT NULL;

• 
CREATE TABLE payments (
id BIGINT AUTO_INCREMENT PRIMARY KEY,
order_id BIGINT NOT NULL,
payment_method ENUM('wallet', 'credit_card', 'bank_transfer') NOT NULL,
payment_status ENUM('success', 'failed') DEFAULT 'success',
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);


mysqldump -u [username] -p shop_db > shop_db_backup.sql



