<?php
/**
 * Safe Database Recreation Script for Makerere Competent High School
 * This script safely recreates all database tables without dropping the database
 * 
 * Usage: Add ?safe_recreate=competent_2025_safe to the URL to run this script
 */

// Security check
if (!isset($_GET['safe_recreate']) || $_GET['safe_recreate'] !== 'competent_2025_safe') {
    die('
    <h1>Safe Database Recreation Script</h1>
    <p style="color: red; font-weight: bold;">Unauthorized access!</p>
    <p>To run this script, add <code>?safe_recreate=competent_2025_safe</code> to the URL</p>
    <p><strong>INFO:</strong> This script safely recreates tables without dropping the database!</p>
    ');
}

// Include config for database connection
require_once 'includes/config.php';

// Set proper styling
echo "
<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
    .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .success { color: #28a745; margin: 10px 0; }
    .error { color: #dc3545; margin: 10px 0; }
    .warning { color: #ffc107; margin: 10px 0; }
    .info { color: #17a2b8; margin: 10px 0; }
    .section { background: #f8f9fa; padding: 15px; margin: 20px 0; border-left: 4px solid #007bff; }
    .step { background: #e9ecef; padding: 10px; margin: 5px 0; border-radius: 4px; }
    h1 { color: #333; border-bottom: 3px solid #007bff; padding-bottom: 10px; }
    h2 { color: #555; margin-top: 30px; }
    .highlight { background: #fff3cd; padding: 10px; border: 1px solid #ffeaa7; border-radius: 4px; margin: 10px 0; }
</style>
";

echo '<div class="container">';
echo '<h1>🏫 Makerere Competent High School - Safe Database Recreation</h1>';
echo '<div class="info"><strong>ℹ️ SAFE MODE:</strong> This will recreate tables without dropping the database!</div>';

try {
    // Connect to existing database using the config connection
    echo '<div class="step">📊 Using existing database connection</div>';
    
    // =================================================================================
    // DROP ALL EXISTING TABLES SAFELY
    // =================================================================================
    
    echo '<h2>🗑️ Cleaning Existing Tables</h2>';
    
    // Disable foreign key checks temporarily
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    echo '<div class="step">🔓 Disabled foreign key checks</div>';
    
    // Get all tables in the database
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (!empty($tables)) {
        // Drop all existing tables
        foreach ($tables as $table) {
            $pdo->exec("DROP TABLE IF EXISTS `$table`");
            echo '<div class="success">✅ Dropped table: ' . $table . '</div>';
        }
        echo '<div class="success">✅ All existing tables dropped successfully</div>';
    } else {
        echo '<div class="info">ℹ️ No existing tables found</div>';
    }
    
    // Re-enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    echo '<div class="step">🔒 Re-enabled foreign key checks</div>';
    
    // =================================================================================
    // CREATE ALL TABLES
    // =================================================================================
    
    echo '<h2>📋 Creating Database Tables</h2>';
    
    // 1. Admin Users Table
    $sql = "CREATE TABLE admin_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        role ENUM('admin', 'super_admin') DEFAULT 'admin',
        status ENUM('active', 'inactive') DEFAULT 'active',
        last_login DATETIME NULL,
        profile_photo VARCHAR(255) NULL,
        phone VARCHAR(20) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_username (username),
        INDEX idx_email (email),
        INDEX idx_status (status)
    ) ENGINE=InnoDB";
    $pdo->exec($sql);
    echo '<div class="success">✅ Created admin_users table</div>';
    
    // 2. Site Settings Table
    $sql = "CREATE TABLE site_settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(100) UNIQUE NOT NULL,
        setting_value TEXT,
        setting_type ENUM('text', 'textarea', 'number', 'boolean', 'email', 'url', 'image') DEFAULT 'text',
        category VARCHAR(50) DEFAULT 'general',
        description TEXT,
        is_public BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_category (category),
        INDEX idx_key (setting_key)
    ) ENGINE=InnoDB";
    $pdo->exec($sql);
    echo '<div class="success">✅ Created site_settings table</div>';
    
    // 3. News Table
    $sql = "CREATE TABLE news (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        slug VARCHAR(255) UNIQUE NOT NULL,
        content TEXT NOT NULL,
        excerpt TEXT,
        featured_image VARCHAR(255),
        category VARCHAR(100),
        tags TEXT,
        author_id INT,
        status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
        featured BOOLEAN DEFAULT FALSE,
        views INT DEFAULT 0,
        meta_title VARCHAR(60),
        meta_description VARCHAR(160),
        published_at DATETIME NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (author_id) REFERENCES admin_users(id) ON DELETE SET NULL,
        INDEX idx_status (status),
        INDEX idx_published (published_at),
        INDEX idx_category (category),
        INDEX idx_slug (slug),
        FULLTEXT idx_search (title, content, excerpt)
    ) ENGINE=InnoDB";
    $pdo->exec($sql);
    echo '<div class="success">✅ Created news table</div>';
    
    // 4. Gallery Table
    $sql = "CREATE TABLE gallery (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        image_path VARCHAR(255) NOT NULL,
        thumbnail_path VARCHAR(255),
        category VARCHAR(100),
        tags TEXT,
        alt_text VARCHAR(255),
        sort_order INT DEFAULT 0,
        status ENUM('active', 'inactive') DEFAULT 'active',
        views INT DEFAULT 0,
        file_size INT,
        dimensions VARCHAR(20),
        uploaded_by INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (uploaded_by) REFERENCES admin_users(id) ON DELETE SET NULL,
        INDEX idx_status (status),
        INDEX idx_category (category),
        INDEX idx_sort (sort_order)
    ) ENGINE=InnoDB";
    $pdo->exec($sql);
    echo '<div class="success">✅ Created gallery table</div>';
    
    // 5. Contact Messages Table
    $sql = "CREATE TABLE contact_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        subject VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        status ENUM('new', 'read', 'replied', 'archived') DEFAULT 'new',
        priority ENUM('low', 'normal', 'high', 'urgent') DEFAULT 'normal',
        ip_address VARCHAR(45),
        user_agent TEXT,
        replied_at DATETIME NULL,
        replied_by INT,
        reply_message TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (replied_by) REFERENCES admin_users(id) ON DELETE SET NULL,
        INDEX idx_status (status),
        INDEX idx_created (created_at),
        INDEX idx_email (email)
    ) ENGINE=InnoDB";
    $pdo->exec($sql);
    echo '<div class="success">✅ Created contact_messages table</div>';
    
    // 6. Alumni Table (MACOSA)
    $sql = "CREATE TABLE alumni (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        phone VARCHAR(20),
        graduation_year YEAR NOT NULL,
        course_studied VARCHAR(100),
        current_profession VARCHAR(100),
        current_employer VARCHAR(100),
        location VARCHAR(100),
        linkedin_profile VARCHAR(255),
        bio TEXT,
        profile_photo VARCHAR(255),
        achievements TEXT,
        willing_to_mentor BOOLEAN DEFAULT FALSE,
        status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
        approved_by INT,
        approved_at DATETIME NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (approved_by) REFERENCES admin_users(id) ON DELETE SET NULL,
        INDEX idx_status (status),
        INDEX idx_year (graduation_year),
        INDEX idx_email (email)
    ) ENGINE=InnoDB";
    $pdo->exec($sql);
    echo '<div class="success">✅ Created alumni table</div>';
    
    // 7. Admissions Table
    $sql = "CREATE TABLE admissions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        application_number VARCHAR(20) UNIQUE NOT NULL,
        student_name VARCHAR(100) NOT NULL,
        parent_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        address TEXT NOT NULL,
        date_of_birth DATE NOT NULL,
        gender ENUM('male', 'female') NOT NULL,
        previous_school VARCHAR(100),
        class_applying VARCHAR(50) NOT NULL,
        academic_year VARCHAR(10) NOT NULL,
        documents_path VARCHAR(255),
        application_fee_paid BOOLEAN DEFAULT FALSE,
        payment_reference VARCHAR(100),
        status ENUM('pending', 'approved', 'rejected', 'enrolled') DEFAULT 'pending',
        notes TEXT,
        processed_by INT,
        processed_at DATETIME NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (processed_by) REFERENCES admin_users(id) ON DELETE SET NULL,
        INDEX idx_status (status),
        INDEX idx_application_number (application_number),
        INDEX idx_created (created_at)
    ) ENGINE=InnoDB";
    $pdo->exec($sql);
    echo '<div class="success">✅ Created admissions table</div>';
    
    // 8. Academic Programs Table
    $sql = "CREATE TABLE academic_programs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        program_name VARCHAR(100) NOT NULL,
        program_code VARCHAR(20) UNIQUE NOT NULL,
        level ENUM('O-Level', 'A-Level') NOT NULL,
        description TEXT,
        duration VARCHAR(50),
        subjects TEXT,
        requirements TEXT,
        fees DECIMAL(10,2),
        status ENUM('active', 'inactive') DEFAULT 'active',
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_level (level),
        INDEX idx_status (status),
        INDEX idx_code (program_code)
    ) ENGINE=InnoDB";
    $pdo->exec($sql);
    echo '<div class="success">✅ Created academic_programs table</div>';
    
    // 9. Teachers Table
    $sql = "CREATE TABLE teachers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        employee_id VARCHAR(50) UNIQUE,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE,
        phone VARCHAR(20),
        qualification VARCHAR(255),
        specialization VARCHAR(100),
        subjects_taught TEXT,
        experience_years INT,
        profile_photo VARCHAR(255),
        bio TEXT,
        status ENUM('active', 'inactive', 'retired') DEFAULT 'active',
        hire_date DATE,
        department VARCHAR(100),
        position VARCHAR(100),
        salary DECIMAL(10,2),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_status (status),
        INDEX idx_employee_id (employee_id),
        INDEX idx_department (department)
    ) ENGINE=InnoDB";
    $pdo->exec($sql);
    echo '<div class="success">✅ Created teachers table</div>';
    
    // 10. Events Table
    $sql = "CREATE TABLE events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        event_date DATE NOT NULL,
        start_time TIME,
        end_time TIME,
        location VARCHAR(255),
        category VARCHAR(100),
        featured_image VARCHAR(255),
        status ENUM('upcoming', 'ongoing', 'completed', 'cancelled') DEFAULT 'upcoming',
        registration_required BOOLEAN DEFAULT FALSE,
        max_participants INT,
        current_participants INT DEFAULT 0,
        registration_fee DECIMAL(8,2) DEFAULT 0.00,
        created_by INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL,
        INDEX idx_status (status),
        INDEX idx_date (event_date),
        INDEX idx_category (category)
    ) ENGINE=InnoDB";
    $pdo->exec($sql);
    echo '<div class="success">✅ Created events table</div>';
    
    // 11. Activity Log Table
    $sql = "CREATE TABLE activity_log (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        action VARCHAR(100) NOT NULL,
        description TEXT,
        table_affected VARCHAR(50),
        record_id INT,
        old_values JSON,
        new_values JSON,
        ip_address VARCHAR(45),
        user_agent TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE SET NULL,
        INDEX idx_user (user_id),
        INDEX idx_action (action),
        INDEX idx_created (created_at)
    ) ENGINE=InnoDB";
    $pdo->exec($sql);
    echo '<div class="success">✅ Created activity_log table</div>';
    
    // 12. Students Table (for enrollment tracking)
    $sql = "CREATE TABLE students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id VARCHAR(20) UNIQUE NOT NULL,
        admission_id INT,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(100),
        phone VARCHAR(20),
        parent_name VARCHAR(100),
        parent_phone VARCHAR(20),
        parent_email VARCHAR(100),
        address TEXT,
        date_of_birth DATE,
        gender ENUM('male', 'female'),
        class VARCHAR(50),
        academic_year VARCHAR(10),
        enrollment_date DATE,
        status ENUM('active', 'graduated', 'transferred', 'expelled') DEFAULT 'active',
        profile_photo VARCHAR(255),
        medical_conditions TEXT,
        emergency_contact TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (admission_id) REFERENCES admissions(id) ON DELETE SET NULL,
        INDEX idx_student_id (student_id),
        INDEX idx_status (status),
        INDEX idx_class (class)
    ) ENGINE=InnoDB";
    $pdo->exec($sql);
    echo '<div class="success">✅ Created students table</div>';
    
    // =================================================================================
    // INSERT SAMPLE DATA
    // =================================================================================
    
    echo '<h2>📊 Populating Database with Sample Data</h2>';
    
    // Insert default admin user
    $defaultPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $sql = "INSERT INTO admin_users (username, email, password, full_name, role) 
            VALUES ('admin', 'admin@competent.ac.ug', ?, 'System Administrator', 'super_admin')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$defaultPassword]);
    echo '<div class="success">✅ Created default admin user (username: admin, password: admin123)</div>';
    
    // Insert site settings
    $defaultSettings = [
        ['school_name', 'Makerere Competent High School', 'text', 'general', 'Official school name'],
        ['school_motto', 'Quality Education for A Bright Future', 'text', 'general', 'School motto'],
        ['school_phone', '+256 414 532 123', 'text', 'contact', 'Primary phone number'],
        ['school_email', 'info@makererecompetent.edu.ug', 'email', 'contact', 'Primary email address'],
        ['school_address', 'Buhimba, Kikuube, Uganda', 'text', 'contact', 'Physical address'],
        ['facebook_url', 'https://facebook.com/competenthighschool', 'url', 'social', 'Facebook page URL'],
        ['twitter_url', 'https://twitter.com/competentschool', 'url', 'social', 'Twitter profile URL'],
        ['instagram_url', 'https://instagram.com/competentschool', 'url', 'social', 'Instagram profile URL'],
        ['youtube_url', 'https://youtube.com/competentschool', 'url', 'social', 'YouTube channel URL'],
        ['school_mission', 'To provide quality education and nurture future leaders through excellence in teaching, character development, and community service.', 'textarea', 'about', 'School mission statement'],
        ['school_vision', 'To be the leading institution in academic excellence and character development in Uganda.', 'textarea', 'about', 'School vision statement'],
        ['principal_message', 'Welcome to Makerere Competent High School, where we believe every student has the potential for greatness.', 'textarea', 'about', 'Message from the principal']
    ];

    foreach ($defaultSettings as $setting) {
        $sql = "INSERT INTO site_settings (setting_key, setting_value, setting_type, category, description) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($setting);
    }
    echo '<div class="success">✅ Inserted ' . count($defaultSettings) . ' site settings</div>';
    
    // Insert sample news articles
    $newsArticles = [
        [
            'Welcome to Academic Year 2025',
            'welcome-academic-year-2025',
            'We are excited to welcome all students back for the 2025 academic year.',
            'Welcome back message for the 2025 academic year.',
            '/assets/images/news/welcome-2025.jpg',
            'Academic',
            'academic year, welcome, 2025',
            1,
            'published',
            1,
            'Welcome to Academic Year 2025',
            'Official welcome message for the 2025 academic year.',
            date('Y-m-d H:i:s')
        ]
    ];

    foreach ($newsArticles as $article) {
        $sql = "INSERT INTO news (title, slug, content, excerpt, featured_image, category, tags, author_id, status, featured, meta_title, meta_description, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($article);
    }
    echo '<div class="success">✅ Inserted ' . count($newsArticles) . ' news articles</div>';
    
    // Create upload directories
    $uploadDirs = [
        'assets/images/uploads/',
        'assets/images/uploads/gallery/',
        'assets/images/uploads/news/',
        'assets/images/uploads/events/',
        'assets/images/uploads/alumni/',
        'assets/images/uploads/teachers/',
        'assets/images/uploads/students/',
        'assets/images/uploads/documents/'
    ];
    
    foreach ($uploadDirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }
    echo '<div class="success">✅ Created upload directories</div>';
    
    // Success message
    echo '<div class="section">';
    echo '<h2>🎉 Safe Database Recreation Complete!</h2>';
    echo '<div class="highlight">';
    echo '<h3>📋 Summary:</h3>';
    echo '<ul>';
    echo '<li><strong>Database:</strong> ' . DB_NAME . ' (safely recreated)</li>';
    echo '<li><strong>Tables Created:</strong> 12 comprehensive tables</li>';
    echo '<li><strong>Sample Data:</strong> Populated with basic sample data</li>';
    echo '<li><strong>Upload Directories:</strong> Created and configured</li>';
    echo '</ul>';
    echo '</div>';
    
    echo '<div class="highlight">';
    echo '<h3>🔐 Admin Access:</h3>';
    echo '<ul>';
    echo '<li><strong>Admin URL:</strong> <a href="admin/login.php" target="_blank">admin/login.php</a></li>';
    echo '<li><strong>Username:</strong> <code>admin</code></li>';
    echo '<li><strong>Password:</strong> <code>admin123</code></li>';
    echo '</ul>';
    echo '</div>';
    
    echo '<div class="highlight">';
    echo '<h3>⚠️ Important Next Steps:</h3>';
    echo '<ol>';
    echo '<li><strong>Change Default Password:</strong> Login and change the default password immediately</li>';
    echo '<li><strong>Update Site Settings:</strong> Configure your actual school information</li>';
    echo '<li><strong>Add Content:</strong> Upload your images, news, events, etc.</li>';
    echo '<li><strong>File Permissions:</strong> Ensure upload directories have proper write permissions</li>';
    echo '</ol>';
    echo '</div>';
    echo '</div>';

} catch (PDOException $e) {
    echo '<div class="error">❌ Database Error: ' . $e->getMessage() . '</div>';
    echo '<div class="error">Please check your database configuration in includes/config.php</div>';
} catch (Exception $e) {
    echo '<div class="error">❌ Error: ' . $e->getMessage() . '</div>';
}

echo '</div>';
?>
