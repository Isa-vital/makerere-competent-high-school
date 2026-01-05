<?php
// Homepage for Makerere Competent High School
$page_title = 'Home';
$page_description = 'Makerere Competent High School - Excellence in Education. We provide quality secondary education with a focus on academic excellence, character development, and holistic growth.';
$page_keywords = 'Makerere Competent High School, Uganda secondary school, quality education, High Schools in Hoima, Best Secondary schools, excellence in education';

include 'includes/header.php';

// Get latest news and gallery items
$latestNews = getNews(3, true);
$galleryImages = getGalleryImages(null, 6);
?>

<!-- Hero Carousel Section -->
<section class="hero-carousel" style="margin-top: 0; padding-top: 0;">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="2000">
        <!-- Carousel Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
        </div>

        <!-- Carousel Slides -->
        <div class="carousel-inner">
            <!-- Slide 1 -->
            <div class="carousel-item active">
                <div class="carousel-bg" style="background: linear-gradient(rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.65)), url('assets/images/gate.jpeg') no-repeat center center; background-size: cover;">
                    <div class="container h-100">
                        <div class="row h-100 align-items-center">
                            <div class="col-lg-10 mx-auto text-center text-white">
                                <h1 class="display-3 fw-bold mb-4">Welcome to <?php echo SITE_NAME; ?></h1>
                                <p class="lead mb-4 px-lg-5">Where Excellence Meets Education - Nurturing Future Leaders Through Quality Learning, Character Development, and Academic Excellence</p>
                                <div class="d-flex gap-3 justify-content-center flex-wrap mt-4">
                                    <a href="about.php" class="btn btn-light btn-lg px-5"><i class="fas fa-info-circle me-2"></i>Learn More</a>
                                    <a href="admissions.php" class="btn btn-warning btn-lg px-5"><i class="fas fa-graduation-cap me-2"></i>Apply Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-item">
                <div class="carousel-bg" style="background: linear-gradient(rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.65)), url('assets/images/WhatsApp\ Image\ 2024-11-13\ at\ 09.24.10.jpeg') no-repeat center center; background-size: cover;">
                    <div class="container h-100">
                        <div class="row h-100 align-items-center">
                            <div class="col-lg-10 mx-auto text-center text-white">
                                <h1 class="display-3 fw-bold mb-4">Academic Excellence Since 1998</h1>
                                <p class="lead mb-4 px-lg-5">Over 25 years of providing quality education with a track record of 98% success rate in national examinations</p>
                                <div class="d-flex gap-3 justify-content-center flex-wrap mt-4">
                                    <a href="academics.php" class="btn btn-light btn-lg px-5"><i class="fas fa-book me-2"></i>Our Programs</a>
                                    <a href="gallery.php" class="btn btn-warning btn-lg px-5"><i class="fas fa-images me-2"></i>View Gallery</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-item">
                <div class="carousel-bg" style="background: linear-gradient(rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.65)), url('assets/images/microscope.jpg') no-repeat center center; background-size: cover;">
                    <div class="container h-100">
                        <div class="row h-100 align-items-center">
                            <div class="col-lg-10 mx-auto text-center text-white">
                                <h1 class="display-3 fw-bold mb-4">Modern Facilities & Technology</h1>
                                <p class="lead mb-4 px-lg-5">State-of-the-art laboratories, digital classrooms, sports facilities, and boarding accommodation for an enhanced learning experience</p>
                                <div class="d-flex gap-3 justify-content-center flex-wrap mt-4">
                                    <a href="about.php#facilities" class="btn btn-light btn-lg px-5"><i class="fas fa-building me-2"></i>Tour Campus</a>
                                    <a href="contact.php" class="btn btn-warning btn-lg px-5"><i class="fas fa-calendar me-2"></i>Schedule Visit</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 4 -->
            <div class="carousel-item">
                <div class="carousel-bg" style="background: linear-gradient(rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.65)), url('assets/images/microscope.jpg') no-repeat center center; background-size: cover;">
                    <div class="container h-100">
                        <div class="row h-100 align-items-center">
                            <div class="col-lg-10 mx-auto text-center text-white">
                                <h1 class="display-3 fw-bold mb-4">Join Our MACOSA Organisation</h1>
                                <p class="lead mb-4 px-lg-5">Connect with over 2,500 successful graduates worldwide, both within and abroad, in various professional fields</p>
                                <div class="d-flex gap-3 justify-content-center flex-wrap mt-4">
                                    <a href="alumni.php" class="btn btn-light btn-lg px-5"><i class="fas fa-users me-2"></i>Alumni Stories</a>
                                    <a href="alumni.php#register" class="btn btn-warning btn-lg px-5"><i class="fas fa-user-plus me-2"></i>Join Network</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carousel Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

<!-- About Section -->
<section class="pt-5 pb-5 bg-light" id="about">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">

            <h2 class="display-5 fw-bold text-primary mb-3">Why Choose Makerere Competent High School?</h2>
            <p class="lead text-muted">We are committed to providing exceptional education that prepares students for success</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                            <i class="fas fa-graduation-cap fa-2x text-primary"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Academic Excellence</h4>
                        <p class="text-muted">Our rigorous curriculum and experienced teachers ensure students achieve their highest potential in both O-Level and A-Level studies.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body text-center p-4">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                            <i class="fas fa-users fa-2x text-success"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Character Development</h4>
                        <p class="text-muted">We focus on building strong moral values, leadership skills, and social responsibility in all our students.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body text-center p-4">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                            <i class="fas fa-microscope fa-2x text-warning"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Modern Facilities</h4>
                        <p class="text-muted">State-of-the-art laboratories, libraries, and classrooms equipped with the latest technology for enhanced learning.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body text-center p-4">
                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                            <i class="fas fa-medal fa-2x text-danger"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Sports & Arts</h4>
                        <p class="text-muted">Comprehensive extra-curricular programs including sports, music, drama, and various clubs to develop well-rounded individuals.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body text-center p-4">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                            <i class="fas fa-handshake fa-2x text-info"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Career Guidance</h4>
                        <p class="text-muted">Professional guidance and counseling services to help students make informed decisions about their future careers.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body text-center p-4">
                        <div class="bg-secondary bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                            <i class="fas fa-globe fa-2x text-secondary"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Global Perspective</h4>
                        <p class="text-muted">We prepare students for a globalized world with international partnerships and exchange programs.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-gradient-primary text-white">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="100">
                <div class="p-4">
                    <div class="mb-3">
                        <i class="fas fa-users fa-3x opacity-75"></i>
                    </div>
                    <h2 class="display-4 fw-bold mb-2"><?php echo getSetting('students_count', '1200+'); ?></h2>
                    <p class="lead mb-0">Active Students</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="200">
                <div class="p-4">
                    <div class="mb-3">
                        <i class="fas fa-chalkboard-teacher fa-3x opacity-75"></i>
                    </div>
                    <h2 class="display-4 fw-bold mb-2"><?php echo getSetting('teachers_count', '85+'); ?></h2>
                    <p class="lead mb-0">Qualified Teachers</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="300">
                <div class="p-4">
                    <div class="mb-3">
                        <i class="fas fa-award fa-3x opacity-75"></i>
                    </div>
                    <h2 class="display-4 fw-bold mb-2"><?php echo getSetting('years_experience', '25'); ?></h2>
                    <p class="lead mb-0">Years of Excellence</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="400">
                <div class="p-4">
                    <div class="mb-3">
                        <i class="fas fa-trophy fa-3x opacity-75"></i>
                    </div>
                    <h2 class="display-4 fw-bold mb-2"><?php echo getSetting('graduation_rate', '98'); ?>%</h2>
                    <p class="lead mb-0">Success Rate</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Important Circular Section -->
<section class="py-5 bg-white" id="circular">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0" data-aos="fade-up">
                    <div class="card-header text-white py-4" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <div class="d-flex align-items-center">
                                <div class="bg-white text-primary rounded-circle p-3 me-3">
                                    <i class="fas fa-bell fa-2x"></i>
                                </div>
                                <div>
                                    <h3 class="mb-1 fw-bold">Important Circular</h3>
                                    <p class="mb-0 opacity-90"><i class="fas fa-calendar-alt me-2"></i>End of Term III 2025</p>
                                </div>
                            </div>
                            <span class="badge bg-warning text-dark px-3 py-2 mt-2 mt-md-0">
                                <i class="fas fa-exclamation-triangle me-1"></i>Urgent
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <p class="lead text-muted mb-4">
                            <strong>Dear Parents/Guardians,</strong> We have important information regarding the end of Term III 2025 and preparations for Term I 2026.
                        </p>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="text-primary me-3">
                                        <i class="fas fa-calendar-check fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-2">Term Opening Date</h5>
                                        <p class="mb-0 text-muted">Term I 2026 opens on <strong class="text-primary">2nd February, 2026</strong> before 5:00 PM</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="text-success me-3">
                                        <i class="fas fa-money-bill-wave fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-2">School Fees Payment</h5>
                                        <p class="mb-0 text-muted">At least <strong class="text-success">half payment</strong> required before reporting</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="text-warning me-3">
                                        <i class="fas fa-book fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-2">Holiday Study Program</h5>
                                        <p class="mb-0 text-muted">S.4 & S.6 students: <strong class="text-warning">19th January, 2026</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="text-danger me-3">
                                        <i class="fas fa-tshirt fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-2">Grooming Standards</h5>
                                        <p class="mb-0 text-muted">Short hair (½ cm), proper uniform, and domestic wear required</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info border-0" style="background-color: #e7f3ff;">
                            <div class="d-flex">
                                <i class="fas fa-info-circle fa-lg text-primary me-3 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold mb-2">Key Points to Note:</h6>
                                    <ul class="mb-0" style="padding-left: 1.2rem;">
                                        <li>UCE/UACE 2025 examinations completed successfully</li>
                                        <li>Clear all previous school fees balances during the holiday</li>
                                        <li>Students must report with required items (uniform, domestic wear, stationery)</li>
                                        <li>Holiday study program compulsory for S.4 and S.6 candidates</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <a href="circular.php" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-file-alt me-2"></i>Read Full Circular
                            </a>
                        </div>

                        <div class="text-center mt-3">
                            <p class="text-muted mb-0">
                                <small><i class="fas fa-user-tie me-1"></i><strong>Mugisha Leonard</strong> - Headteacher</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- News Section -->
<?php if (!empty($latestNews)): ?>
    <section class="section news" id="news">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Latest News & Events</h2>
                <p>Stay updated with the latest happenings at our school</p>
            </div>

            <div class="news-grid">
                <?php foreach ($latestNews as $index => $news): ?>
                    <div class="news-card" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                        <?php if (!empty($news['image'])): ?>
                            <img src="<?php echo SITE_URL; ?>/<?php echo UPLOAD_PATH . $news['image']; ?>" alt="<?php echo htmlspecialchars($news['title']); ?>">
                        <?php else: ?>
                            <img src="<?php echo SITE_URL; ?>/assets/images/default-news.jpg" alt="<?php echo htmlspecialchars($news['title']); ?>">
                        <?php endif; ?>

                        <div class="news-card-content">
                            <h3><?php echo htmlspecialchars($news['title']); ?></h3>
                            <div class="news-date">
                                <i class="fas fa-calendar"></i>
                                <?php echo formatDate($news['created_at']); ?>
                            </div>
                            <p><?php echo truncateText(strip_tags($news['content']), 120); ?></p>
                            <a href="news.php?id=<?php echo $news['id']; ?>" class="btn">Read More</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-3">
                <a href="news.php" class="btn">View All News</a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Gallery Preview Section -->
<?php if (!empty($galleryImages)): ?>
    <section class="section bg-white" id="gallery">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>School Life Gallery</h2>
                <p>Get a glimpse of life at Makerere Competent High School</p>
            </div>

            <div class="gallery-grid">
                <?php foreach ($galleryImages as $index => $image): ?>
                    <div class="gallery-item" data-aos="zoom-in" data-aos-delay="<?php echo ($index + 1) * 100; ?>" data-category="<?php echo $image['category']; ?>">
                        <img src="<?php echo SITE_URL; ?>/<?php echo UPLOAD_PATH . $image['image']; ?>" alt="<?php echo htmlspecialchars($image['title']); ?>">
                        <div class="gallery-overlay">
                            <h4><?php echo htmlspecialchars($image['title']); ?></h4>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-3">
                <a href="gallery.php" class="btn">View Full Gallery</a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Call to Action Section -->
<section class="py-5 bg-gradient-primary text-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center" data-aos="fade-up">
                <h2 class="display-5 fw-bold mb-4">Ready to Join Our School Community?</h2>
                <p class="lead mb-5 opacity-90">Take the first step towards an excellent education. Apply now and become part of our growing family of successful students.</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="admissions.php" class="btn btn-light btn-lg px-5">
                        <i class="fas fa-graduation-cap me-2"></i>Apply for Admission
                    </a>
                    <a href="contact.php" class="btn btn-outline-light btn-lg px-5">
                        <i class="fas fa-calendar-check me-2"></i>Schedule a Visit
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Contact Section -->
<section class="section contact" id="quick-contact">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Get in Touch</h2>
            <p>Have questions? We're here to help!</p>
        </div>

        <div class="contact-grid">
            <div class="contact-info" data-aos="fade-right">
                <h3>Contact Information</h3>

                <div class="contact-item">
                    <div class="icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <strong>Our Location</strong><br>
                        <?php echo SITE_ADDRESS; ?>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <strong>Phone Number</strong><br>
                        <?php echo SITE_PHONE; ?>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <strong>Email Address</strong><br>
                        <a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <strong>Office Hours</strong><br>
                        Monday - Friday: 8:00 AM - 5:00 PM<br>
                        Saturday: 8:00 AM - 1:00 PM
                    </div>
                </div>
            </div>

            <div class="contact-form" data-aos="fade-left">
                <form id="quick-contact-form" data-form-type="contact">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone">
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>

                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" rows="5" required placeholder="Tell us how we can help you..."></textarea>
                    </div>

                    <button type="submit" class="btn">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
    /* Carousel enhancements */
    .hero-carousel {
        position: relative;
        width: 100%;
        overflow: hidden;
        z-index: 1;
    }

    #heroCarousel {
        position: relative;
        width: 100%;
    }

    .carousel-inner {
        position: relative;
        width: 100%;
        overflow: hidden;
    }

    .carousel-item {
        position: relative;
        display: none;
        float: left;
        width: 100%;
        margin-right: -100%;
        backface-visibility: hidden;
    }

    .carousel-item.active {
        display: block;
    }

    .carousel-bg {
        width: 100%;
        height: 600px;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .carousel-bg .container {
        height: 100%;
    }

    .carousel-item h1 {
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.9);
        color: #fff !important;
    }

    .carousel-item .lead {
        text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.9);
        color: #fff !important;
    }

    /* Fade effect */
    .carousel-fade .carousel-item {
        opacity: 0;
        transition-duration: 1s;
        transition-property: opacity;
    }

    .carousel-fade .carousel-item.active,
    .carousel-fade .carousel-item-next.carousel-item-start,
    .carousel-fade .carousel-item-prev.carousel-item-end {
        z-index: 1;
        opacity: 1;
    }

    .carousel-fade .active.carousel-item-start,
    .carousel-fade .active.carousel-item-end {
        z-index: 0;
        opacity: 0;
        transition: opacity 0s 1s;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    }

    .hover-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .hover-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 5%;
        opacity: 0.8;
        z-index: 15;
    }

    .carousel-control-prev:hover,
    .carousel-control-next:hover {
        opacity: 1;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        width: 2.5rem;
        height: 2.5rem;
        background-color: rgba(0, 0, 0, 0.7);
        border-radius: 50%;
    }

    .carousel-indicators {
        bottom: 20px;
        z-index: 15;
    }

    .carousel-indicators button {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin: 0 5px;
        background-color: rgba(255, 255, 255, 0.6);
        border: none;
    }

    .carousel-indicators button.active {
        background-color: #ffd700;
        width: 14px;
        height: 14px;
    }

    /* Responsive adjustments */
    @media (min-width: 1400px) {
        .carousel-bg {
            height: 700px;
        }
    }

    @media (min-width: 992px) and (max-width: 1399px) {
        .carousel-bg {
            height: 600px;
        }
    }

    @media (max-width: 991px) {
        .carousel-bg {
            height: 550px;
        }

        .carousel-item h1 {
            font-size: 2.5rem !important;
        }

        .carousel-item .lead {
            font-size: 1.1rem !important;
        }
    }

    @media (max-width: 768px) {
        .carousel-bg {
            height: 500px;
        }

        .carousel-item h1 {
            font-size: 2rem !important;
        }

        .carousel-item .lead {
            font-size: 1rem !important;
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 8%;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            width: 2rem;
            height: 2rem;
        }

        .btn-lg {
            padding: 0.75rem 2rem !important;
            font-size: 0.95rem !important;
        }
    }

    @media (max-width: 576px) {
        .carousel-bg {
            height: 450px;
        }

        .carousel-item h1 {
            font-size: 1.75rem !important;
        }

        .carousel-item .lead {
            font-size: 0.95rem !important;
        }

        .btn-lg {
            padding: 0.6rem 1.5rem !important;
            font-size: 0.9rem !important;
        }

        .d-flex.gap-3 {
            gap: 0.75rem !important;
        }
    }
</style>

<?php include 'includes/footer.php'; ?>