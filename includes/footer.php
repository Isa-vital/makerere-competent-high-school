    </main>

    <!-- Footer Section -->
    <footer class="footer bg-dark text-white pt-5">
        <div class="container">
            <!-- Main Footer Content -->
            <div class="row g-4 pb-4">
                <!-- School Info -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand mb-4">
                        <img src="assets/images/competentlogo.jpeg"
                            alt="<?php echo SITE_NAME; ?> Logo"
                            class="mb-3 rounded-circle"
                            style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #ffd700;">
                        <h4 class="text-white fw-bold"><?php echo SITE_NAME; ?></h4>
                    </div>
                    <p class="text-white-50 mb-4">
                        <?php echo getSetting('school_motto', 'Quality Education for a Better Future'); ?>
                    </p>
                    <div class="d-flex gap-3 mb-4">
                        <a href="<?php echo FACEBOOK_URL; ?>" target="_blank" rel="noopener"
                            class="btn btn-outline-light btn-sm rounded-circle"
                            style="width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center;"
                            title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="<?php echo TWITTER_URL; ?>" target="_blank" rel="noopener"
                            class="btn btn-outline-light btn-sm rounded-circle"
                            style="width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center;"
                            title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="<?php echo INSTAGRAM_URL; ?>" target="_blank" rel="noopener"
                            class="btn btn-outline-light btn-sm rounded-circle"
                            style="width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center;"
                            title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="<?php echo YOUTUBE_URL; ?>" target="_blank" rel="noopener"
                            class="btn btn-outline-light btn-sm rounded-circle"
                            style="width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center;"
                            title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6">
                    <h5 class="text-warning fw-bold mb-3">Quick Links</h5>
                    <ul class="list-unstyled footer-links">
                        <li class="mb-2"><a href="index.php" class="text-white-50 text-decoration-none hover-link"><i class="fas fa-chevron-right me-2 small"></i>Home</a></li>
                        <li class="mb-2"><a href="about.php" class="text-white-50 text-decoration-none hover-link"><i class="fas fa-chevron-right me-2 small"></i>About Us</a></li>
                        <li class="mb-2"><a href="academics.php" class="text-white-50 text-decoration-none hover-link"><i class="fas fa-chevron-right me-2 small"></i>Academics</a></li>
                        <li class="mb-2"><a href="admissions.php" class="text-white-50 text-decoration-none hover-link"><i class="fas fa-chevron-right me-2 small"></i>Admissions</a></li>
                        <li class="mb-2"><a href="gallery.php" class="text-white-50 text-decoration-none hover-link"><i class="fas fa-chevron-right me-2 small"></i>Gallery</a></li>
                        <li class="mb-2"><a href="news.php" class="text-white-50 text-decoration-none hover-link"><i class="fas fa-chevron-right me-2 small"></i>News & Events</a></li>
                        <li class="mb-2"><a href="contact.php" class="text-white-50 text-decoration-none hover-link"><i class="fas fa-chevron-right me-2 small"></i>Contact</a></li>
                    </ul>
                </div>

                <!-- Academic Programs -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-warning fw-bold mb-3">Our Programs</h5>
                    <ul class="list-unstyled footer-links">
                        <li class="mb-2"><a href="academics.php#o-level" class="text-white-50 text-decoration-none hover-link"><i class="fas fa-graduation-cap me-2 small"></i>O-Level (S1-S4)</a></li>
                        <li class="mb-2"><a href="academics.php#a-level" class="text-white-50 text-decoration-none hover-link"><i class="fas fa-graduation-cap me-2 small"></i>A-Level (S5-S6)</a></li>
                        <li class="mb-2"><a href="academics.php#sciences" class="text-white-50 text-decoration-none hover-link"><i class="fas fa-flask me-2 small"></i>Sciences</a></li>
                        <li class="mb-2"><a href="academics.php#arts" class="text-white-50 text-decoration-none hover-link"><i class="fas fa-book me-2 small"></i>Arts & Humanities</a></li>
                        <li class="mb-2"><a href="academics.php#commercial" class="text-white-50 text-decoration-none hover-link"><i class="fas fa-calculator me-2 small"></i>Commercial Studies</a></li>
                        <li class="mb-2"><a href="academics.php#extracurricular" class="text-white-50 text-decoration-none hover-link"><i class="fas fa-futbol me-2 small"></i>Extra-curricular</a></li>
                    </ul>
                </div>

                <!-- Contact Information -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-warning fw-bold mb-3">Contact Us</h5>
                    <ul class="list-unstyled footer-contact">
                        <li class="mb-3 d-flex">
                            <div class="me-3 text-warning">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="text-white-50 small">
                                <?php echo SITE_ADDRESS; ?>
                            </div>
                        </li>
                        <li class="mb-3 d-flex">
                            <div class="me-3 text-warning">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <a href="tel:<?php echo SITE_PHONE; ?>" class="text-white-50 text-decoration-none hover-link small">
                                    <?php echo SITE_PHONE; ?>
                                </a>
                            </div>
                        </li>
                        <li class="mb-3 d-flex">
                            <div class="me-3 text-warning">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <a href="mailto:<?php echo SITE_EMAIL; ?>" class="text-white-50 text-decoration-none hover-link small">
                                    <?php echo SITE_EMAIL; ?>
                                </a>
                            </div>
                        </li>
                        <li class="mb-3 d-flex">
                            <div class="me-3 text-warning">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="text-white-50 small">
                                Mon - Fri: 8:00 AM - 5:00 PM<br>
                                Sat: 8:00 AM - 1:00 PM
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Newsletter Section -->
            <div class="row py-4 border-top border-secondary">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="newsletter-section">
                        <div class="mb-3">
                            <i class="fas fa-envelope-open-text fa-2x text-warning mb-2"></i>
                            <h5 class="text-white fw-bold mb-2">Stay Updated with Our Newsletter</h5>
                            <p class="text-white-50 small mb-3">Subscribe to receive the latest news, events, and updates from Makerere Competent High School.</p>
                        </div>
                        <form class="newsletter-form row g-2 justify-content-center">
                            <div class="col-auto" style="min-width: 300px;">
                                <div class="input-group">
                                    <input type="email"
                                        class="form-control"
                                        placeholder="Enter your email address"
                                        required>
                                    <button type="submit" class="btn btn-warning fw-semibold px-4">
                                        <i class="fas fa-paper-plane me-2"></i>Subscribe
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="border-top border-secondary py-4">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <p class="mb-0 text-white-50 small">
                            &copy; <?php echo date('Y'); ?> <span class="text-white fw-semibold"><?php echo SITE_NAME; ?></span>. All rights reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="mb-0 text-white-50 small">
                            Designed with <i class="fas fa-heart text-danger"></i> by
                            <span class="text-warning fw-semibold">MACOSA | Isaac Vital</span>
                        </p>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12 text-center">
                        <div class="small">
                            <a href="privacy-policy.php" class="text-white-50 text-decoration-none hover-link me-3">Privacy Policy</a>
                            <span class="text-white-50">|</span>
                            <a href="terms-of-service.php" class="text-white-50 text-decoration-none hover-link ms-3">Terms of Service</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to Top Button -->
        <button id="backToTop"
            class="btn btn-warning rounded-circle shadow-lg"
            style="position: fixed; bottom: 30px; right: 30px; width: 50px; height: 50px; display: none; z-index: 1000; border: none;">
            <i class="fas fa-arrow-up"></i>
        </button>
    </footer>

    <style>
        .footer {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        }

        .hover-link {
            transition: all 0.3s ease;
        }

        .hover-link:hover {
            color: #ffd700 !important;
            padding-left: 5px;
        }

        .footer-links li a i {
            transition: transform 0.3s ease;
        }

        .footer-links li a:hover i {
            transform: translateX(3px);
        }

        .btn-outline-light:hover {
            background: #ffd700;
            border-color: #ffd700;
            color: #1a1a2e;
        }

        .newsletter-form .btn-warning:hover {
            background: #ffed4e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
        }

        #backToTop {
            transition: all 0.3s ease;
        }

        #backToTop:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(255, 215, 0, 0.5) !important;
        }
    </style>

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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Newsletter subscription
        document.addEventListener('DOMContentLoaded', function() {
            const newsletterForm = document.querySelector('.newsletter-form');
            if (newsletterForm) {
                newsletterForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const email = this.querySelector('input[type="email"]').value;

                    // Show success message
                    alert('Thank you for subscribing to our newsletter!');
                    this.reset();
                });
            }

            // Back to top button
            const backToTopBtn = document.getElementById('backToTop');

            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTopBtn.style.display = 'block';
                } else {
                    backToTopBtn.style.display = 'none';
                }
            });

            backToTopBtn.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    </script>

    <!-- Schema.org structured data for SEO -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "EducationalOrganization",
            "name": "<?php echo SITE_NAME; ?>",
            "description": "<?php echo getSetting('school_mission', 'To provide quality education and nurture future leaders'); ?>",
            "url": "http://localhost/competent",
            "logo": "http://localhost/competent/assets/images/competentlogo.jpeg",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "<?php echo SITE_ADDRESS; ?>",
                "addressLocality": "Kikuube",
                "addressRegion": "Western Region",
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
    </body>

    </html>