<?php
// Manage News - Admin panel for Makerere Competent High School
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
        $content = $_POST['content'];
        $excerpt = sanitizeInput($_POST['excerpt']);
        $category = sanitizeInput($_POST['category']);
        $tags = sanitizeInput($_POST['tags']);
        $status = $_POST['status'];
        $featured = isset($_POST['featured']) ? 1 : 0;
        $slug = generateSlug($title);
        $author_id = $_SESSION['user_id'];
        
        // Handle image upload
        $featured_image = '';
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
            $upload_result = uploadImage($_FILES['featured_image'], 'news');
            if ($upload_result['success']) {
                $featured_image = $upload_result['filename'];
            } else {
                $error = $upload_result['error'];
            }
        }
        
        if (empty($error)) {
            try {
                if ($action == 'add') {
                    $stmt = $pdo->prepare("INSERT INTO news (title, slug, excerpt, content, featured_image, category, tags, author_id, status, featured, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $published_at = $status == 'published' ? date('Y-m-d H:i:s') : null;
                    $stmt->execute([$title, $slug, $excerpt, $content, $featured_image, $category, $tags, $author_id, $status, $featured, $published_at]);
                    $message = 'News article added successfully!';
                    logActivity('news_add', "Added news article: $title");
                } else {
                    if ($featured_image) {
                        $stmt = $pdo->prepare("UPDATE news SET title = ?, slug = ?, excerpt = ?, content = ?, featured_image = ?, category = ?, tags = ?, status = ?, featured = ? WHERE id = ?");
                        $stmt->execute([$title, $slug, $excerpt, $content, $featured_image, $category, $tags, $status, $featured, $id]);
                    } else {
                        $stmt = $pdo->prepare("UPDATE news SET title = ?, slug = ?, excerpt = ?, content = ?, category = ?, tags = ?, status = ?, featured = ? WHERE id = ?");
                        $stmt->execute([$title, $slug, $excerpt, $content, $category, $tags, $status, $featured, $id]);
                    }
                    $message = 'News article updated successfully!';
                    logActivity('news_update', "Updated news article: $title");
                }
                $action = 'list';
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
    
    if ($action == 'delete' && $id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
            $stmt->execute([$id]);
            $message = 'News article deleted successfully!';
            logActivity('news_delete', "Deleted news article ID: $id");
            $action = 'list';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

// Get news article for editing
$news_item = null;
if ($action == 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$id]);
    $news_item = $stmt->fetch();
    if (!$news_item) {
        $action = 'list';
        $error = 'News article not found.';
    }
}

// Get all news for listing
if ($action == 'list') {
    $stmt = $pdo->query("SELECT n.*, u.full_name as author_name 
                         FROM news n 
                         LEFT JOIN admin_users u ON n.author_id = u.id 
                         ORDER BY n.created_at DESC");
    $news_list = $stmt->fetchAll();
}

$page_title = 'Manage News';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
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
        .status-published { background: #d4edda; color: #155724; }
        .status-draft { background: #fff3cd; color: #856404; }
        .file-upload { position: relative; display: inline-block; }
        .file-upload input[type=file] { position: absolute; opacity: 0; width: 100%; height: 100%; cursor: pointer; }
        .file-upload-button { display: inline-block; padding: 0.5rem 1rem; background: #007bff; color: white; border-radius: 3px; cursor: pointer; }
        .breadcrumb { margin-bottom: 2rem; }
        .breadcrumb a { color: #1a472a; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="container">
            <nav class="admin-nav">
                <h1><i class="fas fa-newspaper"></i> <?php echo $page_title; ?></h1>
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
                News Management
            <?php elseif ($action == 'add'): ?>
                <a href="manage-news.php">News Management</a> / Add News
            <?php elseif ($action == 'edit'): ?>
                <a href="manage-news.php">News Management</a> / Edit News
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
        <!-- News List -->
        <div class="admin-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>News Articles</h2>
                <a href="?action=add" class="btn">
                    <i class="fas fa-plus"></i> Add New Article
                </a>
            </div>

            <?php if (!empty($news_list)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($news_list as $news): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($news['title']); ?></strong>
                            <?php if ($news['excerpt']): ?>
                            <br><small style="color: #666;"><?php echo htmlspecialchars(truncateText($news['excerpt'], 80)); ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($news['author_name'] ?: 'System'); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $news['status']; ?>">
                                <?php echo ucfirst($news['status']); ?>
                            </span>
                        </td>
                        <td><?php echo formatDate($news['created_at']); ?></td>
                        <td>
                            <a href="?action=edit&id=<?php echo $news['id']; ?>" class="btn btn-small btn-secondary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="?action=delete&id=<?php echo $news['id']; ?>" 
                               class="btn btn-small btn-danger"
                               onclick="return confirm('Are you sure you want to delete this article?')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p style="text-align: center; color: #666; padding: 3rem;">No news articles found.</p>
            <?php endif; ?>
        </div>

        <?php elseif ($action == 'add' || $action == 'edit'): ?>
        <!-- Add/Edit Form -->
        <div class="admin-card">
            <h2><?php echo $action == 'add' ? 'Add New Article' : 'Edit Article'; ?></h2>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" required 
                           value="<?php echo $news_item ? htmlspecialchars($news_item['title']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="excerpt">Excerpt</label>
                    <textarea id="excerpt" name="excerpt" rows="3" 
                              placeholder="Brief description of the article..."><?php echo $news_item ? htmlspecialchars($news_item['excerpt']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="content">Content *</label>
                    <textarea id="content" name="content" required><?php echo $news_item ? htmlspecialchars($news_item['content']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="featured_image">Featured Image</label>
                    <div class="file-upload">
                        <input type="file" id="featured_image" name="featured_image" accept="image/*">
                        <div class="file-upload-button">
                            <i class="fas fa-upload"></i> Choose Image
                        </div>
                    </div>
                    <?php if ($news_item && $news_item['featured_image']): ?>
                    <p style="margin-top: 0.5rem;">Current: <?php echo htmlspecialchars($news_item['featured_image']); ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category">
                        <option value="">Select Category</option>
                        <option value="Academic" <?php echo ($news_item && $news_item['category'] == 'Academic') ? 'selected' : ''; ?>>Academic</option>
                        <option value="Events" <?php echo ($news_item && $news_item['category'] == 'Events') ? 'selected' : ''; ?>>Events</option>
                        <option value="Sports" <?php echo ($news_item && $news_item['category'] == 'Sports') ? 'selected' : ''; ?>>Sports</option>
                        <option value="Announcements" <?php echo ($news_item && $news_item['category'] == 'Announcements') ? 'selected' : ''; ?>>Announcements</option>
                        <option value="Achievements" <?php echo ($news_item && $news_item['category'] == 'Achievements') ? 'selected' : ''; ?>>Achievements</option>
                        <option value="Alumni" <?php echo ($news_item && $news_item['category'] == 'Alumni') ? 'selected' : ''; ?>>Alumni</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="tags">Tags (comma-separated)</label>
                    <input type="text" id="tags" name="tags" 
                           placeholder="e.g., science, competition, students"
                           value="<?php echo $news_item ? htmlspecialchars($news_item['tags']) : ''; ?>">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="draft" <?php echo ($news_item && $news_item['status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?php echo ($news_item && $news_item['status'] == 'published') ? 'selected' : ''; ?>>Published</option>
                            <option value="archived" <?php echo ($news_item && $news_item['status'] == 'archived') ? 'selected' : ''; ?>>Archived</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="featured" value="1" <?php echo ($news_item && $news_item['featured']) ? 'checked' : ''; ?>>
                            Featured Article
                        </label>
                        <small style="color: #666;">Featured articles appear prominently on the homepage</small>
                    </div>
                </div>

                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn">
                        <i class="fas fa-save"></i> <?php echo $action == 'add' ? 'Add Article' : 'Update Article'; ?>
                    </button>
                    <a href="manage-news.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </main>

    <script>
        // Initialize CKEditor
        ClassicEditor
            .create(document.querySelector('#content'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo']
            })
            .catch(error => {
                console.error(error);
            });

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
