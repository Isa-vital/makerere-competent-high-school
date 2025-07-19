<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
require_once 'includes/config.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <?php
    // Generate meta tags (can be customized per page)
    $pageTitle = isset($page_title) ? $page_title : '';
    $pageDescription = isset($page_description) ? $page_description : '';
    $pageKeywords = isset($page_keywords) ? $page_keywords : '';
    $pageImage = isset($page_image) ? $page_image : '';
    
    generateMetaTags($pageTitle, $pageDescription, $pageKeywords, $pageImage);
    ?>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/favicon_io/favicon.ico">
    <link rel="apple-touch-icon" href="assets/images/favicon_io/apple-touch-icon.png">
    
    <!-- Bootstrap CSS for reliable mobile menu -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- AOS Animation Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    
    <!-- Additional page-specific CSS -->
    <?php if (isset($additional_css)): ?>
        <?php foreach ($additional_css as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <!-- Top Header with Contact Info -->
        <div class="top-header">
            <div class="container">
                <div class="top-header-content">
                    <div class="contact-info">
                        <span><i class="fas fa-phone"></i> <?php echo SITE_PHONE; ?></span>
                        <span><i class="fas fa-envelope"></i> <?php echo SITE_EMAIL; ?></span>
                        <span><i class="fas fa-map-marker-alt"></i> <?php echo SITE_ADDRESS; ?></span>
                    </div>
                    <div class="social-links">
                        <a href="<?php echo FACEBOOK_URL; ?>" target="_blank" rel="noopener" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="<?php echo TWITTER_URL; ?>" target="_blank" rel="noopener" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="<?php echo INSTAGRAM_URL; ?>" target="_blank" rel="noopener" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="<?php echo YOUTUBE_URL; ?>" target="_blank" rel="noopener" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Header with Logo and Navigation -->
        <div class="main-header">
            <div class="container">
                <div class="header-content">
                    <div class="logo">
                        <img src="<?php echo SITE_URL; ?>/assets/images/competentlogo.jpeg" alt="<?php echo SITE_NAME; ?> Logo">
                        <div class="logo-text">
                            <h1><?php echo SITE_NAME; ?></h1>
                            <p><?php echo getSetting('school_motto', 'Quality Education for A Bright Future'); ?></p>
                        </div>
                    </div>
                    
                    <!-- Mobile Menu Toggle -->
                    <button class="navbar-toggler mobile-menu-toggle d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon">
                            <div class="hamburger">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav nav-menu ms-auto">
                        <li class="nav-item"><a href="<?php echo SITE_URL; ?>/index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                        <li class="nav-item"><a href="<?php echo SITE_URL; ?>/about.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">About Us</a></li>
                        <li class="nav-item"><a href="<?php echo SITE_URL; ?>/academics.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'academics.php' ? 'active' : ''; ?>">Academics</a></li>
                        <li class="nav-item"><a href="<?php echo SITE_URL; ?>/admissions.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admissions.php' ? 'active' : ''; ?>">Admissions</a></li>
                        <li class="nav-item"><a href="<?php echo SITE_URL; ?>/gallery.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'gallery.php' ? 'active' : ''; ?>">Gallery</a></li>
                        <li class="nav-item"><a href="<?php echo SITE_URL; ?>/news.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'news.php' ? 'active' : ''; ?>">News & Events</a></li>
                        <li class="nav-item"><a href="<?php echo SITE_URL; ?>/alumni.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'alumni.php' ? 'active' : ''; ?>">MACOSA</a></li>
                        <li class="nav-item"><a href="<?php echo SITE_URL; ?>/contact.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">Contact</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content Area -->
    <main class="main-content">
        <?php
        // Display breadcrumb if not on homepage
        if (basename($_SERVER['PHP_SELF']) !== 'index.php') {
            echo getBreadcrumb(basename($_SERVER['PHP_SELF']));
        }
        ?>
