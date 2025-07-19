<?php
// Manage Alumni - Admin panel for Makerere Competent High School
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if user is logged in
requireAdmin();

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? 0;
$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($action == 'add' || $action == 'edit') {
        $full_name = sanitizeInput($_POST['full_name']);
        $email = sanitizeInput($_POST['email']);
        $phone = sanitizeInput($_POST['phone']);
        $graduation_year = intval($_POST['graduation_year']);
        $course_studied = sanitizeInput($_POST['course_studied']);
        $current_profession = sanitizeInput($_POST['current_profession']);
        $current_employer = sanitizeInput($_POST['current_employer']);
        $location = sanitizeInput($_POST['location']);
        $linkedin_profile = sanitizeInput($_POST['linkedin_profile']);
        $bio = $_POST['bio'];
        $achievements = $_POST['achievements'];
        $willing_to_mentor = isset($_POST['willing_to_mentor']) ? 1 : 0;
        $status = $_POST['status'];
        
        // Handle image upload
        $profile_photo = '';
        if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
            $upload_result = uploadImage($_FILES['profile_photo'], 'alumni');
            if ($upload_result['success']) {
                $profile_photo = $upload_result['filename'];
            } else {
                $error = $upload_result['error'];
            }
        }
        
        if (empty($error)) {
            try {
                if ($action == 'add') {
                    $stmt = $pdo->prepare("INSERT INTO alumni (full_name, email, phone, graduation_year, course_studied, current_profession, current_employer, location, linkedin_profile, bio, profile_photo, achievements, willing_to_mentor, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$full_name, $email, $phone, $graduation_year, $course_studied, $current_profession, $current_employer, $location, $linkedin_profile, $bio, $profile_photo, $achievements, $willing_to_mentor, $status]);
                    $message = 'Alumni record added successfully!';
                    logActivity('alumni_add', "Added alumni: $full_name");
                } else {
                    if ($profile_photo) {
                        $stmt = $pdo->prepare("UPDATE alumni SET full_name = ?, email = ?, phone = ?, graduation_year = ?, course_studied = ?, current_profession = ?, current_employer = ?, location = ?, linkedin_profile = ?, bio = ?, profile_photo = ?, achievements = ?, willing_to_mentor = ?, status = ? WHERE id = ?");
                        $stmt->execute([$full_name, $email, $phone, $graduation_year, $course_studied, $current_profession, $current_employer, $location, $linkedin_profile, $bio, $profile_photo, $achievements, $willing_to_mentor, $status, $id]);
                    } else {
                        $stmt = $pdo->prepare("UPDATE alumni SET full_name = ?, email = ?, phone = ?, graduation_year = ?, course_studied = ?, current_profession = ?, current_employer = ?, location = ?, linkedin_profile = ?, bio = ?, achievements = ?, willing_to_mentor = ?, status = ? WHERE id = ?");
                        $stmt->execute([$full_name, $email, $phone, $graduation_year, $course_studied, $current_profession, $current_employer, $location, $linkedin_profile, $bio, $achievements, $willing_to_mentor, $status, $id]);
                    }
                    $message = 'Alumni record updated successfully!';
                    logActivity('alumni_update', "Updated alumni: $full_name");
                }
                $action = 'list';
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
    
    if ($action == 'delete' && $id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM alumni WHERE id = ?");
            $stmt->execute([$id]);
            $message = 'Alumni record deleted successfully!';
            logActivity('alumni_delete', "Deleted alumni ID: $id");
            $action = 'list';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

// Get alumni for editing
$alumni_item = null;
if ($action == 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM alumni WHERE id = ?");
    $stmt->execute([$id]);
    $alumni_item = $stmt->fetch();
    if (!$alumni_item) {
        $action = 'list';
        $error = 'Alumni record not found.';
    }
}

// Get all alumni for listing
if ($action == 'list') {
    $filter = $_GET['filter'] ?? 'all';
    $year = $_GET['year'] ?? '';
    
    $where_clause = "";
    $params = [];
    
    if ($filter !== 'all') {
        $where_clause .= "WHERE status = ?";
        $params[] = $filter;
    }
    
    if ($year) {
        $where_clause .= ($where_clause ? " AND" : "WHERE") . " graduation_year = ?";
        $params[] = $year;
    }
    
    $stmt = $pdo->prepare("SELECT * FROM alumni $where_clause ORDER BY graduation_year DESC, full_name ASC");
    $stmt->execute($params);
    $alumni_list = $stmt->fetchAll();
    
    // Get graduation years for filter
    $stmt = $pdo->query("SELECT DISTINCT graduation_year FROM alumni ORDER BY graduation_year DESC");
    $graduation_years = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

$page_title = 'Manage Alumni';
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
        /* Include admin styles from index.php */
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
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; }
        .form-group textarea { min-height: 100px; resize: vertical; }
        .btn { display: inline-block; padding: 0.75rem 1.5rem; background: #1a472a; color: white; text-decoration: none; border-radius: 5px; border: none; cursor: pointer; font-size: 1rem; transition: background-color 0.3s; }
        .btn:hover { background: #0f2e18; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #545b62; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .btn-small { padding: 0.5rem 1rem; font-size: 0.9rem; }
        .alert { padding: 1rem; border-radius: 5px; margin-bottom: 1rem; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        .table th, .table td { padding: 1rem; text-align: left; border-bottom: 1px solid #dee2e6; }
        .table th { background: #f8f9fa; font-weight: 600; }
        .table tr:hover { background: #f8f9fa; }
        .status-badge { padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.8rem; font-weight: 500; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-rejected { background: #f8d7da; color: #721c24; }
        .file-upload { position: relative; display: inline-block; }
        .file-upload input[type=file] { position: absolute; opacity: 0; width: 100%; height: 100%; cursor: pointer; }
        .file-upload-button { display: inline-block; padding: 0.5rem 1rem; background: #007bff; color: white; border-radius: 3px; cursor: pointer; }
        .breadcrumb { margin-bottom: 2rem; }
        .breadcrumb a { color: #1a472a; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
        .filters { display: flex; gap: 1rem; margin-bottom: 2rem; align-items: center; }
        .filters select { padding: 0.5rem; border: 1px solid #ddd; border-radius: 3px; }
        .alumni-photo { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
        .mentor-badge { background: #e7f3ff; color: #0066cc; padding: 0.2rem 0.5rem; border-radius: 10px; font-size: 0.75rem; }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="container">
            <nav class="admin-nav">
                <h1><i class="fas fa-graduation-cap"></i> <?php echo $page_title; ?></h1>
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
            <a href="index.php">Dashboard</a> / 
            <?php if ($action == 'list'): ?>
                Alumni Management
            <?php elseif ($action == 'add'): ?>
                <a href="manage-alumni.php">Alumni Management</a> / Add Alumni
            <?php elseif ($action == 'edit'): ?>
                <a href="manage-alumni.php">Alumni Management</a> / Edit Alumni
            <?php endif; ?>
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

        <?php if ($action == 'list'): ?>
        <!-- Alumni List -->
        <div class="admin-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>Alumni Records</h2>
                <a href="?action=add" class="btn">
                    <i class="fas fa-plus"></i> Add Alumni
                </a>
            </div>

            <!-- Filters -->
            <div class="filters">
                <label style="margin: 0;">Filter by Status:</label>
                <select onchange="window.location.href='?filter=' + this.value + (document.getElementById('year_filter').value ? '&year=' + document.getElementById('year_filter').value : '')">
                    <option value="all" <?php echo ($filter == 'all') ? 'selected' : ''; ?>>All Status</option>
                    <option value="approved" <?php echo ($filter == 'approved') ? 'selected' : ''; ?>>Approved</option>
                    <option value="pending" <?php echo ($filter == 'pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="rejected" <?php echo ($filter == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                </select>

                <label style="margin: 0;">Filter by Year:</label>
                <select id="year_filter" onchange="window.location.href='?year=' + this.value + (this.form ? '' : '&filter=<?php echo $filter; ?>')">
                    <option value="">All Years</option>
                    <?php foreach ($graduation_years as $grad_year): ?>
                    <option value="<?php echo $grad_year; ?>" <?php echo ($year == $grad_year) ? 'selected' : ''; ?>><?php echo $grad_year; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if (!empty($alumni_list)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Alumni</th>
                        <th>Graduation</th>
                        <th>Current Role</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alumni_list as $alumni): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <?php if ($alumni['profile_photo']): ?>
                                <img src="../assets/images/<?php echo htmlspecialchars($alumni['profile_photo']); ?>" 
                                     alt="Profile" class="alumni-photo">
                                <?php else: ?>
                                <div class="alumni-photo" style="background: #ddd; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user" style="color: #888;"></i>
                                </div>
                                <?php endif; ?>
                                <div>
                                    <strong><?php echo htmlspecialchars($alumni['full_name']); ?></strong>
                                    <?php if ($alumni['willing_to_mentor']): ?>
                                    <br><span class="mentor-badge">Mentor Available</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php echo $alumni['graduation_year']; ?>
                            <?php if ($alumni['course_studied']): ?>
                            <br><small style="color: #666;"><?php echo htmlspecialchars($alumni['course_studied']); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($alumni['current_profession'] ?: 'Not specified'); ?>
                            <?php if ($alumni['current_employer']): ?>
                            <br><small style="color: #666;">at <?php echo htmlspecialchars($alumni['current_employer']); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($alumni['email']); ?>
                            <?php if ($alumni['phone']): ?>
                            <br><small style="color: #666;"><?php echo htmlspecialchars($alumni['phone']); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-badge status-<?php echo $alumni['status']; ?>">
                                <?php echo ucfirst($alumni['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="?action=edit&id=<?php echo $alumni['id']; ?>" class="btn btn-small btn-secondary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="?action=delete&id=<?php echo $alumni['id']; ?>" 
                               class="btn btn-small btn-danger"
                               onclick="return confirm('Are you sure you want to delete this alumni record?')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p style="text-align: center; color: #666; padding: 3rem;">No alumni records found.</p>
            <?php endif; ?>
        </div>

        <?php elseif ($action == 'add' || $action == 'edit'): ?>
        <!-- Add/Edit Form -->
        <div class="admin-card">
            <h2><?php echo $action == 'add' ? 'Add Alumni Record' : 'Edit Alumni Record'; ?></h2>

            <form method="POST" enctype="multipart/form-data">
                <div class="grid-2">
                    <div class="form-group">
                        <label for="full_name">Full Name *</label>
                        <input type="text" id="full_name" name="full_name" required 
                               value="<?php echo $alumni_item ? htmlspecialchars($alumni_item['full_name']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo $alumni_item ? htmlspecialchars($alumni_item['email']) : ''; ?>">
                    </div>
                </div>

                <div class="grid-3">
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" 
                               value="<?php echo $alumni_item ? htmlspecialchars($alumni_item['phone']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="graduation_year">Graduation Year *</label>
                        <input type="number" id="graduation_year" name="graduation_year" required 
                               min="1990" max="<?php echo date('Y'); ?>"
                               value="<?php echo $alumni_item ? $alumni_item['graduation_year'] : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="course_studied">Course Studied</label>
                        <input type="text" id="course_studied" name="course_studied" 
                               value="<?php echo $alumni_item ? htmlspecialchars($alumni_item['course_studied']) : ''; ?>">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="current_profession">Current Profession</label>
                        <input type="text" id="current_profession" name="current_profession" 
                               value="<?php echo $alumni_item ? htmlspecialchars($alumni_item['current_profession']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="current_employer">Current Employer</label>
                        <input type="text" id="current_employer" name="current_employer" 
                               value="<?php echo $alumni_item ? htmlspecialchars($alumni_item['current_employer']) : ''; ?>">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" 
                               placeholder="City, Country"
                               value="<?php echo $alumni_item ? htmlspecialchars($alumni_item['location']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="linkedin_profile">LinkedIn Profile</label>
                        <input type="url" id="linkedin_profile" name="linkedin_profile" 
                               placeholder="https://linkedin.com/in/username"
                               value="<?php echo $alumni_item ? htmlspecialchars($alumni_item['linkedin_profile']) : ''; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="bio">Biography</label>
                    <textarea id="bio" name="bio" rows="3" 
                              placeholder="Brief biography and background..."><?php echo $alumni_item ? htmlspecialchars($alumni_item['bio']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="achievements">Achievements</label>
                    <textarea id="achievements" name="achievements" rows="3" 
                              placeholder="Notable achievements and awards..."><?php echo $alumni_item ? htmlspecialchars($alumni_item['achievements']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="profile_photo">Profile Photo</label>
                    <div class="file-upload">
                        <input type="file" id="profile_photo" name="profile_photo" accept="image/*">
                        <div class="file-upload-button">
                            <i class="fas fa-upload"></i> Choose Photo
                        </div>
                    </div>
                    <?php if ($alumni_item && $alumni_item['profile_photo']): ?>
                    <p style="margin-top: 0.5rem;">Current: <?php echo htmlspecialchars($alumni_item['profile_photo']); ?></p>
                    <?php endif; ?>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="pending" <?php echo ($alumni_item && $alumni_item['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="approved" <?php echo ($alumni_item && $alumni_item['status'] == 'approved') ? 'selected' : ''; ?>>Approved</option>
                            <option value="rejected" <?php echo ($alumni_item && $alumni_item['status'] == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="willing_to_mentor" value="1" <?php echo ($alumni_item && $alumni_item['willing_to_mentor']) ? 'checked' : ''; ?>>
                            Willing to Mentor Current Students
                        </label>
                        <small style="color: #666;">Alumni available for mentoring programs</small>
                    </div>
                </div>

                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn">
                        <i class="fas fa-save"></i> <?php echo $action == 'add' ? 'Add Alumni' : 'Update Alumni'; ?>
                    </button>
                    <a href="manage-alumni.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </main>

    <script>
        // File upload feedback
        document.getElementById('profile_photo').addEventListener('change', function() {
            const fileName = this.files[0]?.name;
            if (fileName) {
                document.querySelector('.file-upload-button').innerHTML = '<i class="fas fa-check"></i> ' + fileName;
            }
        });
    </script>
</body>
</html>
