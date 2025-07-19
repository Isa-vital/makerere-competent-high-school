<?php
// Admin dashboard for Makerere Competent High School
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if user is logged in
requireAdmin();

// Check session timeout
if (!checkSessionTimeout()) {
    header('Location: login.php');
    exit;
}

// Get dashboard statistics
try {
    // Get news count
    $stmt = $pdo->query("SELECT COUNT(*) FROM news");
    $totalNews = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM news WHERE status = 'published'");
    $publishedNews = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM news WHERE status = 'draft'");
    $draftNews = $stmt->fetchColumn();
    
    // Get gallery count
    $stmt = $pdo->query("SELECT COUNT(*) FROM gallery");
    $totalGallery = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM gallery WHERE status = 'active'");
    $activeGallery = $stmt->fetchColumn();
    
    // Get contact messages count
    $stmt = $pdo->query("SELECT COUNT(*) FROM contact_messages");
    $totalMessages = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'new'");
    $newMessages = $stmt->fetchColumn();
    
    // Get alumni count
    $stmt = $pdo->query("SELECT COUNT(*) FROM alumni");
    $totalAlumni = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM alumni WHERE status = 'approved'");
    $approvedAlumni = $stmt->fetchColumn();
    
    // Get admissions count
    $stmt = $pdo->query("SELECT COUNT(*) FROM admissions");
    $totalAdmissions = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM admissions WHERE status = 'pending'");
    $pendingAdmissions = $stmt->fetchColumn();
    
    // Get recent activity
    $stmt = $pdo->prepare("SELECT n.*, u.full_name as author_name FROM news n 
                          LEFT JOIN admin_users u ON n.author_id = u.id 
                          ORDER BY n.created_at DESC LIMIT 5");
    $stmt->execute();
    $recentNews = $stmt->fetchAll();
    
    $stmt = $pdo->prepare("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");
    $stmt->execute();
    $recentMessages = $stmt->fetchAll();
    
    $stmt = $pdo->prepare("SELECT * FROM gallery ORDER BY created_at DESC LIMIT 5");
    $stmt->execute();
    $recentGallery = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $totalNews = $publishedNews = $draftNews = $totalGallery = $activeGallery = 0;
    $totalMessages = $newMessages = $totalAlumni = $approvedAlumni = 0;
    $totalAdmissions = $pendingAdmissions = 0;
    $recentNews = $recentMessages = $recentGallery = [];
}

$page_title = 'Admin Dashboard';
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
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .admin-header {
            background: linear-gradient(135deg, #1a472a 0%, #2d5a3d 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .admin-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .admin-nav h1 {
            font-size: 1.5rem;
            margin: 0;
        }
        
        .admin-nav .nav-links {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .admin-nav .nav-links a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        
        .admin-nav .nav-links a:hover {
            background-color: rgba(255,255,255,0.1);
        }
        
        .admin-sidebar {
            position: fixed;
            left: 0;
            top: 70px;
            width: 250px;
            height: calc(100vh - 70px);
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
            z-index: 1000;
        }
        
        .admin-content {
            margin-left: 250px;
            padding: 2rem;
            min-height: calc(100vh - 70px);
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            border-bottom: 1px solid #eee;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 1rem 1.5rem;
            color: #333;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: #f8f9fa;
            color: #1a472a;
            border-left: 3px solid #1a472a;
        }
        
        .sidebar-menu i {
            width: 20px;
            margin-right: 10px;
        }
        
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #1a472a;
        }
        
        .stat-card h3 {
            margin: 0 0 0.5rem 0;
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-card .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #1a472a;
            margin-bottom: 0.5rem;
        }
        
        .stat-card .stat-icon {
            float: right;
            font-size: 2rem;
            color: #1a472a;
            opacity: 0.3;
        }
        
        .dashboard-section {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .section-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }
        
        .section-header h3 {
            margin: 0;
            color: #1a472a;
        }
        
        .section-header a {
            color: #1a472a;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .section-header a:hover {
            text-decoration: underline;
        }
        
        .item-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .item-list li {
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .item-list li:last-child {
            border-bottom: none;
        }
        
        .item-title {
            font-weight: 500;
            color: #333;
            margin-bottom: 0.25rem;
        }
        
        .item-meta {
            font-size: 0.8rem;
            color: #666;
        }
        
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-published {
            background: #d4edda;
            color: #155724;
        }
        
        .status-draft {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-new {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-read {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .admin-sidebar.active {
                transform: translateX(0);
            }
            
            .admin-content {
                margin-left: 0;
                padding: 1rem;
            }
            
            .mobile-menu-toggle {
                display: block;
            }
            
            .dashboard-stats {
                grid-template-columns: 1fr;
            }
            
            .admin-nav .nav-links {
                gap: 0.5rem;
            }
            
            .admin-nav .nav-links a {
                padding: 0.5rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="container">
            <nav class="admin-nav">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
                </div>
                <div class="nav-links">
                    <a href="../index.php" target="_blank">
                        <i class="fas fa-external-link-alt"></i> View Site
                    </a>
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                    <a href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </nav>
        </div>
    </header>
    
    <!-- Admin Sidebar -->
    <aside class="admin-sidebar" id="sidebar">
        <ul class="sidebar-menu">
            <li><a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="manage-news.php"><i class="fas fa-newspaper"></i> Manage News</a></li>
            <li><a href="manage-events.php"><i class="fas fa-calendar-alt"></i> Manage Events</a></li>
            <li><a href="manage-gallery.php"><i class="fas fa-images"></i> Manage Gallery</a></li>
            <li><a href="manage-alumni.php"><i class="fas fa-graduation-cap"></i> Manage Alumni</a></li>
            <li><a href="manage-admissions.php"><i class="fas fa-file-alt"></i> Admissions</a></li>
            <li><a href="manage-messages.php"><i class="fas fa-envelope"></i> Contact Messages</a></li>
            <li><a href="manage-settings.php"><i class="fas fa-cogs"></i> Site Settings</a></li>
            <li><a href="manage-users.php"><i class="fas fa-users"></i> Admin Users</a></li>
            <li><a href="../index.php" target="_blank"><i class="fas fa-external-link-alt"></i> View Website</a></li>
        </ul>
    </aside>
    
    <!-- Main Content -->
    <main class="admin-content">
        <!-- Dashboard Statistics -->
        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-newspaper"></i></div>
                <h3>News Articles</h3>
                <div class="stat-number"><?php echo $totalNews; ?></div>
                <p><?php echo $publishedNews; ?> published, <?php echo $draftNews; ?> drafts</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-images"></i></div>
                <h3>Gallery Images</h3>
                <div class="stat-number"><?php echo $totalGallery; ?></div>
                <p><?php echo $activeGallery; ?> active images</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-envelope"></i></div>
                <h3>Contact Messages</h3>
                <div class="stat-number"><?php echo $totalMessages; ?></div>
                <p><?php echo $newMessages; ?> new messages</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-graduation-cap"></i></div>
                <h3>Alumni Records</h3>
                <div class="stat-number"><?php echo $totalAlumni; ?></div>
                <p><?php echo $approvedAlumni; ?> approved alumni</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
                <h3>Admissions</h3>
                <div class="stat-number"><?php echo $totalAdmissions; ?></div>
                <p><?php echo $pendingAdmissions; ?> pending applications</p>
            </div>
        </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-envelope"></i></div>
                <h3>Contact Messages</h3>
                <div class="stat-number"><?php echo $totalMessages; ?></div>
                <p><?php echo $newMessages; ?> unread</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <h3>Students</h3>
                <div class="stat-number"><?php echo getSetting('students_count', '1200'); ?></div>
                <p>Active students</p>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <!-- Recent News -->
            <div class="dashboard-section">
                <div class="section-header">
                    <h3><i class="fas fa-newspaper"></i> Recent News</h3>
                    <a href="manage-news.php">View All</a>
                </div>
                
                <?php if (!empty($recentNews)): ?>
                <ul class="item-list">
                    <?php foreach ($recentNews as $news): ?>
                    <li>
                        <div>
                            <div class="item-title"><?php echo htmlspecialchars(truncateText($news['title'], 50)); ?></div>
                            <div class="item-meta">
                                <?php echo formatDate($news['created_at']); ?>
                                <?php if (!empty($news['author'])): ?>
                                    | By <?php echo htmlspecialchars($news['author']); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <span class="status-badge status-<?php echo $news['status']; ?>">
                            <?php echo ucfirst($news['status']); ?>
                        </span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                <p style="text-align: center; color: #666; padding: 2rem;">No news articles yet.</p>
                <?php endif; ?>
            </div>
            
            <!-- Recent Messages -->
            <div class="dashboard-section">
                <div class="section-header">
                    <h3><i class="fas fa-envelope"></i> Recent Messages</h3>
                    <a href="manage-messages.php">View All</a>
                </div>
                
                <?php if (!empty($recentMessages)): ?>
                <ul class="item-list">
                    <?php foreach ($recentMessages as $message): ?>
                    <li>
                        <div>
                            <div class="item-title"><?php echo htmlspecialchars($message['name']); ?></div>
                            <div class="item-meta">
                                <?php echo htmlspecialchars(truncateText($message['subject'], 40)); ?> | 
                                <?php echo timeAgo($message['created_at']); ?>
                            </div>
                        </div>
                        <span class="status-badge status-<?php echo $message['status']; ?>">
                            <?php echo ucfirst($message['status']); ?>
                        </span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                <p style="text-align: center; color: #666; padding: 2rem;">No messages yet.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="dashboard-section">
            <div class="section-header">
                <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <a href="manage-news.php?action=add" class="btn" style="text-decoration: none; text-align: center; padding: 1rem;">
                    <i class="fas fa-plus"></i><br>
                    Add News Article
                </a>
                
                <a href="manage-gallery.php?action=add" class="btn" style="text-decoration: none; text-align: center; padding: 1rem;">
                    <i class="fas fa-upload"></i><br>
                    Upload Images
                </a>
                
                <a href="manage-settings.php" class="btn" style="text-decoration: none; text-align: center; padding: 1rem;">
                    <i class="fas fa-cogs"></i><br>
                    Site Settings
                </a>
                
                <a href="../index.php" target="_blank" class="btn btn-secondary" style="text-decoration: none; text-align: center; padding: 1rem;">
                    <i class="fas fa-eye"></i><br>
                    Preview Site
                </a>
            </div>
        </div>
    </main>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-menu-toggle');
            
            if (window.innerWidth <= 768 && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });
        
        // Auto-refresh dashboard statistics every 30 seconds
        setTimeout(function() {
            location.reload();
        }, 30000);
    </script>
</body>
</html>
