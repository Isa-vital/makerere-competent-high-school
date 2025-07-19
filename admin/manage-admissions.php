<?php
// Manage Admissions - Admin panel for Makerere Competent High School
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
        $student_name = sanitizeInput($_POST['student_name']);
        $parent_name = sanitizeInput($_POST['parent_name']);
        $email = sanitizeInput($_POST['email']);
        $phone = sanitizeInput($_POST['phone']);
        $address = $_POST['address'];
        $date_of_birth = $_POST['date_of_birth'];
        $gender = $_POST['gender'];
        $previous_school = sanitizeInput($_POST['previous_school']);
        $class_applying = sanitizeInput($_POST['class_applying']);
        $application_fee_paid = isset($_POST['application_fee_paid']) ? 1 : 0;
        $status = $_POST['status'];
        $notes = $_POST['notes'];
        $processed_by = $_SESSION['user_id'];
        $processed_at = ($status !== 'pending') ? date('Y-m-d H:i:s') : null;
        
        // Handle document upload
        $documents_path = '';
        if (isset($_FILES['documents']) && $_FILES['documents']['error'] == 0) {
            $upload_result = uploadImage($_FILES['documents'], 'admissions');
            if ($upload_result['success']) {
                $documents_path = $upload_result['filename'];
            } else {
                $error = $upload_result['error'];
            }
        }
        
        if (empty($error)) {
            try {
                if ($action == 'add') {
                    $stmt = $pdo->prepare("INSERT INTO admissions (student_name, parent_name, email, phone, address, date_of_birth, gender, previous_school, class_applying, documents_path, application_fee_paid, status, notes, processed_by, processed_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$student_name, $parent_name, $email, $phone, $address, $date_of_birth, $gender, $previous_school, $class_applying, $documents_path, $application_fee_paid, $status, $notes, $processed_by, $processed_at]);
                    $message = 'Admission application added successfully!';
                    logActivity('admission_add', "Added admission application: $student_name");
                } else {
                    if ($documents_path) {
                        $stmt = $pdo->prepare("UPDATE admissions SET student_name = ?, parent_name = ?, email = ?, phone = ?, address = ?, date_of_birth = ?, gender = ?, previous_school = ?, class_applying = ?, documents_path = ?, application_fee_paid = ?, status = ?, notes = ?, processed_by = ?, processed_at = ? WHERE id = ?");
                        $stmt->execute([$student_name, $parent_name, $email, $phone, $address, $date_of_birth, $gender, $previous_school, $class_applying, $documents_path, $application_fee_paid, $status, $notes, $processed_by, $processed_at, $id]);
                    } else {
                        $stmt = $pdo->prepare("UPDATE admissions SET student_name = ?, parent_name = ?, email = ?, phone = ?, address = ?, date_of_birth = ?, gender = ?, previous_school = ?, class_applying = ?, application_fee_paid = ?, status = ?, notes = ?, processed_by = ?, processed_at = ? WHERE id = ?");
                        $stmt->execute([$student_name, $parent_name, $email, $phone, $address, $date_of_birth, $gender, $previous_school, $class_applying, $application_fee_paid, $status, $notes, $processed_by, $processed_at, $id]);
                    }
                    $message = 'Admission application updated successfully!';
                    logActivity('admission_update', "Updated admission application: $student_name");
                }
                $action = 'list';
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
    
    if ($action == 'delete' && $id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM admissions WHERE id = ?");
            $stmt->execute([$id]);
            $message = 'Admission application deleted successfully!';
            logActivity('admission_delete', "Deleted admission application ID: $id");
            $action = 'list';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

// Get admission for editing
$admission_item = null;
if ($action == 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM admissions WHERE id = ?");
    $stmt->execute([$id]);
    $admission_item = $stmt->fetch();
    if (!$admission_item) {
        $action = 'list';
        $error = 'Admission application not found.';
    }
}

// Get all admissions for listing
if ($action == 'list') {
    $filter = $_GET['filter'] ?? 'all';
    $class_filter = $_GET['class'] ?? '';
    
    $where_clause = "";
    $params = [];
    
    if ($filter !== 'all') {
        $where_clause .= "WHERE status = ?";
        $params[] = $filter;
    }
    
    if ($class_filter) {
        $where_clause .= ($where_clause ? " AND" : "WHERE") . " class_applying = ?";
        $params[] = $class_filter;
    }
    
    $stmt = $pdo->prepare("SELECT a.*, u.full_name as processed_by_name FROM admissions a 
                          LEFT JOIN admin_users u ON a.processed_by = u.id 
                          $where_clause ORDER BY a.created_at DESC");
    $stmt->execute($params);
    $admissions_list = $stmt->fetchAll();
    
    // Get distinct classes for filter
    $stmt = $pdo->query("SELECT DISTINCT class_applying FROM admissions ORDER BY class_applying");
    $classes = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

$page_title = 'Manage Admissions';
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
        .admin-content { padding: 2rem; max-width: 1400px; margin: 0 auto; }
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
        .table { width: 100%; border-collapse: collapse; margin-top: 1rem; font-size: 0.9rem; }
        .table th, .table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #dee2e6; }
        .table th { background: #f8f9fa; font-weight: 600; }
        .table tr:hover { background: #f8f9fa; }
        .status-badge { padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.8rem; font-weight: 500; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-rejected { background: #f8d7da; color: #721c24; }
        .status-enrolled { background: #cce5ff; color: #0066cc; }
        .file-upload { position: relative; display: inline-block; }
        .file-upload input[type=file] { position: absolute; opacity: 0; width: 100%; height: 100%; cursor: pointer; }
        .file-upload-button { display: inline-block; padding: 0.5rem 1rem; background: #007bff; color: white; border-radius: 3px; cursor: pointer; }
        .breadcrumb { margin-bottom: 2rem; }
        .breadcrumb a { color: #1a472a; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
        .filters { display: flex; gap: 1rem; margin-bottom: 2rem; align-items: center; flex-wrap: wrap; }
        .filters select { padding: 0.5rem; border: 1px solid #ddd; border-radius: 3px; }
        .fee-badge { background: #e7f3ff; color: #0066cc; padding: 0.2rem 0.5rem; border-radius: 10px; font-size: 0.75rem; }
        .age-display { font-size: 0.8rem; color: #666; }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="container">
            <nav class="admin-nav">
                <h1><i class="fas fa-file-alt"></i> <?php echo $page_title; ?></h1>
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
                Admissions Management
            <?php elseif ($action == 'add'): ?>
                <a href="manage-admissions.php">Admissions Management</a> / Add Application
            <?php elseif ($action == 'edit'): ?>
                <a href="manage-admissions.php">Admissions Management</a> / Edit Application
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
        <!-- Admissions List -->
        <div class="admin-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>Admission Applications</h2>
                <a href="?action=add" class="btn">
                    <i class="fas fa-plus"></i> Add Application
                </a>
            </div>

            <!-- Filters -->
            <div class="filters">
                <label style="margin: 0;">Filter by Status:</label>
                <select onchange="window.location.href='?filter=' + this.value + (document.getElementById('class_filter').value ? '&class=' + document.getElementById('class_filter').value : '')">
                    <option value="all" <?php echo ($filter == 'all') ? 'selected' : ''; ?>>All Status</option>
                    <option value="pending" <?php echo ($filter == 'pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="approved" <?php echo ($filter == 'approved') ? 'selected' : ''; ?>>Approved</option>
                    <option value="rejected" <?php echo ($filter == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                    <option value="enrolled" <?php echo ($filter == 'enrolled') ? 'selected' : ''; ?>>Enrolled</option>
                </select>

                <label style="margin: 0;">Filter by Class:</label>
                <select id="class_filter" onchange="window.location.href='?class=' + this.value + '&filter=<?php echo $filter; ?>'">
                    <option value="">All Classes</option>
                    <?php foreach ($classes as $class): ?>
                    <option value="<?php echo $class; ?>" <?php echo ($class_filter == $class) ? 'selected' : ''; ?>><?php echo $class; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if (!empty($admissions_list)): ?>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Parent/Guardian</th>
                            <th>Contact</th>
                            <th>Class Applying</th>
                            <th>Fee Status</th>
                            <th>Status</th>
                            <th>Applied Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($admissions_list as $admission): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($admission['student_name']); ?></strong>
                                <br><small class="age-display">
                                    <?php 
                                    $age = date_diff(date_create($admission['date_of_birth']), date_create('today'))->y;
                                    echo $age . ' years old, ' . ucfirst($admission['gender']);
                                    ?>
                                </small>
                                <?php if ($admission['previous_school']): ?>
                                <br><small style="color: #666;">From: <?php echo htmlspecialchars($admission['previous_school']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($admission['parent_name']); ?>
                                <br><small style="color: #666;"><?php echo htmlspecialchars($admission['address']); ?></small>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($admission['email']); ?>
                                <br><small style="color: #666;"><?php echo htmlspecialchars($admission['phone']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($admission['class_applying']); ?></td>
                            <td>
                                <?php if ($admission['application_fee_paid']): ?>
                                <span class="fee-badge">Paid</span>
                                <?php else: ?>
                                <span class="status-badge status-pending">Unpaid</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo $admission['status']; ?>">
                                    <?php echo ucfirst($admission['status']); ?>
                                </span>
                                <?php if ($admission['processed_by_name'] && $admission['status'] !== 'pending'): ?>
                                <br><small style="color: #666;">by <?php echo htmlspecialchars($admission['processed_by_name']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo formatDate($admission['created_at'], 'M j, Y'); ?></td>
                            <td>
                                <a href="?action=edit&id=<?php echo $admission['id']; ?>" class="btn btn-small btn-secondary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="?action=delete&id=<?php echo $admission['id']; ?>" 
                                   class="btn btn-small btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this application?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p style="text-align: center; color: #666; padding: 3rem;">No admission applications found.</p>
            <?php endif; ?>
        </div>

        <?php elseif ($action == 'add' || $action == 'edit'): ?>
        <!-- Add/Edit Form -->
        <div class="admin-card">
            <h2><?php echo $action == 'add' ? 'Add Admission Application' : 'Edit Admission Application'; ?></h2>

            <form method="POST" enctype="multipart/form-data">
                <h3 style="color: #1a472a; border-bottom: 2px solid #1a472a; padding-bottom: 0.5rem;">Student Information</h3>
                
                <div class="grid-2">
                    <div class="form-group">
                        <label for="student_name">Student Name *</label>
                        <input type="text" id="student_name" name="student_name" required 
                               value="<?php echo $admission_item ? htmlspecialchars($admission_item['student_name']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth *</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" required 
                               value="<?php echo $admission_item ? $admission_item['date_of_birth'] : ''; ?>">
                    </div>
                </div>

                <div class="grid-3">
                    <div class="form-group">
                        <label for="gender">Gender *</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male" <?php echo ($admission_item && $admission_item['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                            <option value="female" <?php echo ($admission_item && $admission_item['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="class_applying">Class Applying For *</label>
                        <select id="class_applying" name="class_applying" required>
                            <option value="">Select Class</option>
                            <option value="S1" <?php echo ($admission_item && $admission_item['class_applying'] == 'S1') ? 'selected' : ''; ?>>Senior 1 (S1)</option>
                            <option value="S2" <?php echo ($admission_item && $admission_item['class_applying'] == 'S2') ? 'selected' : ''; ?>>Senior 2 (S2)</option>
                            <option value="S3" <?php echo ($admission_item && $admission_item['class_applying'] == 'S3') ? 'selected' : ''; ?>>Senior 3 (S3)</option>
                            <option value="S4" <?php echo ($admission_item && $admission_item['class_applying'] == 'S4') ? 'selected' : ''; ?>>Senior 4 (S4)</option>
                            <option value="S5" <?php echo ($admission_item && $admission_item['class_applying'] == 'S5') ? 'selected' : ''; ?>>Senior 5 (S5)</option>
                            <option value="S6" <?php echo ($admission_item && $admission_item['class_applying'] == 'S6') ? 'selected' : ''; ?>>Senior 6 (S6)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="previous_school">Previous School</label>
                        <input type="text" id="previous_school" name="previous_school" 
                               value="<?php echo $admission_item ? htmlspecialchars($admission_item['previous_school']) : ''; ?>">
                    </div>
                </div>

                <h3 style="color: #1a472a; border-bottom: 2px solid #1a472a; padding-bottom: 0.5rem; margin-top: 2rem;">Parent/Guardian Information</h3>
                
                <div class="form-group">
                    <label for="parent_name">Parent/Guardian Name *</label>
                    <input type="text" id="parent_name" name="parent_name" required 
                           value="<?php echo $admission_item ? htmlspecialchars($admission_item['parent_name']) : ''; ?>">
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo $admission_item ? htmlspecialchars($admission_item['email']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" required 
                               value="<?php echo $admission_item ? htmlspecialchars($admission_item['phone']) : ''; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Home Address *</label>
                    <textarea id="address" name="address" rows="3" required><?php echo $admission_item ? htmlspecialchars($admission_item['address']) : ''; ?></textarea>
                </div>

                <h3 style="color: #1a472a; border-bottom: 2px solid #1a472a; padding-bottom: 0.5rem; margin-top: 2rem;">Application Details</h3>
                
                <div class="form-group">
                    <label for="documents">Supporting Documents</label>
                    <div class="file-upload">
                        <input type="file" id="documents" name="documents" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="file-upload-button">
                            <i class="fas fa-upload"></i> Choose Documents
                        </div>
                    </div>
                    <small style="color: #666;">Upload academic certificates, passport photo, etc. (PDF, JPG, PNG)</small>
                    <?php if ($admission_item && $admission_item['documents_path']): ?>
                    <p style="margin-top: 0.5rem;">Current: <?php echo htmlspecialchars($admission_item['documents_path']); ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="notes">Additional Notes</label>
                    <textarea id="notes" name="notes" rows="3" 
                              placeholder="Any additional information or special considerations..."><?php echo $admission_item ? htmlspecialchars($admission_item['notes']) : ''; ?></textarea>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="status">Application Status</label>
                        <select id="status" name="status">
                            <option value="pending" <?php echo ($admission_item && $admission_item['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="approved" <?php echo ($admission_item && $admission_item['status'] == 'approved') ? 'selected' : ''; ?>>Approved</option>
                            <option value="rejected" <?php echo ($admission_item && $admission_item['status'] == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                            <option value="enrolled" <?php echo ($admission_item && $admission_item['status'] == 'enrolled') ? 'selected' : ''; ?>>Enrolled</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="application_fee_paid" value="1" <?php echo ($admission_item && $admission_item['application_fee_paid']) ? 'checked' : ''; ?>>
                            Application Fee Paid
                        </label>
                        <small style="color: #666;">Check if application fee has been paid</small>
                    </div>
                </div>

                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn">
                        <i class="fas fa-save"></i> <?php echo $action == 'add' ? 'Add Application' : 'Update Application'; ?>
                    </button>
                    <a href="manage-admissions.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </main>

    <script>
        // File upload feedback
        document.getElementById('documents').addEventListener('change', function() {
            const fileName = this.files[0]?.name;
            if (fileName) {
                document.querySelector('.file-upload-button').innerHTML = '<i class="fas fa-check"></i> ' + fileName;
            }
        });
    </script>
</body>
</html>
