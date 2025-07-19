<?php
// Manage Gallery - Admin panel for Makerere Competent High School
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
    if ($action == 'upload') {
        $title = sanitizeInput($_POST['title']);
        $description = sanitizeInput($_POST['description']);
        $category = sanitizeInput($_POST['category']);
        
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $uploaded_files = [];
            $upload_errors = [];
            
            for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
                if ($_FILES['images']['error'][$i] == 0) {
                    $file = [
                        'name' => $_FILES['images']['name'][$i],
                        'type' => $_FILES['images']['type'][$i],
                        'tmp_name' => $_FILES['images']['tmp_name'][$i],
                        'error' => $_FILES['images']['error'][$i],
                        'size' => $_FILES['images']['size'][$i]
                    ];
                    
                    $upload_result = uploadImage($file, 'gallery');
                    if ($upload_result['success']) {
                        $uploaded_files[] = $upload_result['filename'];
                    } else {
                        $upload_errors[] = $upload_result['error'];
                    }
                }
            }
            
            if (!empty($uploaded_files)) {
                try {
                    foreach ($uploaded_files as $filename) {
                        $stmt = $pdo->prepare("INSERT INTO gallery (title, description, image_path, category) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$title, $description, $filename, $category]);
                    }
                    $message = count($uploaded_files) . ' image(s) uploaded successfully!';
                    logActivity('gallery_upload', "Uploaded " . count($uploaded_files) . " images");
                    $action = 'list';
                } catch (PDOException $e) {
                    $error = 'Database error: ' . $e->getMessage();
                }
            }
            
            if (!empty($upload_errors)) {
                $error = 'Some uploads failed: ' . implode(', ', $upload_errors);
            }
        } else {
            $error = 'Please select at least one image to upload.';
        }
    }
    
    if ($action == 'delete' && $id) {
        try {
            // Get image path before deleting
            $stmt = $pdo->prepare("SELECT image_path FROM gallery WHERE id = ?");
            $stmt->execute([$id]);
            $image = $stmt->fetch();
            
            if ($image) {
                // Delete from database
                $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
                $stmt->execute([$id]);
                
                // Delete file
                $file_path = '../assets/images/' . $image['image_path'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
                
                $message = 'Image deleted successfully!';
                logActivity('gallery_delete', "Deleted gallery image ID: $id");
            }
            $action = 'list';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

// Get gallery images
if ($action == 'list') {
    $category_filter = $_GET['category'] ?? '';
    $search = $_GET['search'] ?? '';
    
    $sql = "SELECT * FROM gallery WHERE 1=1";
    $params = [];
    
    if ($category_filter) {
        $sql .= " AND category = ?";
        $params[] = $category_filter;
    }
    
    if ($search) {
        $sql .= " AND (title LIKE ? OR description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    $sql .= " ORDER BY created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $gallery_items = $stmt->fetchAll();
    
    // Get categories for filter
    $stmt = $pdo->query("SELECT DISTINCT category FROM gallery WHERE category IS NOT NULL AND category != '' ORDER BY category");
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

$page_title = 'Manage Gallery';
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
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; }
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
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem; margin-top: 2rem; }
        .gallery-item { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .gallery-item:hover { transform: translateY(-2px); }
        .gallery-item img { width: 100%; height: 200px; object-fit: cover; }
        .gallery-item-content { padding: 1rem; }
        .gallery-item-title { font-weight: 600; margin-bottom: 0.5rem; color: #333; }
        .gallery-item-category { background: #e9ecef; color: #495057; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.8rem; display: inline-block; margin-bottom: 0.5rem; }
        .gallery-item-description { color: #666; font-size: 0.9rem; margin-bottom: 1rem; }
        .gallery-item-actions { display: flex; gap: 0.5rem; }
        .filters { display: flex; gap: 1rem; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; }
        .filters select, .filters input { padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px; }
        .upload-area { border: 2px dashed #ddd; border-radius: 8px; padding: 3rem; text-align: center; margin-bottom: 2rem; transition: border-color 0.3s; }
        .upload-area.dragover { border-color: #1a472a; background-color: #f8f9fa; }
        .upload-area input[type="file"] { display: none; }
        .upload-button { background: #007bff; color: white; padding: 1rem 2rem; border-radius: 5px; cursor: pointer; display: inline-block; transition: background-color 0.3s; }
        .upload-button:hover { background: #0056b3; }
        .breadcrumb { margin-bottom: 2rem; }
        .breadcrumb a { color: #1a472a; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        .file-preview { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; margin-top: 1rem; }
        .file-preview-item { position: relative; border: 1px solid #ddd; border-radius: 5px; overflow: hidden; }
        .file-preview-item img { width: 100%; height: 100px; object-fit: cover; }
        .file-preview-item .remove-file { position: absolute; top: 5px; right: 5px; background: #dc3545; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; font-size: 12px; cursor: pointer; }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="container">
            <nav class="admin-nav">
                <h1><i class="fas fa-images"></i> <?php echo $page_title; ?></h1>
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
                Gallery Management
            <?php elseif ($action == 'upload'): ?>
                <a href="manage-gallery.php">Gallery Management</a> / Upload Images
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
        <!-- Gallery List -->
        <div class="admin-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>Gallery Images</h2>
                <a href="?action=upload" class="btn">
                    <i class="fas fa-upload"></i> Upload Images
                </a>
            </div>

            <!-- Filters -->
            <div class="filters">
                <form method="GET" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                    <select name="category" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat); ?>" 
                                <?php echo $category_filter == $cat ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <input type="text" name="search" placeholder="Search images..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                    
                    <button type="submit" class="btn btn-small">
                        <i class="fas fa-search"></i> Search
                    </button>
                    
                    <?php if ($category_filter || $search): ?>
                    <a href="manage-gallery.php" class="btn btn-small btn-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                    <?php endif; ?>
                </form>
            </div>

            <?php if (!empty($gallery_items)): ?>
            <div class="gallery-grid">
                <?php foreach ($gallery_items as $item): ?>
                <div class="gallery-item">
                    <img src="../assets/images/<?php echo $item['image_path']; ?>" 
                         alt="<?php echo htmlspecialchars($item['title']); ?>"
                         onerror="this.src='../assets/images/placeholder-facility.jpg'">
                    <div class="gallery-item-content">
                        <div class="gallery-item-title"><?php echo htmlspecialchars($item['title']); ?></div>
                        <?php if ($item['category']): ?>
                        <div class="gallery-item-category"><?php echo htmlspecialchars($item['category']); ?></div>
                        <?php endif; ?>
                        <?php if ($item['description']): ?>
                        <div class="gallery-item-description">
                            <?php echo htmlspecialchars(truncateText($item['description'], 80)); ?>
                        </div>
                        <?php endif; ?>
                        <div class="gallery-item-actions">
                            <a href="?action=delete&id=<?php echo $item['id']; ?>" 
                               class="btn btn-small btn-danger"
                               onclick="return confirm('Are you sure you want to delete this image?')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p style="text-align: center; color: #666; padding: 3rem;">
                <?php echo ($category_filter || $search) ? 'No images found matching your criteria.' : 'No images in gallery yet.'; ?>
            </p>
            <?php endif; ?>
        </div>

        <?php elseif ($action == 'upload'): ?>
        <!-- Upload Form -->
        <div class="admin-card">
            <h2>Upload Images</h2>

            <form method="POST" enctype="multipart/form-data" id="uploadForm">
                <div class="upload-area" id="uploadArea">
                    <i class="fas fa-cloud-upload-alt" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                    <p>Drag and drop images here or click to select</p>
                    <input type="file" id="images" name="images[]" multiple accept="image/*">
                    <div class="upload-button" onclick="document.getElementById('images').click()">
                        <i class="fas fa-plus"></i> Select Images
                    </div>
                </div>

                <div id="filePreview" class="file-preview"></div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 2rem;">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" placeholder="Enter title for all images">
                    </div>

                    <div class="form-group">
                        <label for="category">Category</label>
                        <input type="text" id="category" name="category" placeholder="e.g., Events, Sports, Academics">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3" 
                              placeholder="Brief description for all images..."></textarea>
                </div>

                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn" id="uploadBtn" disabled>
                        <i class="fas fa-upload"></i> Upload Images
                    </button>
                    <a href="manage-gallery.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </main>

    <script>
        // File upload handling
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('images');
        const filePreview = document.getElementById('filePreview');
        const uploadBtn = document.getElementById('uploadBtn');
        let selectedFiles = [];

        // Drag and drop functionality
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            const files = Array.from(e.dataTransfer.files).filter(file => file.type.startsWith('image/'));
            handleFiles(files);
        });

        fileInput.addEventListener('change', (e) => {
            const files = Array.from(e.target.files);
            handleFiles(files);
        });

        function handleFiles(files) {
            selectedFiles = files;
            updateFilePreview();
            uploadBtn.disabled = files.length === 0;
        }

        function updateFilePreview() {
            filePreview.innerHTML = '';
            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const previewItem = document.createElement('div');
                    previewItem.className = 'file-preview-item';
                    previewItem.innerHTML = `
                        <img src="${e.target.result}" alt="${file.name}">
                        <button type="button" class="remove-file" onclick="removeFile(${index})">×</button>
                    `;
                    filePreview.appendChild(previewItem);
                };
                reader.readAsDataURL(file);
            });
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            updateFilePreview();
            uploadBtn.disabled = selectedFiles.length === 0;
            
            // Update file input
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            fileInput.files = dt.files;
        }
    </script>
</body>
</html>
