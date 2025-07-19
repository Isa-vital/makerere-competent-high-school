    </main>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <!-- School Info -->
                <div class="footer-section">
                    <h3>About Our School</h3>
                    <div class="logo">
                        <img src="<?php echo SITE_URL; ?>/assets/images/logo.png" alt="<?php echo SITE_NAME; ?> Logo" style="width: 60px; height: 60px; margin-bottom: 15px;">
                    </div>
                    <p><?php echo getSetting('school_mission', 'To provide quality education and nurture future leaders through excellence in teaching, character development, and community service.'); ?></p>
                    <div class="social-links" style="margin-top: 15px;">
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
                
                <!-- Quick Links -->
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="<?php echo SITE_URL; ?>/index.php">Home</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/about.php">About Us</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/academics.php">Academics</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/admissions.php">Admissions</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/gallery.php">Photo Gallery</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/news.php">News & Events</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/contact.php">Contact Us</a></li>
                    </ul>
                </div>
                
                <!-- Academic Programs -->
                <div class="footer-section">
                    <h3>Academic Programs</h3>
                    <ul>
                        <li><a href="<?php echo SITE_URL; ?>/academics.php#o-level">O-Level (S1-S4)</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/academics.php#a-level">A-Level (S5-S6)</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/academics.php#sciences">Sciences</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/academics.php#arts">Arts & Humanities</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/academics.php#commercial">Commercial Studies</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/academics.php#technical">Technical Studies</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/academics.php#extracurricular">Extra-curricular</a></li>
                    </ul>
                </div>
                
                <!-- Contact Information -->
                <div class="footer-section">
                    <h3>Contact Information</h3>
                    <div class="contact-item">
                        <div class="icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <strong>Address:</strong><br>
                            <?php echo SITE_ADDRESS; ?>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <strong>Phone:</strong><br>
                            <?php echo SITE_PHONE; ?>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <strong>Email:</strong><br>
                            <a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <strong>Office Hours:</strong><br>
                            Monday - Friday: 8:00 AM - 5:00 PM<br>
                            Saturday: 8:00 AM - 1:00 PM
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Newsletter Subscription -->
            <div class="newsletter-section" style="border-top: 1px solid rgba(255,255,255,0.2); padding-top: 2rem; margin-top: 2rem; text-align: center;">
                <h3 style="color: #ffd700; margin-bottom: 1rem;">Stay Updated</h3>
                <p style="margin-bottom: 1.5rem; opacity: 0.9;">Subscribe to our newsletter to receive the latest news and updates from Makerere Competent High School.</p>
                <form class="newsletter-form" style="display: flex; justify-content: center; gap: 10px; max-width: 400px; margin: 0 auto;">
                    <input type="email" placeholder="Enter your email address" required style="flex: 1; padding: 10px; border: none; border-radius: 5px;">
                    <button type="submit" class="btn" style="padding: 10px 20px;">Subscribe</button>
                </form>
            </div>
            
            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved. | Designed with ❤️ by MACOSA. | Isaac Vital</p>
                <p style="margin-top: 10px; font-size: 0.9rem; opacity: 0.8;">
                    <a href="<?php echo SITE_URL; ?>/privacy-policy.php" style="color: inherit; text-decoration: none;">Privacy Policy</a> | 
                    <a href="<?php echo SITE_URL; ?>/terms-of-service.php" style="color: inherit; text-decoration: none;">Terms of Service</a> 
                </p>
            </div>
        </div>
    </footer>

    <!-- JavaScript Files -->
    <script src="assets/js/main.js"></script>
    
    <?php if (basename($_SERVER['PHP_SELF']) == 'gallery.php'): ?>
        <script src="assets/js/gallery.js"></script>
    <?php endif; ?>
    
    <!-- AOS Animation Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    
    <!-- Additional page-specific JavaScript -->
    <?php if (isset($additional_js)): ?>
        <?php foreach ($additional_js as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Google Analytics (replace GA_TRACKING_ID with your actual tracking ID) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=GA_TRACKING_ID"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'GA_TRACKING_ID');
    </script>
    
    <!-- Schema.org structured data for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "EducationalOrganization",
        "name": "<?php echo SITE_NAME; ?>",
        "description": "<?php echo getSetting('school_mission', 'To provide quality education and nurture future leaders'); ?>",
        "url": "<?php echo SITE_URL; ?>",
        "logo": "<?php echo SITE_URL; ?>/assets/images/logo.png",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "<?php echo SITE_ADDRESS; ?>",
            "addressLocality": "Kampala",
            "addressCountry": "Uganda"
        },
        "telephone": "<?php echo SITE_PHONE; ?>",
        "email": "<?php echo SITE_EMAIL; ?>",
        "sameAs": [
            "<?php echo FACEBOOK_URL; ?>",
            "<?php echo TWITTER_URL; ?>",
            "<?php echo INSTAGRAM_URL; ?>",
            "<?php echo YOUTUBE_URL; ?>"
        ]
    }
    </script>
    
    <!-- Cookie Consent (optional) -->
    <div id="cookie-consent" style="display: none; position: fixed; bottom: 0; left: 0; right: 0; background: rgba(26, 71, 42, 0.95); color: white; padding: 15px; text-align: center; z-index: 10000;">
        <p style="margin: 0; margin-bottom: 10px;">This website uses cookies to enhance your browsing experience. By continuing to use this site, you consent to our use of cookies.</p>
        <button onclick="acceptCookies()" class="btn" style="margin-right: 10px;">Accept</button>
        <button onclick="declineCookies()" style="background: transparent; border: 1px solid white; color: white; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Decline</button>
    </div>
    
    <script>
        // Cookie consent functionality
        function checkCookieConsent() {
            if (!localStorage.getItem('cookieConsent')) {
                document.getElementById('cookie-consent').style.display = 'block';
            }
        }
        
        function acceptCookies() {
            localStorage.setItem('cookieConsent', 'accepted');
            document.getElementById('cookie-consent').style.display = 'none';
        }
        
        function declineCookies() {
            localStorage.setItem('cookieConsent', 'declined');
            document.getElementById('cookie-consent').style.display = 'none';
        }
        
        // Check cookie consent on page load
        document.addEventListener('DOMContentLoaded', checkCookieConsent);
        
        // Initialize footer functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Newsletter subscription
            const newsletterForm = document.querySelector('.newsletter-form');
            if (newsletterForm) {
                newsletterForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const email = this.querySelector('input[type="email"]').value;
                    
                    // Here you would typically send an AJAX request to subscribe the email
                    // For now, we'll just show a success message
                    alert('Thank you for subscribing to our newsletter!');
                    this.reset();
                });
            }
            
            // Smooth scroll for footer links
            const footerLinks = document.querySelectorAll('.footer a[href^="#"]');
            footerLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const targetElement = document.getElementById(targetId);
                    
                    if (targetElement) {
                        targetElement.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            });
        });
    </script>
    
    <!-- Bootstrap JS for reliable mobile menu functionality -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
