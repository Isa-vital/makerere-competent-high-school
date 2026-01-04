-- ============================================
-- Makerere Competent High School Database
-- Creation Script for MySQL Workbench
-- ============================================

-- Create database
CREATE DATABASE IF NOT EXISTS school_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- Use the database
USE school_db;

-- ============================================
-- Table: news
-- ============================================
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    excerpt TEXT,
    image VARCHAR(255),
    author VARCHAR(100),
    status ENUM('draft', 'published') DEFAULT 'draft',
    featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_featured (featured),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: gallery
-- ============================================
CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255) NOT NULL,
    category ENUM('academics', 'sports', 'events', 'facilities', 'students', 'other') DEFAULT 'other',
    sort_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_status (status),
    INDEX idx_sort_order (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: contact_messages
-- ============================================
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    phone VARCHAR(20),
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_created_at (created_at),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: admin_users
-- ============================================
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    full_name VARCHAR(100),
    role ENUM('admin', 'editor') DEFAULT 'editor',
    status ENUM('active', 'inactive') DEFAULT 'active',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: site_settings
-- ============================================
CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) UNIQUE NOT NULL,
    setting_value TEXT,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_setting_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Insert Default Admin User
-- Username: admin
-- Password: admin123
-- ============================================
INSERT INTO admin_users (username, password, email, full_name, role, status)
VALUES (
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: admin123
    'admin@makererecompetenthighschool.com',
    'Administrator',
    'admin',
    'active'
);

-- ============================================
-- Insert Default Site Settings
-- ============================================
INSERT INTO site_settings (setting_key, setting_value, description) VALUES
('school_motto', 'Quality Education for a Better Future', 'School motto'),
('school_vision', 'To be the leading educational institution in Uganda', 'School vision'),
('school_mission', 'To provide quality education and nurture future leaders', 'School mission'),
('students_count', '1200', 'Total number of students'),
('teachers_count', '85', 'Total number of teachers'),
('years_experience', '25', 'Years of experience'),
('graduation_rate', '98', 'Graduation rate percentage');

-- ============================================
-- Verification Queries
-- ============================================
-- Run these queries to verify the setup:
-- SELECT * FROM admin_users;
-- SELECT * FROM site_settings;
-- SHOW TABLES;
-- DESCRIBE news;
-- DESCRIBE gallery;
-- DESCRIBE contact_messages;
-- DESCRIBE admin_users;
-- DESCRIBE site_settings;

-- ============================================
-- Success Message
-- ============================================
SELECT 'Database created successfully!' AS Status;
SELECT COUNT(*) AS 'Total Tables' FROM information_schema.tables 
WHERE table_schema = 'school_db';
