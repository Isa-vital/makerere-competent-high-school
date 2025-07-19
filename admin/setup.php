<?php
// Database setup for Makerere Competent High School Admin
require_once '../includes/config.php';

// Create tables if they don't exist
try {
    // Admin users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS admin_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_login TIMESTAMP NULL
    )");

    // News table
    $pdo->exec("CREATE TABLE IF NOT EXISTS news (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        slug VARCHAR(255) UNIQUE NOT NULL,
        excerpt TEXT,
        content LONGTEXT NOT NULL,
        featured_image VARCHAR(255),
        author VARCHAR(100),
        status ENUM('draft', 'published') DEFAULT 'draft',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");

    // Gallery table
    $pdo->exec("CREATE TABLE IF NOT EXISTS gallery (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        image_path VARCHAR(255) NOT NULL,
        category VARCHAR(100),
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Contact messages table
    $pdo->exec("CREATE TABLE IF NOT EXISTS contact_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        subject VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        status ENUM('new', 'read', 'replied') DEFAULT 'new',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Settings table
    $pdo->exec("CREATE TABLE IF NOT EXISTS site_settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(100) UNIQUE NOT NULL,
        setting_value TEXT,
        setting_type ENUM('text', 'textarea', 'number', 'email', 'url') DEFAULT 'text',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");

    // Activity log table
    $pdo->exec("CREATE TABLE IF NOT EXISTS activity_log (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        action VARCHAR(100) NOT NULL,
        description TEXT,
        ip_address VARCHAR(45),
        user_agent TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE SET NULL
    )");

    // Create default admin user if none exists
    $stmt = $pdo->query("SELECT COUNT(*) FROM admin_users");
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        $defaultPassword = hashPassword('admin123');
        $stmt = $pdo->prepare("INSERT INTO admin_users (username, password, full_name, email) VALUES (?, ?, ?, ?)");
        $stmt->execute(['admin', $defaultPassword, 'Administrator', 'admin@makererecompetent.edu.ug']);
    }

    // Insert default settings
    $defaultSettings = [
        ['site_title', 'Makerere Competent High School', 'text'],
        ['site_description', 'Excellence in Education', 'textarea'],
        ['students_count', '1200', 'number'],
        ['teachers_count', '45', 'number'],
        ['established_year', '1998', 'number'],
        ['contact_phone', '+256 414 532 123', 'text'],
        ['contact_email', 'info@makererecompetent.edu.ug', 'email'],
        ['facebook_url', 'https://facebook.com/makererecompetent', 'url'],
        ['twitter_url', 'https://twitter.com/makererecompetent', 'url'],
        ['instagram_url', 'https://instagram.com/makererecompetent', 'url']
    ];

    foreach ($defaultSettings as $setting) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO site_settings (setting_key, setting_value, setting_type) VALUES (?, ?, ?)");
        $stmt->execute($setting);
    }

    echo "Database setup completed successfully!<br>";
    echo "Default admin credentials:<br>";
    echo "Username: admin<br>";
    echo "Password: admin123<br>";
    echo "<strong>Please change the default password after login!</strong><br><br>";
    echo "<a href='login.php'>Go to Admin Login</a>";

} catch (PDOException $e) {
    echo "Database setup failed: " . $e->getMessage();
}
?>
