<?php
// Contact page for Makerere Competent High School
$page_title = 'Contact Us';
$page_description = 'Get in touch with Makerere Competent High School. Find our location, contact information, and send us a message.';
$page_keywords = 'contact Makerere Competent High School, school location, phone number, email, address';

include 'includes/header.php';

$success = '';
$error = '';

// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $subject = sanitizeInput($_POST['subject']);
    $message = sanitizeInput($_POST['message']);

    // Validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Please fill in all required fields.';
    } elseif (!isValidEmail($email)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Save to database
        if (saveContactMessage($name, $email, $subject, $message, $phone)) {
            $success = 'Thank you for your message! We will get back to you soon.';
            // Clear form data
            $name = $email = $phone = $subject = $message = '';
        } else {
            $error = 'Sorry, there was an error sending your message. Please try again.';
        }
    }
}
?>

<!-- Page Header -->
<section class="page-header bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8" data-aos="fade-up">
                <h1 class="display-4 fw-bold mb-3">Contact Us</h1>
                <p class="lead mb-0">We'd love to hear from you. Get in touch with us today!</p>
                <div class="mt-4">
                    <span class="badge bg-white text-primary px-3 py-2 me-2">
                        <i class="fas fa-phone me-1"></i> Quick Response
                    </span>
                    <span class="badge bg-white text-primary px-3 py-2">
                        <i class="fas fa-clock me-1"></i> Available Mon-Sat
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-5">
    <div class="container">
        <!-- Success/Error Messages -->
        <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" data-aos="fade-down">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fa-2x me-3"></i>
                    <div>
                        <h5 class="alert-heading mb-1">Success!</h5>
                        <p class="mb-0"><?php echo $success; ?></p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" data-aos="fade-down">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                    <div>
                        <h5 class="alert-heading mb-1">Error!</h5>
                        <p class="mb-0"><?php echo $error; ?></p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Quick Contact Cards -->
        <div class="row g-4 mb-5">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm h-100 text-center hover-card">
                    <div class="card-body p-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                            <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Visit Us</h5>
                        <p class="text-muted small mb-0"><?php echo SITE_ADDRESS; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm h-100 text-center hover-card">
                    <div class="card-body p-4">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                            <i class="fas fa-phone fa-2x text-success"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Call Us</h5>
                        <p class="text-muted small mb-0">
                            <a href="tel:<?php echo SITE_PHONE; ?>" class="text-decoration-none text-muted"><?php echo SITE_PHONE; ?></a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 shadow-sm h-100 text-center hover-card">
                    <div class="card-body p-4">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                            <i class="fas fa-envelope fa-2x text-warning"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Email Us</h5>
                        <p class="text-muted small mb-0">
                            <a href="mailto:<?php echo SITE_EMAIL; ?>" class="text-decoration-none text-muted"><?php echo SITE_EMAIL; ?></a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="card border-0 shadow-sm h-100 text-center hover-card">
                    <div class="card-body p-4">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                            <i class="fas fa-clock fa-2x text-info"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Office Hours</h5>
                        <p class="text-muted small mb-0">Mon-Fri: 8AM - 5PM<br>Sat: 8AM - 1PM</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information and Form -->
        <div class="row g-4 mb-5">
            <!-- Contact Information -->
            <div class="col-lg-5" data-aos="fade-right">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h3 class="fw-bold text-primary mb-3">
                            <i class="fas fa-info-circle me-2"></i>Get in Touch
                        </h3>
                        <p class="text-muted mb-4">
                            We're here to answer any questions you may have about our school,
                            admissions process, or programs. Don't hesitate to reach out!
                        </p>

                        <div class="mb-4">
                            <div class="d-flex align-items-start mb-3 p-3 bg-light rounded">
                                <div class="bg-primary text-white rounded-circle p-2 me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Our Address</h6>
                                    <p class="text-muted mb-2 small"><?php echo SITE_ADDRESS; ?></p>
                                    <a href="https://maps.google.com/?q=<?php echo urlencode(SITE_ADDRESS); ?>" target="_blank" class="text-primary text-decoration-none small">
                                        <i class="fas fa-external-link-alt me-1"></i>View on Google Maps
                                    </a>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-3 p-3 bg-light rounded">
                                <div class="bg-success text-white rounded-circle p-2 me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Phone Numbers</h6>
                                    <p class="mb-1 small">Main Line: <a href="tel:<?php echo SITE_PHONE; ?>" class="text-decoration-none"><?php echo SITE_PHONE; ?></a></p>
                                    <p class="mb-1 small">Admissions: <a href="tel:+256 704 292872" class="text-decoration-none">+256 704 292872</a></p>
                                    <p class="mb-0 small">Emergency: <a href="tel:+256 704 292872" class="text-decoration-none">+256 704 292872</a></p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-3 p-3 bg-light rounded">
                                <div class="bg-warning text-white rounded-circle p-2 me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Email Addresses</h6>
                                    <p class="mb-1 small">General: <a href="mailto:<?php echo SITE_EMAIL; ?>" class="text-decoration-none"><?php echo SITE_EMAIL; ?></a></p>
                                    <p class="mb-1 small">Admissions: <a href="mailto:admissions@makererecompetenthighschool.com" class="text-decoration-none">admissions@makererecompetenthighschool.com</a></p>
                                    <p class="mb-0 small">Academic: <a href="mailto:academic@makererecompetenthighschool.com" class="text-decoration-none">academic@makererecompetenthighschool.com</a></p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start p-3 bg-light rounded">
                                <div class="bg-info text-white rounded-circle p-2 me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Office Hours</h6>
                                    <p class="mb-1 small">Monday - Friday: 8:00 AM - 5:00 PM</p>
                                    <p class="mb-1 small">Saturday: 8:00 AM - 1:00 PM</p>
                                    <p class="mb-1 small">Sunday: Closed</p>
                                    <p class="mb-0 small text-muted fst-italic">(Holiday hours may vary)</p>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div class="border-top pt-3">
                            <h6 class="fw-bold mb-3">Connect With Us</h6>
                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-outline-primary btn-sm rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="btn btn-outline-info btn-sm rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-outline-danger btn-sm rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="btn btn-outline-success btn-sm rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-7" data-aos="fade-left">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h3 class="fw-bold text-primary mb-3">
                            <i class="fas fa-paper-plane me-2"></i>Send us a Message
                        </h3>
                        <p class="text-muted mb-4">Fill out the form below and we'll get back to you as soon as possible.</p>

                        <form method="POST" action="">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-user text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0 ps-0" id="name" name="name" required
                                            value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>"
                                            placeholder="Enter your full name">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-envelope text-muted"></i>
                                        </span>
                                        <input type="email" class="form-control border-start-0 ps-0" id="email" name="email" required
                                            value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                                            placeholder="your.email@example.com">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-semibold">Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-phone text-muted"></i>
                                        </span>
                                        <input type="tel" class="form-control border-start-0 ps-0" id="phone" name="phone"
                                            value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>"
                                            placeholder="+256 XXX XXXXXX">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="subject" class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-tag text-muted"></i>
                                        </span>
                                        <select class="form-select border-start-0 ps-0" id="subject" name="subject" required>
                                            <option value="">Select a subject</option>
                                            <option value="General Inquiry" <?php echo (isset($subject) && $subject == 'General Inquiry') ? 'selected' : ''; ?>>General Inquiry</option>
                                            <option value="Admissions" <?php echo (isset($subject) && $subject == 'Admissions') ? 'selected' : ''; ?>>Admissions</option>
                                            <option value="Academic Programs" <?php echo (isset($subject) && $subject == 'Academic Programs') ? 'selected' : ''; ?>>Academic Programs</option>
                                            <option value="School Fees" <?php echo (isset($subject) && $subject == 'School Fees') ? 'selected' : ''; ?>>School Fees</option>
                                            <option value="Transportation" <?php echo (isset($subject) && $subject == 'Transportation') ? 'selected' : ''; ?>>Transportation</option>
                                            <option value="Boarding Facilities" <?php echo (isset($subject) && $subject == 'Boarding Facilities') ? 'selected' : ''; ?>>Boarding Facilities</option>
                                            <option value="Sports & Activities" <?php echo (isset($subject) && $subject == 'Sports & Activities') ? 'selected' : ''; ?>>Sports & Activities</option>
                                            <option value="Parent/Guardian Concern" <?php echo (isset($subject) && $subject == 'Parent/Guardian Concern') ? 'selected' : ''; ?>>Parent/Guardian Concern</option>
                                            <option value="Alumni" <?php echo (isset($subject) && $subject == 'Alumni') ? 'selected' : ''; ?>>Alumni</option>
                                            <option value="Partnership/Collaboration" <?php echo (isset($subject) && $subject == 'Partnership/Collaboration') ? 'selected' : ''; ?>>Partnership/Collaboration</option>
                                            <option value="Other" <?php echo (isset($subject) && $subject == 'Other') ? 'selected' : ''; ?>>Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="message" class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="message" name="message" rows="6" required
                                        placeholder="Please provide details about your inquiry..."><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>Please provide as much detail as possible so we can better assist you.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-paper-plane me-2"></i>Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Contact Cards for Different Departments -->
        <div class="mb-5">
            <div class="text-center mb-4" data-aos="fade-up">
                <h3 class="fw-bold text-primary">Department Contacts</h3>
                <p class="text-muted">Reach out to specific departments for specialized assistance</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 shadow-sm h-100 hover-card">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                                    <i class="fas fa-user-graduate fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-2">Admissions Office</h5>
                                    <p class="text-muted mb-2">
                                        <strong>Ms. Baguma Anita</strong><br>
                                        <span class="small">Admissions Officer</span>
                                    </p>
                                    <p class="mb-1 small">
                                        <i class="fas fa-phone text-primary me-2"></i>
                                        <a href="tel:+256704292872" class="text-decoration-none">+256 704 292872</a>
                                    </p>
                                    <p class="mb-2 small">
                                        <i class="fas fa-envelope text-primary me-2"></i>
                                        <a href="mailto:admissions@makererecompetenthighschool.com" class="text-decoration-none">admissions@makererecompetenthighschool.com</a>
                                    </p>
                                    <p class="mb-0 small">
                                        <i class="fas fa-clock text-primary me-2"></i>
                                        <span class="text-muted">Mon-Fri: 8:00 AM - 5:00 PM</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="card border-0 shadow-sm h-100 hover-card">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-success bg-opacity-10 rounded p-3 me-3">
                                    <i class="fas fa-graduation-cap fa-2x text-success"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-2">Academic Affairs</h5>
                                    <p class="text-muted mb-2">
                                        <strong>Mr. Ibrahim</strong><br>
                                        <span class="small">Dean of Studies</span>
                                    </p>
                                    <p class="mb-1 small">
                                        <i class="fas fa-phone text-success me-2"></i>
                                        <a href="tel:+256704292872" class="text-decoration-none">+256 704 292872</a>
                                    </p>
                                    <p class="mb-2 small">
                                        <i class="fas fa-envelope text-success me-2"></i>
                                        <a href="mailto:academic@makererecompetenthighschool.com" class="text-decoration-none">academic@makererecompetenthighschool.com</a>
                                    </p>
                                    <p class="mb-0 small">
                                        <i class="fas fa-clock text-success me-2"></i>
                                        <span class="text-muted">Mon-Fri: 8:00 AM - 5:00 PM</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="bg-light rounded p-4 p-lg-5" data-aos="fade-up">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-primary">Frequently Asked Questions</h3>
                <p class="text-muted">Quick answers to common questions</p>
            </div>
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item border-0 shadow-sm mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true">
                            <i class="fas fa-question-circle text-primary me-2"></i>
                            What are your admission requirements?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            For O-Level admission, students need to have completed Primary 7 with a good PLE certificate. For A-Level, students must have completed O-Level with at least 5 passes including English and Mathematics. We also conduct entrance interviews to assess student readiness.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 shadow-sm mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            <i class="fas fa-question-circle text-primary me-2"></i>
                            Do you offer boarding facilities?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            Yes, we have excellent boarding facilities for both boys and girls. Our dormitories are modern, secure, and supervised by experienced matrons and patrons. Boarding students enjoy nutritious meals, study time, and recreational activities.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 shadow-sm mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            <i class="fas fa-question-circle text-primary me-2"></i>
                            What subjects do you offer?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            We offer a comprehensive curriculum including Sciences (Physics, Chemistry, Biology), Mathematics, English, Literature, History, Geography, Arts subjects, Commercial subjects (Accounts, Entrepreneurship), and technical subjects. We also have strong programs in ICT, Sports, and Arts.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 shadow-sm mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            <i class="fas fa-question-circle text-primary me-2"></i>
                            How can parents stay involved?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            We encourage parent involvement through regular parent-teacher meetings, progress reports, parent committees, and school events. Parents can also communicate with teachers through our online portal and attend workshops on child development and academic support.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 shadow-sm">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                            <i class="fas fa-question-circle text-primary me-2"></i>
                            What is the school fee structure?
                        </button>
                    </h2>
                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            Our school fees vary depending on the level (O-Level or A-Level) and whether the student is a day scholar or boarder. For detailed fee structures and payment plans, please contact our admissions office or visit our <a href="admissions.php" class="text-primary">admissions page</a>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section (Optional) -->
<section class="py-0">
    <div class="position-relative" style="height: 400px;" data-aos="fade-up">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.7576!2d31.5!1d1.6!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMcKwMzYnMDAuMCJOIDMxwrAzMCcwMC4wIkU!5e0!3m2!1sen!2sug!4v1234567890"
            width="100%"
            height="100%"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
        <div class="position-absolute top-0 start-0 m-4">
            <a href="https://maps.google.com/?q=<?php echo urlencode(SITE_ADDRESS); ?>" target="_blank" class="btn btn-light shadow-sm">
                <i class="fas fa-directions me-2"></i>Get Directions
            </a>
        </div>
    </div>
</section>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    }

    .hover-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15) !important;
    }

    .input-group-text {
        border: 1px solid #dee2e6;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #1e3c72;
        box-shadow: 0 0 0 0.2rem rgba(30, 60, 114, 0.15);
    }

    .accordion-button:not(.collapsed) {
        background-color: #f0f4ff;
        color: #1e3c72;
    }

    .accordion-button:focus {
        box-shadow: 0 0 0 0.2rem rgba(30, 60, 114, 0.15);
        border-color: #1e3c72;
    }

    @media (max-width: 768px) {
        .card-body .d-flex {
            flex-direction: column;
        }

        .card-body .d-flex>div:first-child {
            margin-bottom: 1rem;
            margin-right: 0 !important;
        }
    }
</style>

<?php include 'includes/footer.php'; ?>