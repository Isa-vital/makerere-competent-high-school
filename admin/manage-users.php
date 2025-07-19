<?php
// Manage Admin Users - Admin panel for Makerere Competent High School
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
        $username = sanitizeInput($_POST['username']);
        $full_name = sanitizeInput($_POST['full_name']);
        $email = sanitizeInput($_POST['email']);
        $status = $_POST['status'];
        $password = $_POST['password'];
        
        // Validation
        if (empty($username) || empty($full_name) || empty($email)) {
            $error = 'Please fill in all required fields.';
        } elseif (!isValidEmail($email)) {
            $error = 'Please enter a valid email address.';
        } elseif ($action == 'add' && empty($password)) {
            $error = 'Password is required for new users.';
        } elseif (!empty($password) && strlen($password) < PASSWORD_MIN_LENGTH) {
            $error = 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters long.';
        } else {
            try {
                if ($action == 'add') {
                    // Check if username or email already exists
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM admin_users WHERE username = ? OR email = ?");
                    $stmt->execute([$username, $email]);
                    if ($stmt->fetchColumn() > 0) {
                        $error = 'Username or email already exists.';
                    } else {
                        $hashed_password = hashPassword($password);
                        $stmt = $pdo->prepare("INSERT INTO admin_users (username, password, full_name, email, status) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$username, $hashed_password, $full_name, $email, $status]);
                        $message = 'Admin user added successfully!';
                        logActivity('admin_add', "Added admin user: $username");
                        $action = 'list';
                    }
                } else {
                    // Check if username or email already exists (excluding current user)
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM admin_users WHERE (username = ? OR email = ?) AND id != ?");
                    $stmt->execute([$username, $email, $id]);
                    if ($stmt->fetchColumn() > 0) {
                        $error = 'Username or email already exists.';
                    } else {
                        if (!empty($password)) {
                            $hashed_password = hashPassword($password);
                            $stmt = $pdo->prepare("UPDATE admin_users SET username = ?, password = ?, full_name = ?, email = ?, status = ? WHERE id = ?");
                            $stmt->execute([$username, $hashed_password, $full_name, $email, $status, $id]);
                        } else {
                            $stmt = $pdo->prepare("UPDATE admin_users SET username = ?, full_name = ?, email = ?, status = ? WHERE id = ?");
                            $stmt->execute([$username, $full_name, $email, $status, $id]);
                        }
                        $message = 'Admin user updated successfully!';
                        logActivity('admin_update', "Updated admin user: $username");
                        $action = 'list';
                    }
                }
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
    
    if ($action == 'delete' && $id) {
        // Don't allow deleting the last admin or current user
        if ($id == $_SESSION['admin_id']) {
            $error = 'You cannot delete your own account.';
        } else {
            $stmt = $pdo->query("SELECT COUNT(*) FROM admin_users WHERE status = 'active'");
            $active_count = $stmt->fetchColumn();
            
            if ($active_count <= 1) {
                $error = 'Cannot delete the last active admin user.';
            } else {
                try {
                    $stmt = $pdo->prepare("DELETE FROM admin_users WHERE id = ?");
                    $stmt->execute([$id]);
                    $message = 'Admin user deleted successfully!';
                    logActivity('admin_delete', "Deleted admin user ID: $id");
                } catch (PDOException $e) {
                    $error = 'Database error: ' . $e->getMessage();
                }
            }
        }
        $action = 'list';
    }
}

// Get admin user for editing
$admin_user = null;
if ($action == 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE id = ?");
    $stmt->execute([$id]);
    $admin_user = $stmt->fetch();
    if (!$admin_user) {
        $action = 'list';
        $error = 'Admin user not found.';
    }
}

// Get all admin users for listing
if ($action == 'list') {
    $stmt = $pdo->query("SELECT * FROM admin_users ORDER BY created_at DESC");
    $admin_users = $stmt->fetchAll();
}

$page_title = 'Manage Admin Users';
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
        .form-group input, .form-group select { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; }
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
        .status-active { background: #d4edda; color: #155724; }
        .status-inactive { background: #f8d7da; color: #721c24; }
        .breadcrumb { margin-bottom: 2rem; }
        .breadcrumb a { color: #1a472a; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        .password-strength { margin-top: 0.5rem; height: 5px; border-radius: 3px; background: #ddd; overflow: hidden; }
        .password-strength-bar { height: 100%; transition: width 0.3s, background-color 0.3s; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
            .admin-content { padding: 1rem; }
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="container">
            <nav class="admin-nav">
                <h1><i class="fas fa-users"></i> <?php echo $page_title; ?></h1>
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
                Admin Users
            <?php elseif ($action == 'add'): ?>
                <a href="manage-users.php">Admin Users</a> / Add User
            <?php elseif ($action == 'edit'): ?>
                <a href="manage-users.php">Admin Users</a> / Edit User
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
        <!-- Users List -->
        <div class="admin-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>Admin Users</h2>
                <a href="?action=add" class="btn">
                    <i class="fas fa-plus"></i> Add New User
                </a>
            </div>

            <?php if (!empty($admin_users)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admin_users as $user): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($user['full_name']); ?></strong>
                            <?php if ($user['id'] == $_SESSION['admin_id']): ?>
                            <br><small style="color: #28a745; font-weight: 500;">(Current User)</small>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $user['status']; ?>">
                                <?php echo ucfirst($user['status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php echo $user['last_login'] ? formatDate($user['last_login'], 'M j, Y g:i A') : 'Never'; ?>
                        </td>
                        <td>
                            <a href="?action=edit&id=<?php echo $user['id']; ?>" class="btn btn-small btn-secondary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <?php if ($user['id'] != $_SESSION['admin_id']): ?>
                            <a href="?action=delete&id=<?php echo $user['id']; ?>" 
                               class="btn btn-small btn-danger"
                               onclick="return confirm('Are you sure you want to delete this user?')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p style="text-align: center; color: #666; padding: 3rem;">No admin users found.</p>
            <?php endif; ?>
        </div>

        <?php elseif ($action == 'add' || $action == 'edit'): ?>
        <!-- Add/Edit Form -->
        <div class="admin-card">
            <h2><?php echo $action == 'add' ? 'Add New Admin User' : 'Edit Admin User'; ?></h2>

            <form method="POST" id="adminForm">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="full_name">Full Name *</label>
                        <input type="text" id="full_name" name="full_name" required 
                               value="<?php echo $admin_user ? htmlspecialchars($admin_user['full_name']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="username">Username *</label>
                        <input type="text" id="username" name="username" required 
                               value="<?php echo $admin_user ? htmlspecialchars($admin_user['username']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo $admin_user ? htmlspecialchars($admin_user['email']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="active" <?php echo ($admin_user && $admin_user['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo ($admin_user && $admin_user['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">
                        Password <?php echo $action == 'add' ? '*' : '(leave blank to keep current)'; ?>
                    </label>
                    <input type="password" id="password" name="password" 
                           <?php echo $action == 'add' ? 'required' : ''; ?>
                           minlength="<?php echo PASSWORD_MIN_LENGTH; ?>"
                           placeholder="<?php echo $action == 'edit' ? 'Leave blank to keep current password' : 'Enter password'; ?>">
                    <div class="password-strength">
                        <div class="password-strength-bar" id="strengthBar"></div>
                    </div>
                    <small style="color: #666;">Password must be at least <?php echo PASSWORD_MIN_LENGTH; ?> characters long.</small>
                </div>

                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn">
                        <i class="fas fa-save"></i> <?php echo $action == 'add' ? 'Add User' : 'Update User'; ?>
                    </button>
                    <a href="manage-users.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </main>

    <script>
        // Password strength indicator
        document.getElementById('password')?.addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('strengthBar');
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]+/)) strength++;
            if (password.match(/[A-Z]+/)) strength++;
            if (password.match(/[0-9]+/)) strength++;
            if (password.match(/[^a-zA-Z0-9]+/)) strength++;
            
            const colors = ['#ddd', '#ff4757', '#ffa502', '#2ed573', '#1e90ff'];
            const widths = ['0%', '20%', '40%', '60%', '80%', '100%'];
            
            strengthBar.style.width = widths[strength];
            strengthBar.style.backgroundColor = colors[strength];
        });

        // Form validation
        document.getElementById('adminForm')?.addEventListener('submit', function(e) {
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (username.length < 3) {
                e.preventDefault();
                alert('Username must be at least 3 characters long.');
                return;
            }
            
            if (!email.includes('@')) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                return;
            }
            
            <?php if ($action == 'add'): ?>
            if (password.length < <?php echo PASSWORD_MIN_LENGTH; ?>) {
                e.preventDefault();
                alert('Password must be at least <?php echo PASSWORD_MIN_LENGTH; ?> characters long.');
                return;
            }
            <?php endif; ?>
        });
    </script>
</body>
</html>
