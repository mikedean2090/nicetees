CREATE DATABASE IF NOT EXISTS nicetees CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE nicetees;

-- USERS TABLE
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- PRODUCTS TABLE
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    category VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ORDERS TABLE
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    zip VARCHAR(20) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ORDER ITEMS TABLE
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Default admin: email admin@nicetees.com, password admin123
INSERT INTO users (name, email, password_hash, is_admin)
VALUES ('Admin', 'admin@nicetees.com',
        '$2y$10$4qOC6z3wWkbTlETzMj1tF.uJjH0VBImEUmo9I1kylgqrzn5ECT/NG', 1);

-- Sample products
INSERT INTO products (name, description, price, image, category) VALUES
('Classic White Tee', 'Simple, comfortable white T-shirt.', 19.99, 'white-tee.jpg', 'Classic'),
('Nice-Tees Logo Tee', 'Show off your love for Nice-Tees.', 24.99, 'logo-tee.jpg', 'Logo'),
('Black Minimal Tee', 'Clean black tee with minimal design.', 21.99, 'black-tee.jpg', 'Classic');
