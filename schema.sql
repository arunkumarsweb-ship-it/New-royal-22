-- Saree Availability Website Database Schema

CREATE DATABASE IF NOT EXISTS saree_availability;
USE saree_availability;

-- Categories table (Main menu items)
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Subcategories table (30 days for each category)
CREATE TABLE IF NOT EXISTS subcategories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    day_number INT NOT NULL CHECK (day_number >= 1 AND day_number <= 30),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_category_day (category_id, day_number),
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subcategory_id INT NOT NULL,
    product_number INT NOT NULL,
    image_path VARCHAR(500) NOT NULL,
    availability_status ENUM('available', 'unavailable', 'hold') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_subcategory_product (subcategory_id, product_number),
    FOREIGN KEY (subcategory_id) REFERENCES subcategories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default categories
INSERT INTO categories (name) VALUES
('Poonam Sarees Without Blouse'),
('Poonam Sarees With Blouse'),
('Farak Sarees With Blouse'),
('Farak Sarees Without Blouse'),
('Designer Sarees'),
('Accessories'),
('Others')
ON DUPLICATE KEY UPDATE name=name;

-- Create subcategories (30 days) for each category
-- This will create Day 1-30 for all 7 categories
INSERT INTO subcategories (category_id, day_number)
SELECT c.id, d.day_num
FROM categories c
CROSS JOIN (
    SELECT 1 as day_num UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION
    SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION
    SELECT 11 UNION SELECT 12 UNION SELECT 13 UNION SELECT 14 UNION SELECT 15 UNION
    SELECT 16 UNION SELECT 17 UNION SELECT 18 UNION SELECT 19 UNION SELECT 20 UNION
    SELECT 21 UNION SELECT 22 UNION SELECT 23 UNION SELECT 24 UNION SELECT 25 UNION
    SELECT 26 UNION SELECT 27 UNION SELECT 28 UNION SELECT 29 UNION SELECT 30
) d
ON DUPLICATE KEY UPDATE day_number=day_number;

