<?php
/**
 * Complete Database Recreation Script for Makerere Competent High School
 * This script will recreate the entire database structure and populate it with sample data
 * 
 * IMPORTANT: This will DROP the existing database and recreate it!
 * Use with caution and ensure you have backups if needed.
 * 
 * Usage: Add ?recreate_key=competent_2025_reset to the URL to run this script
 */

// Security check
if (!isset($_GET['recreate_key']) || $_GET['recreate_key'] !== 'competent_2025_reset') {
    die('
    <h1>Database Recreation Script</h1>
    <p style="color: red; font-weight: bold;">Unauthorized access!</p>
    <p>To run this script, add <code>?recreate_key=competent_2025_reset</code> to the URL</p>
    <p><strong>WARNING:</strong> This script will completely recreate the database!</p>
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
echo '<h1>🏫 Makerere Competent High School - Database Recreation</h1>';
echo '<div class="warning"><strong>⚠️ WARNING:</strong> This will completely recreate the database!</div>';

try {
    // First, let's try to connect without selecting a database to create it
    $dsn_no_db = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
    $pdo_admin = new PDO($dsn_no_db, DB_USER, DB_PASS);
    $pdo_admin->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo '<div class="step">📡 Connected to MySQL server</div>';
    
    // Alternative approach: Connect to existing database and drop all tables instead
    echo '<h2>🗄️ Database Recreation</h2>';
    
    try {
        // Try to connect to the existing database
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        echo '<div class="step">📊 Connected to existing database</div>';
        
        // Get all tables in the database
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (!empty($tables)) {
            // Disable foreign key checks temporarily
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
            
            // Drop all existing tables
            foreach ($tables as $table) {
                $pdo->exec("DROP TABLE IF EXISTS `$table`");
                echo '<div class="success">✅ Dropped table: ' . $table . '</div>';
            }
            
            // Re-enable foreign key checks
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
            
            echo '<div class="success">✅ All existing tables dropped successfully</div>';
        } else {
            echo '<div class="info">ℹ️ No existing tables found</div>';
        }
        
    } catch (PDOException $e) {
        // If database doesn't exist, create it
        echo '<div class="info">ℹ️ Database does not exist, creating new one...</div>';
        
        $pdo_admin->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo '<div class="success">✅ Created new database: ' . DB_NAME . '</div>';
        
        // Now connect to the new database
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        echo '<div class="step">📊 Connected to new database</div>';
    }
    
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
    
    // 13. Subjects Table
    $sql = "CREATE TABLE subjects (
        id INT AUTO_INCREMENT PRIMARY KEY,
        subject_name VARCHAR(100) NOT NULL,
        subject_code VARCHAR(20) UNIQUE NOT NULL,
        level ENUM('O-Level', 'A-Level', 'Both') NOT NULL,
        category VARCHAR(50),
        description TEXT,
        credits INT DEFAULT 1,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_level (level),
        INDEX idx_status (status),
        INDEX idx_code (subject_code)
    ) ENGINE=InnoDB";
    $pdo->exec($sql);
    echo '<div class="success">✅ Created subjects table</div>';
    
    // 14. Classes Table
    $sql = "CREATE TABLE classes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        class_name VARCHAR(50) NOT NULL,
        level ENUM('O-Level', 'A-Level') NOT NULL,
        academic_year VARCHAR(10) NOT NULL,
        class_teacher_id INT,
        max_students INT DEFAULT 40,
        current_students INT DEFAULT 0,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (class_teacher_id) REFERENCES teachers(id) ON DELETE SET NULL,
        INDEX idx_level (level),
        INDEX idx_year (academic_year),
        INDEX idx_status (status)
    ) ENGINE=InnoDB";
    $pdo->exec($sql);
    echo '<div class="success">✅ Created classes table</div>';
    
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
    
    // Insert additional admin users
    $adminPassword = password_hash('principal123', PASSWORD_DEFAULT);
    $sql = "INSERT INTO admin_users (username, email, password, full_name, role, phone) 
            VALUES ('principal', 'principal@competent.ac.ug', ?, 'Dr. John Mukasa', 'admin', '+256 701 234 567')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$adminPassword]);
    echo '<div class="success">✅ Created principal user (username: principal, password: principal123)</div>';
    
    // Insert site settings
    $defaultSettings = [
        ['school_name', 'Makerere Competent High School', 'text', 'general', 'Official school name'],
        ['school_motto', 'Quality Education for A Bright Future', 'text', 'general', 'School motto'],
        ['school_phone', '+256 414 532 123', 'text', 'contact', 'Primary phone number'],
        ['school_email', 'info@makererecompetent.edu.ug', 'email', 'contact', 'Primary email address'],
        ['school_address', 'Buhimba, Kikuube, Uganda', 'text', 'contact', 'Physical address'],
        ['postal_address', 'P.O. Box 1234, Kampala, Uganda', 'text', 'contact', 'Postal address'],
        ['facebook_url', 'https://facebook.com/competenthighschool', 'url', 'social', 'Facebook page URL'],
        ['twitter_url', 'https://twitter.com/competentschool', 'url', 'social', 'Twitter profile URL'],
        ['instagram_url', 'https://instagram.com/competentschool', 'url', 'social', 'Instagram profile URL'],
        ['youtube_url', 'https://youtube.com/competentschool', 'url', 'social', 'YouTube channel URL'],
        ['school_mission', 'To provide quality education and nurture future leaders through excellence in teaching, character development, and community service.', 'textarea', 'about', 'School mission statement'],
        ['school_vision', 'To be the leading institution in academic excellence and character development in Uganda.', 'textarea', 'about', 'School vision statement'],
        ['principal_message', 'Welcome to Makerere Competent High School, where we believe every student has the potential for greatness. Our commitment to excellence in education, character development, and holistic growth ensures that our students are well-prepared for the challenges and opportunities of tomorrow.', 'textarea', 'about', 'Message from the principal'],
        ['school_history', 'Founded in 1995, Makerere Competent High School has been at the forefront of quality education in Uganda. Our institution has grown from humble beginnings to become one of the most respected secondary schools in the region.', 'textarea', 'about', 'Brief school history'],
        ['admission_fee_o_level', '500000', 'number', 'fees', 'O-Level admission fee in UGX'],
        ['admission_fee_a_level', '800000', 'number', 'fees', 'A-Level admission fee in UGX'],
        ['school_calendar_url', '/documents/calendar.pdf', 'url', 'academic', 'School calendar document URL'],
        ['school_logo', '/assets/images/competentlogo.jpeg', 'image', 'branding', 'School logo image path'],
        ['site_maintenance', 'false', 'boolean', 'system', 'Site maintenance mode'],
        ['registration_open', 'true', 'boolean', 'system', 'Whether new registrations are open']
    ];

    foreach ($defaultSettings as $setting) {
        $sql = "INSERT INTO site_settings (setting_key, setting_value, setting_type, category, description) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($setting);
    }
    echo '<div class="success">✅ Inserted ' . count($defaultSettings) . ' site settings</div>';
    
    // Insert subjects
    $subjects = [
        // O-Level Core Subjects
        ['Mathematics', 'MATH-O', 'O-Level', 'Science', 'Core mathematics for O-Level'],
        ['English Language', 'ENG-O', 'O-Level', 'Language', 'English language and communication'],
        ['Physics', 'PHY-O', 'O-Level', 'Science', 'Basic physics concepts'],
        ['Chemistry', 'CHEM-O', 'O-Level', 'Science', 'Basic chemistry concepts'],
        ['Biology', 'BIO-O', 'O-Level', 'Science', 'Basic biology concepts'],
        ['History', 'HIST-O', 'O-Level', 'Humanities', 'World and African history'],
        ['Geography', 'GEO-O', 'O-Level', 'Humanities', 'Physical and human geography'],
        ['Literature in English', 'LIT-O', 'O-Level', 'Language', 'English literature'],
        ['Economics', 'ECON-O', 'O-Level', 'Commerce', 'Basic economics principles'],
        ['Computer Studies', 'COMP-O', 'O-Level', 'Technology', 'Computer literacy and programming'],
        
        // A-Level Subjects
        ['Mathematics', 'MATH-A', 'A-Level', 'Science', 'Advanced mathematics'],
        ['Physics', 'PHY-A', 'A-Level', 'Science', 'Advanced physics'],
        ['Chemistry', 'CHEM-A', 'A-Level', 'Science', 'Advanced chemistry'],
        ['Biology', 'BIO-A', 'A-Level', 'Science', 'Advanced biology'],
        ['Economics', 'ECON-A', 'A-Level', 'Commerce', 'Advanced economics'],
        ['History', 'HIST-A', 'A-Level', 'Humanities', 'Advanced history'],
        ['Geography', 'GEO-A', 'A-Level', 'Humanities', 'Advanced geography'],
        ['General Paper', 'GP-A', 'A-Level', 'General', 'General knowledge and current affairs'],
        ['Subsidiary ICT', 'ICT-A', 'A-Level', 'Technology', 'Information and Communication Technology']
    ];

    foreach ($subjects as $subject) {
        $sql = "INSERT INTO subjects (subject_name, subject_code, level, category, description) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($subject);
    }
    echo '<div class="success">✅ Inserted ' . count($subjects) . ' subjects</div>';
    
    // Insert academic programs
    $programs = [
        ['O-Level Sciences', 'O-SCI', 'O-Level', 'Science subjects including Mathematics, Physics, Chemistry, Biology for students interested in pursuing science careers.', '4 years', 'Mathematics, Physics, Chemistry, Biology, English, Literature, History, Geography, Computer Studies', 'Minimum 7 subjects at PLE with at least 4 distinctions', 1500000, 1],
        ['O-Level Arts', 'O-ART', 'O-Level', 'Arts and humanities subjects for students interested in social sciences and humanities.', '4 years', 'English, Literature, History, Geography, Religious Studies, Economics, Computer Studies', 'Minimum 7 subjects at PLE', 1200000, 2],
        ['A-Level PCM (Physics, Chemistry, Mathematics)', 'A-PCM', 'A-Level', 'Science combination ideal for engineering, medicine, and other science-related fields.', '2 years', 'Physics, Chemistry, Mathematics, General Paper, Subsidiary ICT', 'Minimum 5 O-Level credits including Math, Physics, Chemistry, and English', 2000000, 3],
        ['A-Level BCM (Biology, Chemistry, Mathematics)', 'A-BCM', 'A-Level', 'Science combination perfect for medical and biological sciences.', '2 years', 'Biology, Chemistry, Mathematics, General Paper, Subsidiary ICT', 'Minimum 5 O-Level credits including Math, Biology, Chemistry, and English', 2000000, 4],
        ['A-Level HEG (History, Economics, Geography)', 'A-HEG', 'A-Level', 'Arts combination for students interested in social sciences, law, and business.', '2 years', 'History, Economics, Geography, General Paper, Subsidiary ICT', 'Minimum 5 O-Level credits including English and relevant subjects', 1800000, 5],
        ['A-Level MEG (Mathematics, Economics, Geography)', 'A-MEG', 'A-Level', 'Commerce combination combining analytical and business skills.', '2 years', 'Mathematics, Economics, Geography, General Paper, Subsidiary ICT', 'Minimum 5 O-Level credits including Math, Economics, and English', 1800000, 6]
    ];

    foreach ($programs as $program) {
        $sql = "INSERT INTO academic_programs (program_name, program_code, level, description, duration, subjects, requirements, fees, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($program);
    }
    echo '<div class="success">✅ Inserted ' . count($programs) . ' academic programs</div>';
    
    // Insert sample teachers
    $teachers = [
        ['EMP001', 'Dr. Sarah Nakimuli', 'sarah.nakimuli@competent.ac.ug', '+256 701 123 456', 'PhD in Mathematics', 'Mathematics', 'Mathematics, Physics', 15, 'Head of Mathematics Department', 'Mathematics', 'Senior Teacher', 2500000],
        ['EMP002', 'Mr. James Okello', 'james.okello@competent.ac.ug', '+256 702 234 567', 'MSc in Physics', 'Physics', 'Physics, Mathematics', 12, 'Head of Physics Department', 'Science', 'Senior Teacher', 2300000],
        ['EMP003', 'Mrs. Grace Namutebi', 'grace.namutebi@competent.ac.ug', '+256 703 345 678', 'BA in English Literature', 'English', 'English, Literature', 10, 'Head of Languages Department', 'Languages', 'Senior Teacher', 2200000],
        ['EMP004', 'Mr. Peter Ssebugwawo', 'peter.ssebugwawo@competent.ac.ug', '+256 704 456 789', 'MSc in Chemistry', 'Chemistry', 'Chemistry, Biology', 8, 'Head of Chemistry Department', 'Science', 'Teacher', 2000000],
        ['EMP005', 'Ms. Rachel Atuhaire', 'rachel.atuhaire@competent.ac.ug', '+256 705 567 890', 'BA in History', 'History', 'History, Geography', 6, 'Head of Humanities Department', 'Humanities', 'Teacher', 1800000]
    ];

    foreach ($teachers as $teacher) {
        $sql = "INSERT INTO teachers (employee_id, full_name, email, phone, qualification, specialization, subjects_taught, experience_years, bio, department, position, salary, hire_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), 'active')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($teacher);
    }
    echo '<div class="success">✅ Inserted ' . count($teachers) . ' sample teachers</div>';
    
    // Insert sample classes
    $classes = [
        ['Senior 1 East', 'O-Level', '2025', 1, 40],
        ['Senior 1 West', 'O-Level', '2025', 2, 40],
        ['Senior 2 East', 'O-Level', '2025', 3, 38],
        ['Senior 2 West', 'O-Level', '2025', 4, 38],
        ['Senior 3 Sciences', 'O-Level', '2025', 1, 35],
        ['Senior 3 Arts', 'O-Level', '2025', 3, 35],
        ['Senior 4 Sciences', 'O-Level', '2025', 2, 32],
        ['Senior 4 Arts', 'O-Level', '2025', 5, 32],
        ['Senior 5 PCM', 'A-Level', '2025', 1, 25],
        ['Senior 5 BCM', 'A-Level', '2025', 2, 25],
        ['Senior 5 HEG', 'A-Level', '2025', 3, 30],
        ['Senior 6 PCM', 'A-Level', '2025', 4, 22],
        ['Senior 6 BCM', 'A-Level', '2025', 5, 22],
        ['Senior 6 HEG', 'A-Level', '2025', 1, 28]
    ];

    foreach ($classes as $class) {
        $sql = "INSERT INTO classes (class_name, level, academic_year, class_teacher_id, max_students, current_students) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($class);
    }
    echo '<div class="success">✅ Inserted ' . count($classes) . ' classes</div>';
    
    // Insert sample news articles
    $newsArticles = [
        [
            'Welcome to Academic Year 2025',
            'welcome-academic-year-2025',
            'We are excited to welcome all students back for the 2025 academic year. This year promises to be filled with new opportunities, enhanced learning experiences, and remarkable achievements. Our dedicated faculty and staff have prepared extensively to ensure that every student receives the quality education that Makerere Competent High School is known for.',
            'Welcome back message for the 2025 academic year with exciting new opportunities ahead.',
            '/assets/images/news/welcome-2025.jpg',
            'Academic',
            'academic year, welcome, 2025, new term',
            1,
            'published',
            1,
            'Welcome to Academic Year 2025',
            'Official welcome message for the 2025 academic year at Makerere Competent High School.',
            date('Y-m-d H:i:s')
        ],
        [
            'Outstanding Performance in 2024 UNEB Examinations',
            'outstanding-performance-2024-uneb',
            'We are proud to announce that our students have once again excelled in the 2024 UNEB examinations. With 98% of our O-Level students passing with credits and 95% of A-Level students securing principal passes, Makerere Competent High School continues to maintain its position as a leading academic institution in Uganda. Special congratulations go to our top performers who achieved distinctions across all subjects.',
            'Exceptional UNEB 2024 results showcase the academic excellence of our students.',
            '/assets/images/news/uneb-2024.jpg',
            'Achievement',
            'UNEB, results, 2024, excellence, achievement',
            1,
            'published',
            1,
            'Outstanding UNEB 2024 Results',
            'Makerere Competent High School students achieve exceptional results in 2024 UNEB examinations.',
            date('Y-m-d H:i:s', strtotime('-10 days'))
        ],
        [
            'Annual Science Fair 2025 - Innovation and Discovery',
            'science-fair-2025-innovation',
            'Our annual Science Fair 2025 was a tremendous success, showcasing the innovative minds of our students. From robotics projects to environmental solutions, students demonstrated their scientific prowess and creativity. The event featured over 50 projects covering various scientific disciplines including physics, chemistry, biology, and computer science. Winners will represent our school at the national science competition.',
            'Students showcase innovative scientific projects at the annual Science Fair 2025.',
            '/assets/images/news/science-fair-2025.jpg',
            'Events',
            'science fair, innovation, students, projects, 2025',
            2,
            'published',
            0,
            'Annual Science Fair 2025',
            'Student innovation takes center stage at Makerere Competent High School Science Fair 2025.',
            date('Y-m-d H:i:s', strtotime('-5 days'))
        ]
    ];

    foreach ($newsArticles as $article) {
        $sql = "INSERT INTO news (title, slug, content, excerpt, featured_image, category, tags, author_id, status, featured, meta_title, meta_description, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($article);
    }
    echo '<div class="success">✅ Inserted ' . count($newsArticles) . ' news articles</div>';
    
    // Insert sample events
    $events = [
        [
            'Inter-House Sports Competition 2025',
            'Join us for our annual inter-house sports competition featuring athletics, football, netball, basketball, and volleyball. Students will compete for the coveted sports trophy while promoting teamwork and school spirit.',
            date('Y-m-d', strtotime('+30 days')),
            '08:00:00',
            '17:00:00',
            'School Sports Grounds',
            'Sports',
            '/assets/images/events/sports-2025.jpg',
            'upcoming',
            0,
            500,
            0,
            0.00,
            1
        ],
        [
            'Career Guidance Workshop',
            'A comprehensive career guidance workshop for Senior 5 and 6 students. Industry professionals will share insights about various career paths, university admission requirements, and scholarship opportunities.',
            date('Y-m-d', strtotime('+15 days')),
            '09:00:00',
            '15:00:00',
            'Main Assembly Hall',
            'Academic',
            '/assets/images/events/career-workshop.jpg',
            'upcoming',
            1,
            200,
            0,
            10000.00,
            1
        ],
        [
            'Parent-Teacher Conference',
            'Mid-term parent-teacher conference to discuss student progress, academic performance, and areas for improvement. All parents are encouraged to attend.',
            date('Y-m-d', strtotime('+20 days')),
            '08:00:00',
            '16:00:00',
            'Various Classrooms',
            'Academic',
            '/assets/images/events/parent-teacher.jpg',
            'upcoming',
            1,
            800,
            0,
            0.00,
            2
        ]
    ];

    foreach ($events as $event) {
        $sql = "INSERT INTO events (title, description, event_date, start_time, end_time, location, category, featured_image, status, registration_required, max_participants, current_participants, registration_fee, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($event);
    }
    echo '<div class="success">✅ Inserted ' . count($events) . ' upcoming events</div>';
    
    // Insert sample gallery items
    $galleryItems = [
        ['School Campus Overview', 'Aerial view of our beautiful campus showing the main buildings, sports facilities, and green spaces.', '/assets/images/gallery/campus-aerial.jpg', '/assets/images/gallery/thumbs/campus-aerial-thumb.jpg', 'Campus', 'campus, buildings, aerial view', 'Aerial view of Makerere Competent High School campus', 1, 1],
        ['Science Laboratory', 'State-of-the-art science laboratory equipped with modern equipment for physics, chemistry, and biology experiments.', '/assets/images/gallery/science-lab.jpg', '/assets/images/gallery/thumbs/science-lab-thumb.jpg', 'Facilities', 'laboratory, science, equipment', 'Modern science laboratory at Makerere Competent High School', 2, 1],
        ['Library and Study Area', 'Spacious library with thousands of books, digital resources, and quiet study areas for students.', '/assets/images/gallery/library.jpg', '/assets/images/gallery/thumbs/library-thumb.jpg', 'Facilities', 'library, books, study', 'Well-equipped library and study area', 3, 1],
        ['Sports Complex', 'Modern sports complex featuring basketball courts, volleyball courts, and indoor sports facilities.', '/assets/images/gallery/sports-complex.jpg', '/assets/images/gallery/thumbs/sports-complex-thumb.jpg', 'Sports', 'sports, courts, facilities', 'Indoor sports complex and courts', 4, 1],
        ['Graduation Ceremony 2024', 'Proud graduates celebrating their achievements at the 2024 graduation ceremony.', '/assets/images/gallery/graduation-2024.jpg', '/assets/images/gallery/thumbs/graduation-2024-thumb.jpg', 'Events', 'graduation, ceremony, 2024', 'Graduation ceremony 2024 celebration', 5, 1]
    ];

    foreach ($galleryItems as $item) {
        $sql = "INSERT INTO gallery (title, description, image_path, thumbnail_path, category, tags, alt_text, sort_order, uploaded_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($item);
    }
    echo '<div class="success">✅ Inserted ' . count($galleryItems) . ' gallery items</div>';
    
    // Insert sample alumni
    $alumni = [
        [
            'Dr. Mary Nakato',
            'mary.nakato@example.com',
            '+256 701 987 654',
            2018,
            'A-Level PCM',
            'Medical Doctor',
            'Mulago Hospital',
            'Kampala, Uganda',
            'https://linkedin.com/in/marynakato',
            'Graduated with distinctions in all subjects. Currently practicing as a medical doctor and passionate about giving back to the school.',
            '/assets/images/alumni/mary-nakato.jpg',
            'Best student award 2018, Currently pursuing specialization in pediatrics',
            1,
            'approved',
            1,
            date('Y-m-d H:i:s', strtotime('-6 months'))
        ],
        [
            'Eng. Samuel Mugisha',
            'samuel.mugisha@example.com',
            '+256 702 876 543',
            2016,
            'A-Level PCM',
            'Software Engineer',
            'Google Inc.',
            'California, USA',
            'https://linkedin.com/in/samuelmugisha',
            'Top performer in mathematics and physics. Currently working as a senior software engineer at Google.',
            '/assets/images/alumni/samuel-mugisha.jpg',
            'School mathematics prize 2016, Published researcher in AI and machine learning',
            1,
            'approved',
            1,
            date('Y-m-d H:i:s', strtotime('-4 months'))
        ]
    ];

    foreach ($alumni as $alum) {
        $sql = "INSERT INTO alumni (full_name, email, phone, graduation_year, course_studied, current_profession, current_employer, location, linkedin_profile, bio, profile_photo, achievements, willing_to_mentor, status, approved_by, approved_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($alum);
    }
    echo '<div class="success">✅ Inserted ' . count($alumni) . ' alumni records</div>';
    
    // Insert sample contact messages
    $messages = [
        [
            'John Ssempala',
            'john.ssempala@example.com',
            '+256 701 234 567',
            'Inquiry about Admission Requirements',
            'Good morning, I would like to inquire about the admission requirements for O-Level. My child completed PLE last year and I am interested in enrolling them for 2025. Please provide information about the application process and required documents.',
            'new',
            'normal',
            '192.168.1.100',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        ],
        [
            'Grace Namutebi',
            'grace.namutebi@example.com',
            '+256 702 345 678',
            'School Fees Payment Methods',
            'Hello, I am a parent of a current student in Senior 2. I would like to know about the available payment methods for school fees and if there are any installment options available.',
            'read',
            'normal',
            '192.168.1.101',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15'
        ]
    ];

    foreach ($messages as $message) {
        $sql = "INSERT INTO contact_messages (name, email, phone, subject, message, status, priority, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($message);
    }
    echo '<div class="success">✅ Inserted ' . count($messages) . ' contact messages</div>';
    
    // Insert sample admission applications
    $admissions = [
        [
            'APP2025001',
            'David Okello',
            'Margaret Okello',
            'margaret.okello@example.com',
            '+256 703 456 789',
            'Plot 123, Bugolobi, Kampala',
            '2008-03-15',
            'male',
            'Kampala Primary School',
            'Senior 1',
            '2025',
            '/uploads/documents/app001-documents.pdf',
            1,
            'PAY2025001',
            'pending'
        ],
        [
            'APP2025002',
            'Sarah Namukasa',
            'James Namukasa',
            'james.namukasa@example.com',
            '+256 704 567 890',
            'Plot 456, Ntinda, Kampala',
            '2007-07-22',
            'female',
            'St. Mary\'s Primary School',
            'Senior 1',
            '2025',
            '/uploads/documents/app002-documents.pdf',
            1,
            'PAY2025002',
            'approved'
        ]
    ];

    foreach ($admissions as $admission) {
        $sql = "INSERT INTO admissions (application_number, student_name, parent_name, email, phone, address, date_of_birth, gender, previous_school, class_applying, academic_year, documents_path, application_fee_paid, payment_reference, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($admission);
    }
    echo '<div class="success">✅ Inserted ' . count($admissions) . ' admission applications</div>';
    
    // Create upload directories
    $uploadDirs = [
        '../assets/images/uploads/',
        '../assets/images/uploads/gallery/',
        '../assets/images/uploads/news/',
        '../assets/images/uploads/events/',
        '../assets/images/uploads/alumni/',
        '../assets/images/uploads/teachers/',
        '../assets/images/uploads/students/',
        '../assets/images/uploads/documents/'
    ];
    
    foreach ($uploadDirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }
    echo '<div class="success">✅ Created upload directories</div>';
    
    // Success message
    echo '<div class="section">';
    echo '<h2>🎉 Database Recreation Complete!</h2>';
    echo '<div class="highlight">';
    echo '<h3>📋 Summary:</h3>';
    echo '<ul>';
    echo '<li><strong>Database:</strong> ' . DB_NAME . ' (completely recreated)</li>';
    echo '<li><strong>Tables Created:</strong> 14 comprehensive tables</li>';
    echo '<li><strong>Sample Data:</strong> Populated with realistic sample data</li>';
    echo '<li><strong>Upload Directories:</strong> Created and configured</li>';
    echo '</ul>';
    echo '</div>';
    
    echo '<div class="highlight">';
    echo '<h3>🔐 Admin Access:</h3>';
    echo '<ul>';
    echo '<li><strong>Admin URL:</strong> <a href="admin/login.php" target="_blank">admin/login.php</a></li>';
    echo '<li><strong>Super Admin:</strong> username: <code>admin</code>, password: <code>admin123</code></li>';
    echo '<li><strong>Principal:</strong> username: <code>principal</code>, password: <code>principal123</code></li>';
    echo '</ul>';
    echo '</div>';
    
    echo '<div class="highlight">';
    echo '<h3>⚠️ Important Next Steps:</h3>';
    echo '<ol>';
    echo '<li><strong>Change Default Passwords:</strong> Login and change all default passwords immediately</li>';
    echo '<li><strong>File Permissions:</strong> Ensure upload directories have proper write permissions</li>';
    echo '<li><strong>Site Settings:</strong> Update site settings in the admin panel with your actual information</li>';
    echo '<li><strong>Content:</strong> Add your actual content, images, and information</li>';
    echo '<li><strong>Security:</strong> Update the encryption key in config.php</li>';
    echo '</ol>';
    echo '</div>';
    
    echo '<div class="highlight">';
    echo '<h3>📊 Database Tables Created:</h3>';
    echo '<ul>';
    echo '<li><strong>admin_users</strong> - Administrative user accounts</li>';
    echo '<li><strong>site_settings</strong> - Configurable site settings</li>';
    echo '<li><strong>news</strong> - News articles and announcements</li>';
    echo '<li><strong>gallery</strong> - Image gallery management</li>';
    echo '<li><strong>contact_messages</strong> - Contact form submissions</li>';
    echo '<li><strong>alumni</strong> - Alumni/MACOSA member records</li>';
    echo '<li><strong>admissions</strong> - Student admission applications</li>';
    echo '<li><strong>academic_programs</strong> - Available academic programs</li>';
    echo '<li><strong>teachers</strong> - Faculty and staff information</li>';
    echo '<li><strong>events</strong> - School events and activities</li>';
    echo '<li><strong>activity_log</strong> - System activity tracking</li>';
    echo '<li><strong>students</strong> - Enrolled student records</li>';
    echo '<li><strong>subjects</strong> - Available subjects</li>';
    echo '<li><strong>classes</strong> - Class management</li>';
    echo '</ul>';
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
