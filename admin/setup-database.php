<?php
// Database Setup Script for Makerere Competent High School
require_once '../includes/config.php';

// Check if user is admin (for security)
session_start();
if (!isset($_GET['setup_key']) || $_GET['setup_key'] !== 'competent_setup_2025') {
    die('Unauthorized access. Add ?setup_key=competent_setup_2025 to the URL');
}

echo "<h1>Setting up Database for Makerere Competent High School</h1>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:green;} .error{color:red;} .info{color:blue;}</style>";

try {
    // Create admin users table
    $sql = "CREATE TABLE IF NOT EXISTS admin_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        role ENUM('admin', 'super_admin') DEFAULT 'admin',
        status ENUM('active', 'inactive') DEFAULT 'active',
        last_login DATETIME NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "<p class='success'>✅ Created admin_users table</p>";

    // Create news table
    $sql = "CREATE TABLE IF NOT EXISTS news (
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
        published_at DATETIME NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (author_id) REFERENCES admin_users(id) ON DELETE SET NULL
    )";
    $pdo->exec($sql);
    echo "<p class='success'>✅ Created news table</p>";

    // Create gallery table
    $sql = "CREATE TABLE IF NOT EXISTS gallery (
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
        uploaded_by INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (uploaded_by) REFERENCES admin_users(id) ON DELETE SET NULL
    )";
    $pdo->exec($sql);
    echo "<p class='success'>✅ Created gallery table</p>";

    // Create contact messages table
    $sql = "CREATE TABLE IF NOT EXISTS contact_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        subject VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        status ENUM('new', 'read', 'replied', 'archived') DEFAULT 'new',
        ip_address VARCHAR(45),
        user_agent TEXT,
        replied_at DATETIME NULL,
        replied_by INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (replied_by) REFERENCES admin_users(id) ON DELETE SET NULL
    )";
    $pdo->exec($sql);
    echo "<p class='success'>✅ Created contact_messages table</p>";

    // Create alumni table
    $sql = "CREATE TABLE IF NOT EXISTS alumni (
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
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "<p class='success'>✅ Created alumni table</p>";

    // Create admissions table
    $sql = "CREATE TABLE IF NOT EXISTS admissions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_name VARCHAR(100) NOT NULL,
        parent_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        address TEXT NOT NULL,
        date_of_birth DATE NOT NULL,
        gender ENUM('male', 'female') NOT NULL,
        previous_school VARCHAR(100),
        class_applying VARCHAR(50) NOT NULL,
        documents_path VARCHAR(255),
        application_fee_paid BOOLEAN DEFAULT FALSE,
        status ENUM('pending', 'approved', 'rejected', 'enrolled') DEFAULT 'pending',
        notes TEXT,
        processed_by INT,
        processed_at DATETIME NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (processed_by) REFERENCES admin_users(id) ON DELETE SET NULL
    )";
    $pdo->exec($sql);
    echo "<p class='success'>✅ Created admissions table</p>";

    // Create site settings table
    $sql = "CREATE TABLE IF NOT EXISTS site_settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(100) UNIQUE NOT NULL,
        setting_value TEXT,
        setting_type ENUM('text', 'textarea', 'number', 'boolean', 'email', 'url') DEFAULT 'text',
        category VARCHAR(50) DEFAULT 'general',
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "<p class='success'>✅ Created site_settings table</p>";

    // Create activity log table
    $sql = "CREATE TABLE IF NOT EXISTS activity_log (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        action VARCHAR(100) NOT NULL,
        description TEXT,
        ip_address VARCHAR(45),
        user_agent TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE SET NULL
    )";
    $pdo->exec($sql);
    echo "<p class='success'>✅ Created activity_log table</p>";

    // Create academic programs table
    $sql = "CREATE TABLE IF NOT EXISTS academic_programs (
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
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "<p class='success'>✅ Created academic_programs table</p>";

    // Create teachers table
    $sql = "CREATE TABLE IF NOT EXISTS teachers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE,
        phone VARCHAR(20),
        employee_id VARCHAR(50) UNIQUE,
        qualification VARCHAR(255),
        specialization VARCHAR(100),
        subjects_taught TEXT,
        experience_years INT,
        profile_photo VARCHAR(255),
        bio TEXT,
        status ENUM('active', 'inactive', 'retired') DEFAULT 'active',
        hire_date DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "<p class='success'>✅ Created teachers table</p>";

    // Create events table
    $sql = "CREATE TABLE IF NOT EXISTS events (
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
        created_by INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
    )";
    $pdo->exec($sql);
    echo "<p class='success'>✅ Created events table</p>";

    // Insert default admin user
    $defaultPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $sql = "INSERT IGNORE INTO admin_users (username, email, password, full_name, role) 
            VALUES ('admin', 'admin@competent.ac.ug', ?, 'System Administrator', 'super_admin')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$defaultPassword]);
    echo "<p class='success'>✅ Created default admin user (username: admin, password: admin123)</p>";

    // Insert default site settings
    $defaultSettings = [
        ['school_name', 'Makerere Competent High School', 'general'],
        ['school_motto', 'Quality Education for A Bright Future', 'general'],
        ['school_phone', '+256 123 456 789', 'contact'],
        ['school_email', 'info@competent.ac.ug', 'contact'],
        ['school_address', 'Makerere Hill, Kampala, Uganda', 'contact'],
        ['facebook_url', 'https://facebook.com/competenthighschool', 'social'],
        ['twitter_url', 'https://twitter.com/competentschool', 'social'],
        ['instagram_url', 'https://instagram.com/competentschool', 'social'],
        ['youtube_url', 'https://youtube.com/competentschool', 'social'],
        ['school_mission', 'To provide quality education and nurture future leaders through excellence in teaching, character development, and community service.', 'about'],
        ['school_vision', 'To be the leading institution in academic excellence and character development in Uganda.', 'about'],
        ['principal_message', 'Welcome to Makerere Competent High School, where we believe every student has the potential for greatness.', 'about']
    ];

    foreach ($defaultSettings as $setting) {
        $sql = "INSERT IGNORE INTO site_settings (setting_key, setting_value, category) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($setting);
    }
    echo "<p class='success'>✅ Inserted default site settings</p>";

    // Insert sample academic programs
    $programs = [
        ['O-Level Sciences', 'O-SCI', 'O-Level', 'Science subjects including Mathematics, Physics, Chemistry, Biology', '4 years', 'Mathematics, Physics, Chemistry, Biology, English, Literature, History, Geography', 'Minimum 7 subjects at PLE', 500000],
        ['O-Level Arts', 'O-ART', 'O-Level', 'Arts and humanities subjects', '4 years', 'English, Literature, History, Geography, Religious Studies, French, Economics', 'Minimum 7 subjects at PLE', 450000],
        ['A-Level PCM', 'A-PCM', 'A-Level', 'Physics, Chemistry, Mathematics combination', '2 years', 'Physics, Chemistry, Mathematics, General Paper', 'Minimum 5 O-Level credits including Math and English', 800000],
        ['A-Level HEG', 'A-HEG', 'A-Level', 'History, Economics, Geography combination', '2 years', 'History, Economics, Geography, General Paper', 'Minimum 5 O-Level credits including English', 750000]
    ];

    foreach ($programs as $program) {
        $sql = "INSERT IGNORE INTO academic_programs (program_name, program_code, level, description, duration, subjects, requirements, fees) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($program);
    }
    echo "<p class='success'>✅ Inserted sample academic programs</p>";

    // Insert sample news articles
    $newsArticles = [
        [
            'Welcome to New Academic Year 2025',
            'welcome-new-academic-year-2025',
            'We are excited to welcome all students back for the 2025 academic year. This year promises to be filled with new opportunities and achievements.',
            'Welcome back message for 2025 academic year',
            'Academic',
            1,
            'published',
            1,
            date('Y-m-d H:i:s')
        ],
        [
            'Science Fair 2025 - Outstanding Student Projects',
            'science-fair-2025-outstanding-projects',
            'Our annual science fair showcased incredible projects from students across all levels. The creativity and scientific thinking displayed was truly impressive.',
            'Annual science fair highlights student achievements',
            'Events',
            1,
            'published',
            0,
            date('Y-m-d H:i:s', strtotime('-5 days'))
        ]
    ];

    foreach ($newsArticles as $article) {
        $sql = "INSERT IGNORE INTO news (title, slug, content, excerpt, category, author_id, status, featured, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($article);
    }
    echo "<p class='success'>✅ Inserted sample news articles</p>";

    echo "<h2 class='success'>🎉 Database Setup Complete!</h2>";
    echo "<div class='info'>";
    echo "<h3>Next Steps:</h3>";
    echo "<ol>";
    echo "<li><strong>Admin Login:</strong> Go to <a href='login.php'>admin/login.php</a></li>";
    echo "<li><strong>Username:</strong> admin</li>";
    echo "<li><strong>Password:</strong> admin123</li>";
    echo "<li><strong>Security:</strong> Change the default password immediately!</li>";
    echo "<li><strong>Upload Directory:</strong> Ensure 'assets/images/uploads/' has write permissions</li>";
    echo "</ol>";
    echo "</div>";

    // Create uploads directory
    $uploadDir = '../assets/images/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
        echo "<p class='success'>✅ Created uploads directory</p>";
    }

} catch (PDOException $e) {
    echo "<p class='error'>❌ Database Error: " . $e->getMessage() . "</p>";
}
?>
