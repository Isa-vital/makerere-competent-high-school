<?php
// Manage Contact Messages - Admin panel for Makerere Competent High School
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if user is logged in
requireAdmin();

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? 0;
$message = '';
$error = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($action == 'mark_read' && $id) {
        try {
            $stmt = $pdo->prepare("UPDATE contact_messages SET status = 'read' WHERE id = ?");
            $stmt->execute([$id]);
            $message = 'Message marked as read.';
            logActivity('message_read', "Marked message ID $id as read");
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
    
    if ($action == 'delete' && $id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
            $stmt->execute([$id]);
            $message = 'Message deleted successfully!';
            logActivity('message_delete', "Deleted message ID: $id");
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
    
    if ($action == 'bulk_action') {
        $bulk_action = $_POST['bulk_action'];
        $selected_ids = $_POST['selected_messages'] ?? [];
        
        if (!empty($selected_ids) && $bulk_action) {
            try {
                $placeholders = implode(',', array_fill(0, count($selected_ids), '?'));
                
                if ($bulk_action == 'mark_read') {
                    $stmt = $pdo->prepare("UPDATE contact_messages SET status = 'read' WHERE id IN ($placeholders)");
                    $stmt->execute($selected_ids);
                    $message = count($selected_ids) . ' messages marked as read.';
                } elseif ($bulk_action == 'delete') {
                    $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id IN ($placeholders)");
                    $stmt->execute($selected_ids);
                    $message = count($selected_ids) . ' messages deleted.';
                }
                
                logActivity('bulk_message_action', "Performed $bulk_action on " . count($selected_ids) . " messages");
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
    $action = 'list';
}

// Get message for viewing
$message_item = null;
if ($action == 'view' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id = ?");
    $stmt->execute([$id]);
    $message_item = $stmt->fetch();
    
    if ($message_item && $message_item['status'] == 'new') {
        // Mark as read when viewing
        $stmt = $pdo->prepare("UPDATE contact_messages SET status = 'read' WHERE id = ?");
        $stmt->execute([$id]);
        $message_item['status'] = 'read';
    }
    
    if (!$message_item) {
        $action = 'list';
        $error = 'Message not found.';
    }
}

// Get messages list
if ($action == 'list') {
    $status_filter = $_GET['status'] ?? '';
    $search = $_GET['search'] ?? '';
    $page = max(1, $_GET['page'] ?? 1);
    $per_page = 20;
    $offset = ($page - 1) * $per_page;
    
    $sql = "SELECT * FROM contact_messages WHERE 1=1";
    $count_sql = "SELECT COUNT(*) FROM contact_messages WHERE 1=1";
    $params = [];
    
    if ($status_filter) {
        $sql .= " AND status = ?";
        $count_sql .= " AND status = ?";
        $params[] = $status_filter;
    }
    
    if ($search) {
        $sql .= " AND (name LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ?)";
        $count_sql .= " AND (name LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ?)";
        $search_param = "%$search%";
        $params = array_merge($params, [$search_param, $search_param, $search_param, $search_param]);
    }
    
    // Get total count
    $stmt = $pdo->prepare($count_sql);
    $stmt->execute($params);
    $total_messages = $stmt->fetchColumn();
    $total_pages = ceil($total_messages / $per_page);
    
    // Get messages for current page
    $sql .= " ORDER BY created_at DESC LIMIT $per_page OFFSET $offset";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $messages_list = $stmt->fetchAll();
    
    // Get counts for status filter
    $stmt = $pdo->query("SELECT status, COUNT(*) as count FROM contact_messages GROUP BY status");
    $status_counts = [];
    while ($row = $stmt->fetch()) {
        $status_counts[$row['status']] = $row['count'];
    }
}

$page_title = 'Contact Messages';
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
        .status-new { background: #f8d7da; color: #721c24; }
        .status-read { background: #d1ecf1; color: #0c5460; }
        .status-replied { background: #d4edda; color: #155724; }
        .filters { display: flex; gap: 1rem; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; }
        .filters select, .filters input { padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px; }
        .pagination { display: flex; justify-content: center; gap: 0.5rem; margin-top: 2rem; }
        .pagination a, .pagination span { padding: 0.5rem 1rem; border: 1px solid #ddd; border-radius: 5px; text-decoration: none; color: #333; }
        .pagination a:hover { background: #f8f9fa; }
        .pagination .current { background: #1a472a; color: white; border-color: #1a472a; }
        .breadcrumb { margin-bottom: 2rem; }
        .breadcrumb a { color: #1a472a; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        .message-detail { background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; }
        .message-meta { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .message-meta-item { background: white; padding: 1rem; border-radius: 5px; }
        .message-meta-item strong { color: #1a472a; }
        .bulk-actions { display: flex; gap: 1rem; align-items: center; margin-bottom: 1rem; }
        .bulk-actions select { padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .stat-item { background: white; padding: 1rem; border-radius: 8px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .stat-number { font-size: 1.5rem; font-weight: bold; color: #1a472a; }
        .stat-label { font-size: 0.9rem; color: #666; }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="container">
            <nav class="admin-nav">
                <h1><i class="fas fa-envelope"></i> <?php echo $page_title; ?></h1>
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
                Contact Messages
            <?php elseif ($action == 'view'): ?>
                <a href="manage-messages.php">Contact Messages</a> / View Message
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
        <!-- Messages List -->
        
        <!-- Statistics -->
        <?php if (!empty($status_counts)): ?>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number"><?php echo array_sum($status_counts); ?></div>
                <div class="stat-label">Total Messages</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $status_counts['new'] ?? 0; ?></div>
                <div class="stat-label">New Messages</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $status_counts['read'] ?? 0; ?></div>
                <div class="stat-label">Read Messages</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $status_counts['replied'] ?? 0; ?></div>
                <div class="stat-label">Replied</div>
            </div>
        </div>
        <?php endif; ?>

        <div class="admin-card">
            <h2>Contact Messages</h2>

            <!-- Filters -->
            <div class="filters">
                <form method="GET" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                    <select name="status" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="new" <?php echo $status_filter == 'new' ? 'selected' : ''; ?>>New</option>
                        <option value="read" <?php echo $status_filter == 'read' ? 'selected' : ''; ?>>Read</option>
                        <option value="replied" <?php echo $status_filter == 'replied' ? 'selected' : ''; ?>>Replied</option>
                    </select>
                    
                    <input type="text" name="search" placeholder="Search messages..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                    
                    <button type="submit" class="btn btn-small">
                        <i class="fas fa-search"></i> Search
                    </button>
                    
                    <?php if ($status_filter || $search): ?>
                    <a href="manage-messages.php" class="btn btn-small btn-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                    <?php endif; ?>
                </form>
            </div>

            <?php if (!empty($messages_list)): ?>
            <form method="POST" action="?action=bulk_action">
                <!-- Bulk Actions -->
                <div class="bulk-actions">
                    <input type="checkbox" id="selectAll" onchange="toggleAll()">
                    <label for="selectAll">Select All</label>
                    
                    <select name="bulk_action">
                        <option value="">Bulk Actions</option>
                        <option value="mark_read">Mark as Read</option>
                        <option value="delete">Delete</option>
                    </select>
                    
                    <button type="submit" class="btn btn-small" onclick="return confirm('Are you sure?')">
                        Apply
                    </button>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th width="30"><input type="checkbox" id="selectAllHeader" onchange="toggleAll()"></th>
                            <th>From</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages_list as $msg): ?>
                        <tr class="<?php echo $msg['status'] == 'new' ? 'font-weight-bold' : ''; ?>">
                            <td>
                                <input type="checkbox" name="selected_messages[]" 
                                       value="<?php echo $msg['id']; ?>" class="message-checkbox">
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($msg['name']); ?></strong>
                                <br><small style="color: #666;"><?php echo htmlspecialchars($msg['email']); ?></small>
                                <?php if ($msg['phone']): ?>
                                <br><small style="color: #666;"><?php echo htmlspecialchars($msg['phone']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="?action=view&id=<?php echo $msg['id']; ?>" style="text-decoration: none; color: #333;">
                                    <?php echo htmlspecialchars($msg['subject']); ?>
                                </a>
                                <br><small style="color: #666;">
                                    <?php echo htmlspecialchars(truncateText($msg['message'], 80)); ?>
                                </small>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo $msg['status']; ?>">
                                    <?php echo ucfirst($msg['status']); ?>
                                </span>
                            </td>
                            <td><?php echo formatDate($msg['created_at'], 'M j, Y g:i A'); ?></td>
                            <td>
                                <a href="?action=view&id=<?php echo $msg['id']; ?>" class="btn btn-small btn-secondary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $msg['id']; ?>">
                                    <button type="submit" class="btn btn-small btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this message?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?><?php echo $status_filter ? '&status=' . $status_filter : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                    <i class="fas fa-chevron-left"></i> Previous
                </a>
                <?php endif; ?>

                <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                <?php if ($i == $page): ?>
                <span class="current"><?php echo $i; ?></span>
                <?php else: ?>
                <a href="?page=<?php echo $i; ?><?php echo $status_filter ? '&status=' . $status_filter : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                    <?php echo $i; ?>
                </a>
                <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?><?php echo $status_filter ? '&status=' . $status_filter : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                    Next <i class="fas fa-chevron-right"></i>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php else: ?>
            <p style="text-align: center; color: #666; padding: 3rem;">
                <?php echo ($status_filter || $search) ? 'No messages found matching your criteria.' : 'No messages received yet.'; ?>
            </p>
            <?php endif; ?>
        </div>

        <?php elseif ($action == 'view' && $message_item): ?>
        <!-- View Message -->
        <div class="admin-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>Message Details</h2>
                <div>
                    <a href="manage-messages.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Messages
                    </a>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo $message_item['id']; ?>">
                        <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to delete this message?')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>

            <div class="message-meta">
                <div class="message-meta-item">
                    <strong>From:</strong><br>
                    <?php echo htmlspecialchars($message_item['name']); ?>
                </div>
                <div class="message-meta-item">
                    <strong>Email:</strong><br>
                    <a href="mailto:<?php echo htmlspecialchars($message_item['email']); ?>">
                        <?php echo htmlspecialchars($message_item['email']); ?>
                    </a>
                </div>
                <?php if ($message_item['phone']): ?>
                <div class="message-meta-item">
                    <strong>Phone:</strong><br>
                    <a href="tel:<?php echo htmlspecialchars($message_item['phone']); ?>">
                        <?php echo htmlspecialchars($message_item['phone']); ?>
                    </a>
                </div>
                <?php endif; ?>
                <div class="message-meta-item">
                    <strong>Date:</strong><br>
                    <?php echo formatDate($message_item['created_at'], 'F j, Y g:i A'); ?>
                </div>
                <div class="message-meta-item">
                    <strong>Status:</strong><br>
                    <span class="status-badge status-<?php echo $message_item['status']; ?>">
                        <?php echo ucfirst($message_item['status']); ?>
                    </span>
                </div>
            </div>

            <div class="message-detail">
                <h3><?php echo htmlspecialchars($message_item['subject']); ?></h3>
                <div style="margin-top: 1rem; line-height: 1.6;">
                    <?php echo nl2br(htmlspecialchars($message_item['message'])); ?>
                </div>
            </div>

            <div style="margin-top: 2rem;">
                <a href="mailto:<?php echo htmlspecialchars($message_item['email']); ?>?subject=Re: <?php echo urlencode($message_item['subject']); ?>" 
                   class="btn">
                    <i class="fas fa-reply"></i> Reply via Email
                </a>
            </div>
        </div>
        <?php endif; ?>
    </main>

    <script>
        function toggleAll() {
            const checkboxes = document.querySelectorAll('.message-checkbox');
            const selectAll = document.getElementById('selectAll');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        }

        // Auto-refresh every 30 seconds to check for new messages
        setTimeout(() => {
            if (window.location.href.indexOf('action=list') !== -1 || window.location.href.indexOf('manage-messages.php') !== -1) {
                location.reload();
            }
        }, 30000);
    </script>
</body>
</html>
