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
        $role = $_POST['role'] ?? 'admin';
        $status = $_POST['status'] ?? 'active';
        $password = $_POST['password'] ?? '';
        
        // Validation
        if (empty($username) || empty($full_name) || empty($email)) {
            $error = 'Please fill in all required fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } elseif ($action == 'add' && empty($password)) {
            $error = 'Password is required for new users.';
        } elseif (!empty($password) && strlen($password) < 6) {
            $error = 'Password must be at least 6 characters long.';
        } else {
            try {
                if ($action == 'add') {
                    // Check if username or email already exists
                    $stmt = $pdo->prepare("SELECT id FROM admin_users WHERE username = ? OR email = ?");
                    $stmt->execute([$username, $email]);
                    if ($stmt->fetch()) {
                        $error = 'Username or email already exists.';
                    } else {
                        // Add new user
                        $stmt = $pdo->prepare("INSERT INTO admin_users (username, password, full_name, email, role, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
                        $stmt->execute([
                            $username,
                            password_hash($password, PASSWORD_DEFAULT),
                            $full_name,
                            $email,
                            $role,
                            $status
                        ]);
                        
                        // Log activity
                        logActivity('Admin', 'User created: ' . $username);
                        $message = 'User created successfully.';
                        $action = 'list'; // Redirect to list view
                    }
                } elseif ($action == 'edit') {
                    // Update existing user
                    if (!empty($password)) {
                        $stmt = $pdo->prepare("UPDATE admin_users SET username = ?, password = ?, full_name = ?, email = ?, role = ?, status = ?, updated_at = NOW() WHERE id = ?");
                        $stmt->execute([
                            $username,
                            password_hash($password, PASSWORD_DEFAULT),
                            $full_name,
                            $email,
                            $role,
                            $status,
                            $id
                        ]);
                    } else {
                        $stmt = $pdo->prepare("UPDATE admin_users SET username = ?, full_name = ?, email = ?, role = ?, status = ?, updated_at = NOW() WHERE id = ?");
                        $stmt->execute([
                            $username,
                            $full_name,
                            $email,
                            $role,
                            $status,
                            $id
                        ]);
                    }
                    
                    // Log activity
                    logActivity('Admin', 'User updated: ' . $username);
                    $message = 'User updated successfully.';
                    $action = 'list'; // Redirect to list view
                }
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    } elseif ($action == 'delete') {
        try {
            // Get user info before deletion for logging
            $stmt = $pdo->prepare("SELECT username FROM admin_users WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch();
            
            if ($user) {
                // Don't allow deletion of current user
                if ($id == $_SESSION['user_id']) {
                    $error = 'You cannot delete your own account.';
                } else {
                    // Delete user
                    $stmt = $pdo->prepare("DELETE FROM admin_users WHERE id = ?");
                    $stmt->execute([$id]);
                    
                    // Log activity
                    logActivity('Admin', 'User deleted: ' . $user['username']);
                    $message = 'User deleted successfully.';
                }
            } else {
                $error = 'User not found.';
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
        $action = 'list'; // Redirect to list view
    }
}

// Get current user for editing
$current_user = null;
if ($action == 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE id = ?");
    $stmt->execute([$id]);
    $current_user = $stmt->fetch();
    if (!$current_user) {
        $error = 'User not found.';
        $action = 'list';
    }
}

// Get all users for listing
if ($action == 'list') {
    $stmt = $pdo->prepare("SELECT * FROM admin_users ORDER BY created_at DESC");
    $stmt->execute();
    $users = $stmt->fetchAll();
}

$page_title = 'Manage Users - Admin Panel';
include '../includes/header.php';
?>

<div class="admin-wrapper">
    <?php include 'sidebar.php'; ?>
    
    <div class="admin-content">
        <div class="admin-header">
            <div class="admin-header-content">
                <h1>
                    <i class="fas fa-users"></i>
                    <?php 
                    switch($action) {
                        case 'add': echo 'Add New User'; break;
                        case 'edit': echo 'Edit User'; break;
                        default: echo 'Manage Users'; break;
                    }
                    ?>
                </h1>
                <?php if ($action == 'list'): ?>
                <a href="?action=add" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New User
                </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="admin-main">
            <?php if ($message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
            <?php endif; ?>

            <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <?php if ($action == 'add' || $action == 'edit'): ?>
            <!-- Add/Edit User Form -->
            <div class="card">
                <div class="card-header">
                    <h3><?php echo $action == 'add' ? 'Add New User' : 'Edit User'; ?></h3>
                </div>
                <div class="card-body">
                    <form method="POST" class="row g-3">
                        <div class="col-md-6">
                            <label for="username" class="form-label">Username *</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?php echo htmlspecialchars($current_user['username'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="full_name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   value="<?php echo htmlspecialchars($current_user['full_name'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($current_user['email'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-control" id="role" name="role">
                                <option value="admin" <?php echo ($current_user['role'] ?? '') == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                <option value="super_admin" <?php echo ($current_user['role'] ?? '') == 'super_admin' ? 'selected' : ''; ?>>Super Admin</option>
                                <option value="editor" <?php echo ($current_user['role'] ?? '') == 'editor' ? 'selected' : ''; ?>>Editor</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">
                                Password <?php echo $action == 'add' ? '*' : '(Leave blank to keep current)'; ?>
                            </label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   <?php echo $action == 'add' ? 'required' : ''; ?>>
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="active" <?php echo ($current_user['status'] ?? 'active') == 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo ($current_user['status'] ?? '') == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                <?php echo $action == 'add' ? 'Create User' : 'Update User'; ?>
                            </button>
                            <a href="?action=list" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <?php else: ?>
            <!-- Users List -->
            <div class="card">
                <div class="card-header">
                    <h3>All Users</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($users)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No users found. <a href="?action=add">Add the first user</a>.</p>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo $user['id']; ?></td>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $user['role'] == 'super_admin' ? 'danger' : 'primary'; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $user['role'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo $user['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($user['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="?action=edit&id=<?php echo $user['id']; ?>" class="btn btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <a href="?action=delete&id=<?php echo $user['id']; ?>" 
                                               class="btn btn-outline-danger"
                                               onclick="return confirm('Are you sure you want to delete this user?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.badge {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    border-radius: 0.375rem;
}
.badge-primary { background-color: #0d6efd; color: white; }
.badge-danger { background-color: #dc3545; color: white; }
.badge-success { background-color: #198754; color: white; }
.badge-secondary { background-color: #6c757d; color: white; }

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>

<?php include '../includes/footer.php'; ?>
