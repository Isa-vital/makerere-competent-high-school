<?php
// Helper functions for Makerere Competent High School website

// Sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Validate email address
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Generate secure password hash
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Generate random string
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Format date
function formatDate($date, $format = 'F j, Y') {
    return date($format, strtotime($date));
}

// Time ago function
function timeAgo($date) {
    $timestamp = strtotime($date);
    $difference = time() - $timestamp;
    
    if ($difference < 60) {
        return 'just now';
    } elseif ($difference < 3600) {
        $minutes = floor($difference / 60);
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
    } elseif ($difference < 86400) {
        $hours = floor($difference / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($difference < 604800) {
        $days = floor($difference / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        return formatDate($date);
    }
}

// Truncate text
function truncateText($text, $length = 150, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . $suffix;
}

// Generate excerpt from content
function generateExcerpt($content, $length = 150) {
    $content = strip_tags($content);
    return truncateText($content, $length);
}

// Upload file function
function uploadFile($file, $uploadDir = 'assets/images/uploads/', $allowedTypes = ['jpg', 'jpeg', 'png', 'gif']) {
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        return ['success' => false, 'message' => 'No file uploaded'];
    }
    
    $fileName = $file['name'];
    $fileSize = $file['size'];
    $fileTmp = $file['tmp_name'];
    $fileError = $file['error'];
    
    // Check for upload errors
    if ($fileError !== 0) {
        return ['success' => false, 'message' => 'File upload error'];
    }
    
    // Get file extension
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    // Check if file type is allowed
    if (!in_array($fileExt, $allowedTypes)) {
        return ['success' => false, 'message' => 'File type not allowed'];
    }
    
    // Check file size (5MB max)
    if ($fileSize > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'File size too large'];
    }
    
    // Generate unique filename
    $newFileName = uniqid('img_') . '.' . $fileExt;
    $uploadPath = $uploadDir . $newFileName;
    
    // Create upload directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Move uploaded file
    if (move_uploaded_file($fileTmp, $uploadPath)) {
        return ['success' => true, 'filename' => $newFileName, 'path' => $uploadPath];
    } else {
        return ['success' => false, 'message' => 'Failed to move uploaded file'];
    }
}

// Delete file function
function deleteFile($filePath) {
    if (file_exists($filePath)) {
        return unlink($filePath);
    }
    return false;
}

// Resize image function
function resizeImage($source, $destination, $maxWidth = 800, $maxHeight = 600, $quality = 85) {
    list($origWidth, $origHeight, $imageType) = getimagesize($source);
    
    // Calculate new dimensions
    $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
    $newWidth = round($origWidth * $ratio);
    $newHeight = round($origHeight * $ratio);
    
    // Create image resource
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($source);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($source);
            break;
        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($source);
            break;
        default:
            return false;
    }
    
    // Create new image
    $newImage = imagecreatetruecolor($newWidth, $newHeight);
    
    // Preserve transparency for PNG and GIF
    if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
        imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
    }
    
    // Resize image
    imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
    
    // Save image
    $result = false;
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $result = imagejpeg($newImage, $destination, $quality);
            break;
        case IMAGETYPE_PNG:
            $result = imagepng($newImage, $destination);
            break;
        case IMAGETYPE_GIF:
            $result = imagegif($newImage, $destination);
            break;
    }
    
    // Clean up memory
    imagedestroy($sourceImage);
    imagedestroy($newImage);
    
    return $result;
}

// Send email function
function sendEmail($to, $subject, $message, $from = SITE_EMAIL) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: " . SITE_NAME . " <" . $from . ">" . "\r\n";
    $headers .= "Reply-To: " . $from . "\r\n";
    
    return mail($to, $subject, $message, $headers);
}

// Get news articles
function getNews($limit = 10, $featured = false) {
    global $pdo;
    try {
        $sql = "SELECT * FROM news WHERE status = 'published'";
        if ($featured) {
            $sql .= " AND featured = 1";
        }
        $sql .= " ORDER BY created_at DESC LIMIT ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

// Get single news article
function getNewsById($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ? AND status = 'published'");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return false;
    }
}

// Get latest news by category
function getLatestNews($limit = 5, $category = null) {
    global $pdo;
    try {
        $sql = "SELECT * FROM news WHERE status = 'published'";
        $params = [];
        
        if ($category) {
            $sql .= " AND category = ?";
            $params[] = $category;
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT ?";
        $params[] = $limit;
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

// Get gallery images
function getGalleryImages($category = null, $limit = null) {
    global $pdo;
    try {
        $sql = "SELECT * FROM gallery WHERE status = 'active'";
        $params = [];
        
        if ($category) {
            $sql .= " AND category = ?";
            $params[] = $category;
        }
        
        $sql .= " ORDER BY sort_order ASC, created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT ?";
            $params[] = $limit;
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

// Save contact message
function saveContactMessage($name, $email, $subject, $message, $phone = '') {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message, phone) VALUES (?, ?, ?, ?, ?)");
        $result = $stmt->execute([$name, $email, $subject, $message, $phone]);
        
        if ($result) {
            // Send notification email to admin
            $adminMessage = "
                <h3>New Contact Message</h3>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Phone:</strong> $phone</p>
                <p><strong>Subject:</strong> $subject</p>
                <p><strong>Message:</strong></p>
                <p>$message</p>
            ";
            sendEmail(ADMIN_EMAIL, "New Contact Message - " . $subject, $adminMessage);
        }
        
        return $result;
    } catch (PDOException $e) {
        return false;
    }
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Check if user is logged in
function requireAdmin() {
    if (!isAdmin()) {
        header('Location: login.php');
        exit;
    }
}

// Start admin session
function startAdminSession($userId, $username) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_user_id'] = $userId;
    $_SESSION['admin_username'] = $username;
    $_SESSION['admin_login_time'] = time();
}

// Destroy admin session
function destroyAdminSession() {
    unset($_SESSION['admin_logged_in']);
    unset($_SESSION['admin_user_id']);
    unset($_SESSION['admin_username']);
    unset($_SESSION['admin_login_time']);
    session_destroy();
}

// Check session timeout
function checkSessionTimeout() {
    if (isset($_SESSION['admin_login_time'])) {
        if (time() - $_SESSION['admin_login_time'] > SESSION_TIMEOUT) {
            destroyAdminSession();
            return false;
        }
        $_SESSION['admin_login_time'] = time(); // Update last activity time
    }
    return true;
}

// Get breadcrumb for current page
function getBreadcrumb($currentPage) {
    $breadcrumbs = [
        'index.php' => 'Home',
        'about.php' => 'About Us',
        'academics.php' => 'Academics',
        'admissions.php' => 'Admissions',
        'gallery.php' => 'Gallery',
        'news.php' => 'News & Events',
        'contact.php' => 'Contact Us'
    ];
    
    $result = '<nav class="breadcrumb"><div class="container"><ul>';
    $result .= '<li><a href="index.php">Home</a></li>';
    
    if (isset($breadcrumbs[$currentPage]) && $currentPage !== 'index.php') {
        $result .= '<li>' . $breadcrumbs[$currentPage] . '</li>';
    }
    
    $result .= '</ul></div></nav>';
    return $result;
}

// Generate meta tags
function generateMetaTags($title = '', $description = '', $keywords = '', $image = '') {
    $siteTitle = !empty($title) ? $title . ' - ' . SITE_NAME : SITE_NAME;
    $siteDescription = !empty($description) ? $description : 'Makerere Competent High School - Excellence in Education';
    $siteKeywords = !empty($keywords) ? $keywords : 'school, education, Uganda, Kampala, high school, secondary school';
    $siteImage = !empty($image) ? SITE_URL . '/' . $image : SITE_URL . '/assets/images/logo.png';
    
    echo "<title>$siteTitle</title>\n";
    echo "<meta name='description' content='$siteDescription'>\n";
    echo "<meta name='keywords' content='$siteKeywords'>\n";
    echo "<meta property='og:title' content='$siteTitle'>\n";
    echo "<meta property='og:description' content='$siteDescription'>\n";
    echo "<meta property='og:image' content='$siteImage'>\n";
    echo "<meta property='og:url' content='" . SITE_URL . "'>\n";
    echo "<meta name='twitter:card' content='summary_large_image'>\n";
    echo "<meta name='twitter:title' content='$siteTitle'>\n";
    echo "<meta name='twitter:description' content='$siteDescription'>\n";
    echo "<meta name='twitter:image' content='$siteImage'>\n";
}

// Log activity
function logActivity($action, $description = '') {
    global $pdo;
    try {
        $userId = $_SESSION['admin_user_id'] ?? 0;
        $userIp = $_SERVER['REMOTE_ADDR'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        
        $stmt = $pdo->prepare("INSERT INTO activity_log (user_id, action, description, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $action, $description, $userIp, $userAgent]);
    } catch (PDOException $e) {
        // Silently fail for logging
    }
}

// Convert URLs to links in text
function makeLinksClickable($text) {
    return preg_replace('/(https?:\/\/[^\s]+)/', '<a href="$1" target="_blank" rel="noopener">$1</a>', $text);
}

// Generate pagination
function generatePagination($currentPage, $totalPages, $baseUrl) {
    if ($totalPages <= 1) return '';
    
    $pagination = '<div class="pagination">';
    
    // Previous button
    if ($currentPage > 1) {
        $pagination .= '<a href="' . $baseUrl . '?page=' . ($currentPage - 1) . '" class="pagination-btn">&laquo; Previous</a>';
    }
    
    // Page numbers
    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $currentPage + 2);
    
    if ($start > 1) {
        $pagination .= '<a href="' . $baseUrl . '?page=1" class="pagination-btn">1</a>';
        if ($start > 2) {
            $pagination .= '<span class="pagination-dots">...</span>';
        }
    }
    
    for ($i = $start; $i <= $end; $i++) {
        $active = ($i == $currentPage) ? ' active' : '';
        $pagination .= '<a href="' . $baseUrl . '?page=' . $i . '" class="pagination-btn' . $active . '">' . $i . '</a>';
    }
    
    if ($end < $totalPages) {
        if ($end < $totalPages - 1) {
            $pagination .= '<span class="pagination-dots">...</span>';
        }
        $pagination .= '<a href="' . $baseUrl . '?page=' . $totalPages . '" class="pagination-btn">' . $totalPages . '</a>';
    }
    
    // Next button
    if ($currentPage < $totalPages) {
        $pagination .= '<a href="' . $baseUrl . '?page=' . ($currentPage + 1) . '" class="pagination-btn">Next &raquo;</a>';
    }
    
    $pagination .= '</div>';
    return $pagination;
}

// Upload image to gallery
function uploadImage($file, $folder = 'gallery') {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB
    $upload_dir = 'assets/images/uploads/';
    if (!is_dir(__DIR__ . '/../' . $upload_dir)) {
        mkdir(__DIR__ . '/../' . $upload_dir, 0777, true);
    }

    // Validate file type
    if (!in_array($file['type'], $allowed_types)) {
        return ['success' => false, 'error' => 'Invalid file type. Only JPG, PNG, GIF, WEBP allowed.'];
    }
    // Validate file size
    if ($file['size'] > $max_size) {
        return ['success' => false, 'error' => 'File too large. Max 5MB allowed.'];
    }
    // Generate unique filename
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = $folder . '_' . date('YmdHis') . '_' . generateRandomString(6) . '.' . $ext;
    $target_path = __DIR__ . '/../' . $upload_dir . $filename;
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        return ['success' => true, 'filename' => 'uploads/' . $filename];
    } else {
        return ['success' => false, 'error' => 'Failed to upload image.'];
    }
}

// Generate URL-friendly slug
function generateSlug($text) {
    // Convert to lowercase and replace non-alphanumeric characters with hyphens
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text)));
    // Remove duplicate hyphens and trim hyphens from ends
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}

// Get current admin user info
function getCurrentAdmin() {
    global $pdo;
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

// Check if user has permission
function hasPermission($action) {
    $user = getCurrentAdmin();
    if (!$user) return false;
    
    // Super admin has all permissions
    if ($user['role'] === 'super_admin') return true;
    
    // Define role permissions
    $permissions = [
        'admin' => ['news_view', 'news_add', 'news_edit', 'gallery_view', 'gallery_add', 'messages_view']
    ];
    
    return in_array($action, $permissions[$user['role']] ?? []);
}
?>
