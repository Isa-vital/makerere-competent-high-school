<?php
// Database configuration for Makerere Competent High School website
// Update these settings according to your hosting environment

// Database Settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'competent_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Site Settings
define('SITE_NAME', 'Makerere Competent High School');
define('SITE_URL', 'http://localhost/competent');
define('SITE_EMAIL', 'info@makererecompetent.edu.ug');
define('SITE_PHONE', '+256 414 532 123');
define('SITE_ADDRESS', 'Buhimba, Kikuube, Uganda');

// File Upload Settings
define('UPLOAD_PATH', 'assets/images/uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_IMAGE_TYPES', array('jpg', 'jpeg', 'png', 'gif'));

// Admin Settings
define('ADMIN_EMAIL', 'admin@makererecompetent.edu.ug');
define('SESSION_TIMEOUT', 3600); // 1 hour

// Security Settings
define('ENCRYPTION_KEY', 'your-secret-key-here-change-this');
define('PASSWORD_MIN_LENGTH', 8);

// Social Media Links
define('FACEBOOK_URL', 'https://facebook.com/makererecompetent');
define('TWITTER_URL', 'https://twitter.com/makererecompetent');
define('INSTAGRAM_URL', 'https://instagram.com/makererecompetent');
define('YOUTUBE_URL', 'https://youtube.com/makererecompetent');

// Error Reporting (Set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Africa/Kampala');

// Database Connection
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // In production, log this error instead of displaying it
    die("Database connection failed: " . $e->getMessage());
}

// Auto-create database tables if they don't exist
createTables($pdo);

function createTables($pdo) {
    // News table
    $newsTable = "
        CREATE TABLE IF NOT EXISTS news (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            excerpt TEXT,
            image VARCHAR(255),
            author VARCHAR(100),
            status ENUM('draft', 'published') DEFAULT 'draft',
            featured BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ";
    
    // Gallery table
    $galleryTable = "
        CREATE TABLE IF NOT EXISTS gallery (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            image VARCHAR(255) NOT NULL,
            category ENUM('academics', 'sports', 'events', 'facilities', 'students', 'other') DEFAULT 'other',
            sort_order INT DEFAULT 0,
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";
    
    // Contact messages table
    $contactTable = "
        CREATE TABLE IF NOT EXISTS contact_messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            subject VARCHAR(255),
            message TEXT NOT NULL,
            phone VARCHAR(20),
            status ENUM('new', 'read', 'replied') DEFAULT 'new',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";
    
    // Admin users table
    $adminTable = "
        CREATE TABLE IF NOT EXISTS admin_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL,
            full_name VARCHAR(100),
            role ENUM('admin', 'editor') DEFAULT 'editor',
            status ENUM('active', 'inactive') DEFAULT 'active',
            last_login TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";
    
    // Site settings table
    $settingsTable = "
        CREATE TABLE IF NOT EXISTS site_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(50) UNIQUE NOT NULL,
            setting_value TEXT,
            description TEXT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ";
    
    try {
        $pdo->exec($newsTable);
        $pdo->exec($galleryTable);
        $pdo->exec($contactTable);
        $pdo->exec($adminTable);
        $pdo->exec($settingsTable);
        
        // Insert default admin user if no users exist
        $stmt = $pdo->query("SELECT COUNT(*) FROM admin_users");
        if ($stmt->fetchColumn() == 0) {
            $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
            $pdo->exec("INSERT INTO admin_users (username, password, email, full_name, role) 
                       VALUES ('admin', '$hashedPassword', 'admin@makererecompetent.edu.ug', 'Administrator', 'admin')");
        }
        
        // Insert default site settings
        $defaultSettings = [
            ['school_motto', 'Quality Education for a Better Future', 'School motto'],
            ['school_vision', 'To be the leading educational institution in Uganda', 'School vision'],
            ['school_mission', 'To provide quality education and nurture future leaders', 'School mission'],
            ['students_count', '1200', 'Total number of students'],
            ['teachers_count', '85', 'Total number of teachers'],
            ['years_experience', '25', 'Years of experience'],
            ['graduation_rate', '98', 'Graduation rate percentage']
        ];
        
        foreach ($defaultSettings as $setting) {
            $stmt = $pdo->prepare("INSERT IGNORE INTO site_settings (setting_key, setting_value, description) VALUES (?, ?, ?)");
            $stmt->execute($setting);
        }
        
    } catch (PDOException $e) {
        error_log("Error creating tables: " . $e->getMessage());
    }
}

// Function to get site setting
function getSetting($key, $default = '') {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetchColumn();
        return $result ? $result : $default;
    } catch (PDOException $e) {
        return $default;
    }
}

// Function to update site setting
function updateSetting($key, $value) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
        return $stmt->execute([$value, $key]);
    } catch (PDOException $e) {
        return false;
    }
}
?>
