<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
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
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    
    <!-- Additional page-specific CSS -->
    <?php if (isset($additional_css)): ?>
        <?php foreach ($additional_css as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Header Section -->
    <header class="site-header">
        <!-- Top Header Section -->
        <div class="top-header bg-primary text-white py-2">
            <div class="container">
                <div class="row align-items-center">
                    <!-- School Logo and Name -->
                    <div class="col-lg-6 col-md-6">
                        <div class="d-flex align-items-center">
                            <img src="<?php echo SITE_URL; ?>/assets/images/competentlogo.jpeg" 
                                 alt="<?php echo SITE_NAME; ?> Logo" 
                                 class="logo-img me-3">
                            <div class="logo-text">
                                <h1 class="mb-0 fs-5 fw-bold text-white"><?php echo SITE_NAME; ?></h1>
                                <p class="mb-0 small text-white-50"><?php echo getSetting('school_motto', 'Quality Education for A Bright Future'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Info and Social Links -->
                    <div class="col-lg-6 col-md-6">
                        <div class="row">
                            <!-- Contact Information -->
                            <div class="col-lg-8 d-none d-md-block">
                                <div class="contact-info">
                                    <div class="d-flex flex-column align-items-end text-end">
                                        <small class="mb-1">
                                            <i class="fas fa-phone me-1"></i>
                                            <?php echo SITE_PHONE; ?>
                                        </small>
                                        <small class="mb-1">
                                            <i class="fas fa-envelope me-1"></i>
                                            <?php echo SITE_EMAIL; ?>
                                        </small>
                                        <small>
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            <?php echo SITE_ADDRESS; ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Social Media Links -->
                            <div class="col-lg-4 col-12">
                                <div class="social-links d-flex justify-content-end gap-2">
                                    <a href="<?php echo FACEBOOK_URL; ?>" target="_blank" rel="noopener" 
                                       class="social-link" title="Facebook" aria-label="Visit our Facebook page">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="<?php echo TWITTER_URL; ?>" target="_blank" rel="noopener" 
                                       class="social-link" title="Twitter" aria-label="Visit our Twitter page">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a href="<?php echo INSTAGRAM_URL; ?>" target="_blank" rel="noopener" 
                                       class="social-link" title="Instagram" aria-label="Visit our Instagram page">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                    <a href="<?php echo YOUTUBE_URL; ?>" target="_blank" rel="noopener" 
                                       class="social-link" title="YouTube" aria-label="Visit our YouTube channel">
                                        <i class="fab fa-youtube"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top" id="mainNavbar">
            <div class="container">
                <!-- Mobile Logo (visible only on mobile) -->
                <a class="navbar-brand d-lg-none" href="<?php echo SITE_URL; ?>/index.php">
                    <img src="<?php echo SITE_URL; ?>/assets/images/competentlogo.jpeg" 
                         alt="<?php echo SITE_NAME; ?>" 
                         class="mobile-logo">
                </a>
                
                <!-- Mobile Toggle Button -->
                <button class="navbar-toggler border-0" 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#navbarNav" 
                        aria-controls="navbarNav" 
                        aria-expanded="false" 
                        aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- Navigation Menu -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" 
                               href="<?php echo SITE_URL; ?>/index.php">
                                <i class="fas fa-home d-lg-none me-2"></i>Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>" 
                               href="<?php echo SITE_URL; ?>/about.php">
                                <i class="fas fa-info-circle d-lg-none me-2"></i>About Us
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'academics.php' ? 'active' : ''; ?>" 
                               href="<?php echo SITE_URL; ?>/academics.php">
                                <i class="fas fa-graduation-cap d-lg-none me-2"></i>Academics
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admissions.php' ? 'active' : ''; ?>" 
                               href="<?php echo SITE_URL; ?>/admissions.php">
                                <i class="fas fa-user-plus d-lg-none me-2"></i>Admissions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'gallery.php' ? 'active' : ''; ?>" 
                               href="<?php echo SITE_URL; ?>/gallery.php">
                                <i class="fas fa-images d-lg-none me-2"></i>Gallery
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'news.php' ? 'active' : ''; ?>" 
                               href="<?php echo SITE_URL; ?>/news.php">
                                <i class="fas fa-newspaper d-lg-none me-2"></i>News & Events
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'alumni.php' ? 'active' : ''; ?>" 
                               href="<?php echo SITE_URL; ?>/alumni.php">
                                <i class="fas fa-users d-lg-none me-2"></i>MACOSA
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>" 
                               href="<?php echo SITE_URL; ?>/contact.php">
                                <i class="fas fa-envelope d-lg-none me-2"></i>Contact
                            </a>
                        </li>
                    </ul>
                    
                    <!-- CTA Button -->
                    <div class="d-lg-block d-none">
                        <a href="<?php echo SITE_URL; ?>/admissions.php" class="btn btn-primary btn-sm px-4">
                            <i class="fas fa-graduation-cap me-1"></i>
                            Apply Now
                        </a>
                    </div>
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
