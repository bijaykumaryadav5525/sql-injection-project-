-- SQL Database Schema for SQL Injection Project
-- Create database
CREATE DATABASE IF NOT EXISTS injectable_db;
USE injectable_db;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user'
);

-- Products Table
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    category VARCHAR(50),
    price DECIMAL(10, 2)
);

-- Insert Test Users
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@securelab.com', 'admin123', 'admin'),
('john', 'john@example.com', 'john123', 'user'),
('sarah', 'sarah@example.com', 'sarah123', 'user');

-- Insert Test Products
INSERT INTO products (name, description, category, price) VALUES
('Laptop Pro', 'High performance laptop with 16GB RAM', 'Electronics', 134999),
('Wireless Mouse', 'Ergonomic wireless mouse with USB receiver', 'Accessories', 1299),
('USB Cable', 'Universal USB 3.0 type-C connector cable', 'Cables', 299);

-- Display data to verify
SELECT * FROM users;
SELECT * FROM products;
