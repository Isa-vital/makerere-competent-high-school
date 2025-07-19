<?php
// Manage Settings - Admin panel for Makerere Competent High School
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if user is logged in
requireAdmin();

$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        foreach ($_POST as $key => $value) {
            if ($key !== 'submit') {
                $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
                $stmt->execute([sanitizeInput($value), $key]);
            }
        }
        $message = 'Settings updated successfully!';
        logActivity('settings_update', 'Updated site settings');
    } catch (PDOException $e) {
        $error = 'Database error: ' . $e->getMessage();
    }
}

// Get all settings
$stmt = $pdo->query("SELECT * FROM site_settings ORDER BY setting_key");
$settings = [];
while ($row = $stmt->fetch()) {
    $settings[$row['setting_key']] = $row;
}

$page_title = 'Site Settings';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Include admin styles */
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .admin-header { background: linear-gradient(135deg, #1a472a 0%, #2d5a3d 100%); color: white; padding: 1rem 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .admin-nav { display: flex; justify-content: space-between; align-items: center; }
        .admin-nav h1 { font-size: 1.5rem; margin: 0; }
        .admin-nav .nav-links { display: flex; gap: 1rem; align-items: center; }
        .admin-nav .nav-links a { color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 5px; transition: background-color 0.3s; }
        .admin-nav .nav-links a:hover { background-color: rgba(255,255,255,0.1); }
        .admin-content { padding: 2rem; max-width: 1200px; margin: 0 auto; }
        .admin-card { background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 2rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333; }
        .form-group input, .form-group textarea { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; }
        .form-group textarea { min-height: 100px; resize: vertical; }
        .btn { display: inline-block; padding: 0.75rem 1.5rem; background: #1a472a; color: white; text-decoration: none; border-radius: 5px; border: none; cursor: pointer; font-size: 1rem; transition: background-color 0.3s; }
        .btn:hover { background: #0f2e18; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #545b62; }
        .alert { padding: 1rem; border-radius: 5px; margin-bottom: 1rem; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .settings-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
        .settings-section { background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; }
        .settings-section h3 { margin-top: 0; color: #1a472a; border-bottom: 2px solid #1a472a; padding-bottom: 0.5rem; }
        .breadcrumb { margin-bottom: 2rem; }
        .breadcrumb a { color: #1a472a; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        @media (max-width: 768px) {
            .settings-grid { grid-template-columns: 1fr; }
            .admin-content { padding: 1rem; }
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="container">
            <nav class="admin-nav">
                <h1><i class="fas fa-cogs"></i> <?php echo $page_title; ?></h1>
                <div class="nav-links">
                    <a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </nav>
        </div>
    </header>

    <main class="admin-content">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="index.php">Dashboard</a> / Site Settings
        </div>

        <!-- Messages -->
        <?php if ($message): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <?php if ($error): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="settings-grid">
                <!-- General Settings -->
                <div class="admin-card">
                    <div class="settings-section">
                        <h3><i class="fas fa-info-circle"></i> General Information</h3>
                        
                        <div class="form-group">
                            <label for="site_title">Site Title</label>
                            <input type="text" id="site_title" name="site_title" 
                                   value="<?php echo htmlspecialchars($settings['site_title']['setting_value'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="site_description">Site Description</label>
                            <textarea id="site_description" name="site_description" rows="3"><?php echo htmlspecialchars($settings['site_description']['setting_value'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="students_count">Number of Students</label>
                            <input type="number" id="students_count" name="students_count" 
                                   value="<?php echo htmlspecialchars($settings['students_count']['setting_value'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="teachers_count">Number of Teachers</label>
                            <input type="number" id="teachers_count" name="teachers_count" 
                                   value="<?php echo htmlspecialchars($settings['teachers_count']['setting_value'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="established_year">Year Established</label>
                            <input type="number" id="established_year" name="established_year" 
                                   value="<?php echo htmlspecialchars($settings['established_year']['setting_value'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="admin-card">
                    <div class="settings-section">
                        <h3><i class="fas fa-phone"></i> Contact Information</h3>
                        
                        <div class="form-group">
                            <label for="contact_phone">Phone Number</label>
                            <input type="tel" id="contact_phone" name="contact_phone" 
                                   value="<?php echo htmlspecialchars($settings['contact_phone']['setting_value'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="contact_email">Email Address</label>
                            <input type="email" id="contact_email" name="contact_email" 
                                   value="<?php echo htmlspecialchars($settings['contact_email']['setting_value'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="contact_address">Physical Address</label>
                            <textarea id="contact_address" name="contact_address" rows="3"><?php echo htmlspecialchars($settings['contact_address']['setting_value'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <div class="settings-section">
                        <h3><i class="fas fa-share-alt"></i> Social Media Links</h3>
                        
                        <div class="form-group">
                            <label for="facebook_url">Facebook URL</label>
                            <input type="url" id="facebook_url" name="facebook_url" 
                                   value="<?php echo htmlspecialchars($settings['facebook_url']['setting_value'] ?? ''); ?>"
                                   placeholder="https://facebook.com/yourpage">
                        </div>

                        <div class="form-group">
                            <label for="twitter_url">Twitter URL</label>
                            <input type="url" id="twitter_url" name="twitter_url" 
                                   value="<?php echo htmlspecialchars($settings['twitter_url']['setting_value'] ?? ''); ?>"
                                   placeholder="https://twitter.com/yourhandle">
                        </div>

                        <div class="form-group">
                            <label for="instagram_url">Instagram URL</label>
                            <input type="url" id="instagram_url" name="instagram_url" 
                                   value="<?php echo htmlspecialchars($settings['instagram_url']['setting_value'] ?? ''); ?>"
                                   placeholder="https://instagram.com/yourhandle">
                        </div>

                        <div class="form-group">
                            <label for="youtube_url">YouTube URL</label>
                            <input type="url" id="youtube_url" name="youtube_url" 
                                   value="<?php echo htmlspecialchars($settings['youtube_url']['setting_value'] ?? ''); ?>"
                                   placeholder="https://youtube.com/yourchannel">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Information -->
            <div class="admin-card">
                <div class="settings-section">
                    <h3><i class="fas fa-graduation-cap"></i> Academic Information</h3>
                    
                    <div class="settings-grid">
                        <div class="form-group">
                            <label for="school_motto">School Motto</label>
                            <input type="text" id="school_motto" name="school_motto" 
                                   value="<?php echo htmlspecialchars($settings['school_motto']['setting_value'] ?? ''); ?>"
                                   placeholder="e.g., Excellence in Education">
                        </div>

                        <div class="form-group">
                            <label for="school_vision">School Vision</label>
                            <textarea id="school_vision" name="school_vision" rows="3"><?php echo htmlspecialchars($settings['school_vision']['setting_value'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="school_mission">School Mission</label>
                            <textarea id="school_mission" name="school_mission" rows="3"><?php echo htmlspecialchars($settings['school_mission']['setting_value'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="academic_year">Current Academic Year</label>
                            <input type="text" id="academic_year" name="academic_year" 
                                   value="<?php echo htmlspecialchars($settings['academic_year']['setting_value'] ?? ''); ?>"
                                   placeholder="e.g., 2024-2025">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admission Information -->
            <div class="admin-card">
                <div class="settings-section">
                    <h3><i class="fas fa-user-plus"></i> Admission Information</h3>
                    
                    <div class="settings-grid">
                        <div class="form-group">
                            <label for="admission_status">Admission Status</label>
                            <select id="admission_status" name="admission_status">
                                <option value="open" <?php echo ($settings['admission_status']['setting_value'] ?? '') == 'open' ? 'selected' : ''; ?>>Open</option>
                                <option value="closed" <?php echo ($settings['admission_status']['setting_value'] ?? '') == 'closed' ? 'selected' : ''; ?>>Closed</option>
                                <option value="coming_soon" <?php echo ($settings['admission_status']['setting_value'] ?? '') == 'coming_soon' ? 'selected' : ''; ?>>Coming Soon</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="admission_fee">Admission Fee (UGX)</label>
                            <input type="number" id="admission_fee" name="admission_fee" 
                                   value="<?php echo htmlspecialchars($settings['admission_fee']['setting_value'] ?? ''); ?>"
                                   placeholder="e.g., 50000">
                        </div>

                        <div class="form-group">
                            <label for="term_fee">Termly Fee (UGX)</label>
                            <input type="number" id="term_fee" name="term_fee" 
                                   value="<?php echo htmlspecialchars($settings['term_fee']['setting_value'] ?? ''); ?>"
                                   placeholder="e.g., 300000">
                        </div>

                        <div class="form-group">
                            <label for="admission_requirements">Admission Requirements</label>
                            <textarea id="admission_requirements" name="admission_requirements" rows="4"><?php echo htmlspecialchars($settings['admission_requirements']['setting_value'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div style="text-align: center; margin-top: 2rem;">
                <button type="submit" name="submit" class="btn" style="padding: 1rem 3rem; font-size: 1.1rem;">
                    <i class="fas fa-save"></i> Save All Settings
                </button>
                <a href="index.php" class="btn btn-secondary" style="padding: 1rem 3rem; font-size: 1.1rem;">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </form>
    </main>

    <script>
        // Add confirmation before leaving if changes were made
        let formChanged = false;
        
        document.querySelectorAll('input, textarea, select').forEach(element => {
            element.addEventListener('change', () => {
                formChanged = true;
            });
        });

        window.addEventListener('beforeunload', (e) => {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        document.querySelector('form').addEventListener('submit', () => {
            formChanged = false;
        });
    </script>
</body>
</html>
