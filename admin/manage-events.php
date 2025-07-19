<?php
// Manage Events - Admin panel for Makerere Competent High School
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
        $title = sanitizeInput($_POST['title']);
        $description = $_POST['description'];
        $event_date = $_POST['event_date'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $location = sanitizeInput($_POST['location']);
        $category = sanitizeInput($_POST['category']);
        $status = $_POST['status'];
        $registration_required = isset($_POST['registration_required']) ? 1 : 0;
        $max_participants = $_POST['max_participants'] ? intval($_POST['max_participants']) : null;
        $created_by = $_SESSION['user_id'];
        
        // Handle image upload
        $featured_image = '';
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
            $upload_result = uploadImage($_FILES['featured_image'], 'events');
            if ($upload_result['success']) {
                $featured_image = $upload_result['filename'];
            } else {
                $error = $upload_result['error'];
            }
        }
        
        if (empty($error)) {
            try {
                if ($action == 'add') {
                    $stmt = $pdo->prepare("INSERT INTO events (title, description, event_date, start_time, end_time, location, category, featured_image, status, registration_required, max_participants, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$title, $description, $event_date, $start_time, $end_time, $location, $category, $featured_image, $status, $registration_required, $max_participants, $created_by]);
                    $message = 'Event added successfully!';
                    logActivity('event_add', "Added event: $title");
                } else {
                    if ($featured_image) {
                        $stmt = $pdo->prepare("UPDATE events SET title = ?, description = ?, event_date = ?, start_time = ?, end_time = ?, location = ?, category = ?, featured_image = ?, status = ?, registration_required = ?, max_participants = ? WHERE id = ?");
                        $stmt->execute([$title, $description, $event_date, $start_time, $end_time, $location, $category, $featured_image, $status, $registration_required, $max_participants, $id]);
                    } else {
                        $stmt = $pdo->prepare("UPDATE events SET title = ?, description = ?, event_date = ?, start_time = ?, end_time = ?, location = ?, category = ?, status = ?, registration_required = ?, max_participants = ? WHERE id = ?");
                        $stmt->execute([$title, $description, $event_date, $start_time, $end_time, $location, $category, $status, $registration_required, $max_participants, $id]);
                    }
                    $message = 'Event updated successfully!';
                    logActivity('event_update', "Updated event: $title");
                }
                $action = 'list';
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
    
    if ($action == 'delete' && $id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
            $stmt->execute([$id]);
            $message = 'Event deleted successfully!';
            logActivity('event_delete', "Deleted event ID: $id");
            $action = 'list';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

// Get event for editing
$event_item = null;
if ($action == 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$id]);
    $event_item = $stmt->fetch();
    if (!$event_item) {
        $action = 'list';
        $error = 'Event not found.';
    }
}

// Get all events for listing
if ($action == 'list') {
    $stmt = $pdo->query("SELECT e.*, u.full_name as created_by_name 
                         FROM events e 
                         LEFT JOIN admin_users u ON e.created_by = u.id 
                         ORDER BY e.event_date DESC");
    $events_list = $stmt->fetchAll();
}

$page_title = 'Manage Events';
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
        .status-upcoming { background: #cce5ff; color: #0066cc; }
        .status-ongoing { background: #ffe6cc; color: #cc6600; }
        .status-completed { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .file-upload { position: relative; display: inline-block; }
        .file-upload input[type=file] { position: absolute; opacity: 0; width: 100%; height: 100%; cursor: pointer; }
        .file-upload-button { display: inline-block; padding: 0.5rem 1rem; background: #007bff; color: white; border-radius: 3px; cursor: pointer; }
        .breadcrumb { margin-bottom: 2rem; }
        .breadcrumb a { color: #1a472a; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="container">
            <nav class="admin-nav">
                <h1><i class="fas fa-calendar-alt"></i> <?php echo $page_title; ?></h1>
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
                Events Management
            <?php elseif ($action == 'add'): ?>
                <a href="manage-events.php">Events Management</a> / Add Event
            <?php elseif ($action == 'edit'): ?>
                <a href="manage-events.php">Events Management</a> / Edit Event
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
        <!-- Events List -->
        <div class="admin-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>School Events</h2>
                <a href="?action=add" class="btn">
                    <i class="fas fa-plus"></i> Add New Event
                </a>
            </div>

            <?php if (!empty($events_list)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Date & Time</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events_list as $event): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($event['title']); ?></strong>
                            <?php if ($event['category']): ?>
                            <br><small style="color: #666;"><i class="fas fa-tag"></i> <?php echo htmlspecialchars($event['category']); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo formatDate($event['event_date'], 'M j, Y'); ?>
                            <?php if ($event['start_time']): ?>
                            <br><small style="color: #666;"><?php echo date('g:i A', strtotime($event['start_time'])); ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($event['location'] ?: 'TBA'); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $event['status']; ?>">
                                <?php echo ucfirst($event['status']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($event['created_by_name'] ?: 'System'); ?></td>
                        <td>
                            <a href="?action=edit&id=<?php echo $event['id']; ?>" class="btn btn-small btn-secondary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="?action=delete&id=<?php echo $event['id']; ?>" 
                               class="btn btn-small btn-danger"
                               onclick="return confirm('Are you sure you want to delete this event?')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p style="text-align: center; color: #666; padding: 3rem;">No events found.</p>
            <?php endif; ?>
        </div>

        <?php elseif ($action == 'add' || $action == 'edit'): ?>
        <!-- Add/Edit Form -->
        <div class="admin-card">
            <h2><?php echo $action == 'add' ? 'Add New Event' : 'Edit Event'; ?></h2>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Event Title *</label>
                    <input type="text" id="title" name="title" required 
                           value="<?php echo $event_item ? htmlspecialchars($event_item['title']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4" 
                              placeholder="Event description and details..."><?php echo $event_item ? htmlspecialchars($event_item['description']) : ''; ?></textarea>
                </div>

                <div class="grid-3">
                    <div class="form-group">
                        <label for="event_date">Event Date *</label>
                        <input type="date" id="event_date" name="event_date" required 
                               value="<?php echo $event_item ? $event_item['event_date'] : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="start_time">Start Time</label>
                        <input type="time" id="start_time" name="start_time" 
                               value="<?php echo $event_item ? $event_item['start_time'] : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="end_time">End Time</label>
                        <input type="time" id="end_time" name="end_time" 
                               value="<?php echo $event_item ? $event_item['end_time'] : ''; ?>">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" 
                               placeholder="e.g., School Hall, Sports Ground"
                               value="<?php echo $event_item ? htmlspecialchars($event_item['location']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="category">Category</label>
                        <select id="category" name="category">
                            <option value="">Select Category</option>
                            <option value="Academic" <?php echo ($event_item && $event_item['category'] == 'Academic') ? 'selected' : ''; ?>>Academic</option>
                            <option value="Sports" <?php echo ($event_item && $event_item['category'] == 'Sports') ? 'selected' : ''; ?>>Sports</option>
                            <option value="Cultural" <?php echo ($event_item && $event_item['category'] == 'Cultural') ? 'selected' : ''; ?>>Cultural</option>
                            <option value="Social" <?php echo ($event_item && $event_item['category'] == 'Social') ? 'selected' : ''; ?>>Social</option>
                            <option value="Meeting" <?php echo ($event_item && $event_item['category'] == 'Meeting') ? 'selected' : ''; ?>>Meeting</option>
                            <option value="Ceremony" <?php echo ($event_item && $event_item['category'] == 'Ceremony') ? 'selected' : ''; ?>>Ceremony</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="featured_image">Event Image</label>
                    <div class="file-upload">
                        <input type="file" id="featured_image" name="featured_image" accept="image/*">
                        <div class="file-upload-button">
                            <i class="fas fa-upload"></i> Choose Image
                        </div>
                    </div>
                    <?php if ($event_item && $event_item['featured_image']): ?>
                    <p style="margin-top: 0.5rem;">Current: <?php echo htmlspecialchars($event_item['featured_image']); ?></p>
                    <?php endif; ?>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="upcoming" <?php echo ($event_item && $event_item['status'] == 'upcoming') ? 'selected' : ''; ?>>Upcoming</option>
                            <option value="ongoing" <?php echo ($event_item && $event_item['status'] == 'ongoing') ? 'selected' : ''; ?>>Ongoing</option>
                            <option value="completed" <?php echo ($event_item && $event_item['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                            <option value="cancelled" <?php echo ($event_item && $event_item['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="max_participants">Max Participants (optional)</label>
                        <input type="number" id="max_participants" name="max_participants" min="1"
                               value="<?php echo $event_item ? $event_item['max_participants'] : ''; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="registration_required" value="1" <?php echo ($event_item && $event_item['registration_required']) ? 'checked' : ''; ?>>
                        Registration Required
                    </label>
                    <small style="color: #666;">Check if participants need to register for this event</small>
                </div>

                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn">
                        <i class="fas fa-save"></i> <?php echo $action == 'add' ? 'Add Event' : 'Update Event'; ?>
                    </button>
                    <a href="manage-events.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </main>

    <script>
        // File upload feedback
        document.getElementById('featured_image').addEventListener('change', function() {
            const fileName = this.files[0]?.name;
            if (fileName) {
                document.querySelector('.file-upload-button').innerHTML = '<i class="fas fa-check"></i> ' + fileName;
            }
        });
    </script>
</body>
</html>
