<?php
// Homepage for Makerere Competent High School
$page_title = 'Home';
$page_description = 'Makerere Competent High School - Excellence in Education. We provide quality secondary education with a focus on academic excellence, character development, and holistic growth.';
$page_keywords = 'Makerere Competent High School, Uganda secondary school, quality education, Kampala school, excellence in education';

include 'includes/header.php';

// Get latest news and gallery items
$latestNews = getNews(3, true);
$galleryImages = getGalleryImages(null, 6);
?>

<!-- Hero Carousel Section -->
<section class="hero-carousel">
    <div class="carousel-container">
        <!-- Carousel Slides -->
        <div class="carousel-slide active" style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('assets/images/gate.jpeg'); background-size: cover; background-position: center;">
            <div class="container">
                <div class="hero-content" data-aos="fade-up">
                    <h2>Welcome to <?php echo SITE_NAME; ?></h2>
                    <p>Where Excellence Meets Education - Nurturing Future Leaders Through Quality Learning, Character Development, and Academic Excellence</p>
                    <a href="about.php" class="btn">Learn More About Us</a>
                    <a href="admissions.php" class="btn btn-secondary">Apply Now</a>
                </div>
            </div>
        </div>
        
        <div class="carousel-slide" style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('assets/images/WhatsApp\ Image\ 2024-11-13\ at\ 09.24.10.jpeg'); background-size: cover; background-position: center;">
            <div class="container">
                <div class="hero-content" data-aos="fade-up">
                    <h2>Academic Excellence Since 1995</h2>
                    <p>Over 25 years of providing quality education with a track record of 98% success rate in national examinations</p>
                    <a href="academics.php" class="btn">Our Programs</a>
                    <a href="gallery.php" class="btn btn-secondary">View Gallery</a>
                </div>
            </div>
        </div>
        
        <div class="carousel-slide" style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('assets/images/microscope.jpg'); background-size: cover; background-position: center;">
            <div class="container">
                <div class="hero-content" data-aos="fade-up">
                    <h2>Modern Facilities & Technology</h2>
                    <p>State-of-the-art laboratories, digital classrooms, sports facilities, and boarding accommodation for an enhanced learning experience</p>
                    <a href="about.php#facilities" class="btn" style="background: var(--primary-blue); color: var(--neutral-white);">Tour Our Campus</a>
                    <a href="contact.php" class="btn btn-secondary">Schedule Visit</a>
                </div>
            </div>
        </div>
        
        <div class="carousel-slide" style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('assets/images/microscope.jpg'); background-size: cover; background-position: center;">
            <div class="container">
                <div class="hero-content" data-aos="fade-up">
                    <h2>Join Our MACOSA Organisation</h2>
                    <p>Connect with over 2,500 successful graduates worldwide, both within and abroad, in various professional fields</p>
                    <a href="alumni.php" class="btn">Alumni Stories</a>
                    <a href="alumni.php#register" class="btn btn-secondary">Join Network</a>
                </div>
            </div>
        </div>
        
        <!-- Carousel Navigation -->
        <button class="carousel-nav carousel-prev" onclick="previousSlide()">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="carousel-nav carousel-next" onclick="nextSlide()">
            <i class="fas fa-chevron-right"></i>
        </button>
        
        <!-- Carousel Indicators -->
        <div class="carousel-indicators">
            <button class="indicator active" onclick="currentSlide(1)"></button>
            <button class="indicator" onclick="currentSlide(2)"></button>
            <button class="indicator" onclick="currentSlide(3)"></button>
            <button class="indicator" onclick="currentSlide(4)"></button>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="section features" id="about">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Why Choose Makerere Competent High School?</h2>
            <p>We are committed to providing exceptional education that prepares students for success in their academic journey and beyond.</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                <div class="icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>Academic Excellence</h3>
                <p>Our rigorous curriculum and experienced teachers ensure students achieve their highest potential in both O-Level and A-Level studies.</p>
            </div>
            
            <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Character Development</h3>
                <p>We focus on building strong moral values, leadership skills, and social responsibility in all our students.</p>
            </div>
            
            <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                <div class="icon">
                    <i class="fas fa-microscope"></i>
                </div>
                <h3>Modern Facilities</h3>
                <p>State-of-the-art laboratories, libraries, and classrooms equipped with the latest technology for enhanced learning.</p>
            </div>
            
            <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
                <div class="icon">
                    <i class="fas fa-medal"></i>
                </div>
                <h3>Sports & Arts</h3>
                <p>Comprehensive extra-curricular programs including sports, music, drama, and various clubs to develop well-rounded individuals.</p>
            </div>
            
            <div class="feature-card" data-aos="fade-up" data-aos-delay="500">
                <div class="icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3>Career Guidance</h3>
                <p>Professional guidance and counseling services to help students make informed decisions about their future careers.</p>
            </div>
            
            <div class="feature-card" data-aos="fade-up" data-aos-delay="600">
                <div class="icon">
                    <i class="fas fa-globe"></i>
                </div>
                <h3>Global Perspective</h3>
                <p>We prepare students for a globalized world with international partnerships and exchange programs.</p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="section stats">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item" data-aos="zoom-in" data-aos-delay="100">
                <h3><?php echo getSetting('students_count', '1200+'); ?></h3>
                <p>Active Students</p>
            </div>
            <div class="stat-item" data-aos="zoom-in" data-aos-delay="200">
                <h3><?php echo getSetting('teachers_count', '25+'); ?></h3>
                <p>Qualified Teachers</p>
            </div>
            <div class="stat-item" data-aos="zoom-in" data-aos-delay="300">
                <h3><?php echo getSetting('years_experience', '25'); ?></h3>
                <p>Years of Excellence</p>
            </div>
            <div class="stat-item" data-aos="zoom-in" data-aos-delay="400">
                <h3><?php echo getSetting('graduation_rate', '98'); ?>%</h3>
                <p>Success Rate</p>
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
<section class="section bg-green">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2 style="color: white;">Ready to Join Our School Community?</h2>
            <p style="color: rgba(255,255,255,0.9);">Take the first step towards an excellent education. Apply now and become part of our growing family of successful students.</p>
        </div>
        
        <div class="text-center">
            <a href="admissions.php" class="btn" style="margin-right: 15px;">Apply for Admission</a>
            <a href="contact.php" class="btn btn-secondary">Schedule a Visit</a>
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

<?php include 'includes/footer.php'; ?>