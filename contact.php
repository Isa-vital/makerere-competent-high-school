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
<div class="page-header">
    <div class="container">
        <h1>Contact Us</h1>
        <p>We'd love to hear from you. Get in touch with us today!</p>
    </div>
</div>

<!-- Main Content -->
<section class="page-content">
    <div class="container">
        <?php if (!empty($success)): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 2rem; text-align: center;">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 2rem; text-align: center;">
                <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Contact Information and Form -->
        <div class="contact-grid">
            <!-- Contact Information -->
            <div class="contact-info" data-aos="fade-right">
                <h3>Get in Touch</h3>
                <p style="margin-bottom: 2rem; line-height: 1.6;">
                    We're here to answer any questions you may have about our school, 
                    admissions process, or programs. Don't hesitate to reach out!
                </p>
                
                <div class="contact-item">
                    <div class="icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <strong>Our Address</strong><br>
                        <?php echo SITE_ADDRESS; ?><br>
                        <a href="https://maps.google.com/?q=<?php echo urlencode(SITE_ADDRESS); ?>" target="_blank" style="color: #1a472a; text-decoration: none;">
                            <i class="fas fa-external-link-alt"></i> View on Google Maps
                        </a>
                    </div>
                </div>
                
                <div class="contact-item">
                    <div class="icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <strong>Phone Numbers</strong><br>
                        Main Line: <a href="tel:<?php echo SITE_PHONE; ?>" style="color: #1a472a;"><?php echo SITE_PHONE; ?></a><br>
                        Admissions: <a href="tel:+256414532124" style="color: #1a472a;">+256 414 532 124</a><br>
                        Emergency: <a href="tel:+256414532125" style="color: #1a472a;">+256 414 532 125</a>
                    </div>
                </div>
                
                <div class="contact-item">
                    <div class="icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <strong>Email Addresses</strong><br>
                        General: <a href="mailto:<?php echo SITE_EMAIL; ?>" style="color: #1a472a;"><?php echo SITE_EMAIL; ?></a><br>
                        Admissions: <a href="mailto:admissions@makererecompetent.edu.ug" style="color: #1a472a;">admissions@makererecompetent.edu.ug</a><br>
                        Academic: <a href="mailto:academic@makererecompetent.edu.ug" style="color: #1a472a;">academic@makererecompetent.edu.ug</a>
                    </div>
                </div>
                
                <div class="contact-item">
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <strong>Office Hours</strong><br>
                        Monday - Friday: 8:00 AM - 5:00 PM<br>
                        Saturday: 8:00 AM - 1:00 PM<br>
                        Sunday: Closed<br>
                        <small style="color: #666;">(Holiday hours may vary)</small>
                    </div>
                </div>
            
            </div>
            
            <!-- Contact Form -->
            <div class="contact-form" data-aos="fade-left">
                <h3 style="margin-bottom: 1.5rem; color: #1a472a;">Send us a Message</h3>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" required 
                               value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>"
                               placeholder="Enter your full name">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                               placeholder="Enter your email address">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" 
                               value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>"
                               placeholder="Enter your phone number">
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <select id="subject" name="subject" required>
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
                    
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" rows="6" required 
                                  placeholder="Please provide details about your inquiry..."><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>

        <!-- Map Section -->
        <div class="section" style="margin-top: 4rem;" data-aos="fade-up">
            <h3 style="color: #1a472a; text-align: center; margin-bottom: 2rem;">Find Us on the Map</h3>
            <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <!-- Embedded Google Map -->
                <div style="position: relative; height: 400px; border-radius: 10px; overflow: hidden;">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.7527168513897!2d32.5729!3d0.3317!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x177dbb6b3a5f6d0f%3A0x1234567890abcdef!2sMakerere%20Hill%2C%20Kampala%2C%20Uganda!5e0!3m2!1sen!2sus!4v1234567890123!5m2!1sen!2sus" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <div style="text-align: center; margin-top: 1rem;">
                    <a href="https://maps.google.com/?q=<?php echo urlencode(SITE_ADDRESS); ?>" target="_blank" class="btn">
                        <i class="fas fa-directions"></i> Get Directions
                    </a>
                </div>
            </div>
        </div>

        <!-- Contact Cards for Different Departments -->
        <div class="section bg-white" style="padding: 4rem 0;">
            <h3 style="color: #1a472a; text-align: center; margin-bottom: 3rem;" data-aos="fade-up">Department Contacts</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
                <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h4>Admissions Office</h4>
                    <p><strong>Ms. Baguma Anita</strong><br>Admissions Officer</p>
                    <p><i class="fas fa-phone"></i> +256 414 532 124<br>
                       <i class="fas fa-envelope"></i> admissions@makererecompetent.edu.ug</p>
                    <p><strong>Office Hours:</strong> Mon-Fri 8:00 AM - 5:00 PM</p>
                </div>
                
                
                <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h4>Academic Affairs</h4>
                    <p><strong>Mr. Ibrahim</strong><br>Dean of Studies</p>
                    <p><i class="fas fa-phone"></i> +256 414 532 127<br>
                       <i class="fas fa-envelope"></i> academic@makererecompetent.edu.ug</p>
                    <p><strong>Office Hours:</strong> Mon-Fri 8:00 AM - 5:00 PM</p>
                </div>
                
                
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="section" data-aos="fade-up">
            <h3 style="color: #1a472a; text-align: center; margin-bottom: 3rem;">Frequently Asked Questions</h3>
            <div style="max-width: 800px; margin: 0 auto;">
                <div style="background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); overflow: hidden;">
                    <div class="faq-item" style="border-bottom: 1px solid #eee; padding: 1.5rem;">
                        <h4 style="color: #1a472a; margin-bottom: 1rem; cursor: pointer;" onclick="toggleFAQ(this)">
                            <i class="fas fa-plus" style="margin-right: 10px; transition: transform 0.3s;"></i>
                            What are your admission requirements?
                        </h4>
                        <div class="faq-answer" style="display: none; color: #666; line-height: 1.6;">
                            <p>For O-Level admission, students need to have completed Primary 7 with a good PLE certificate. For A-Level, students must have completed O-Level with at least 5 passes including English and Mathematics. We also conduct entrance interviews to assess student readiness.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item" style="border-bottom: 1px solid #eee; padding: 1.5rem;">
                        <h4 style="color: #1a472a; margin-bottom: 1rem; cursor: pointer;" onclick="toggleFAQ(this)">
                            <i class="fas fa-plus" style="margin-right: 10px; transition: transform 0.3s;"></i>
                            Do you offer boarding facilities?
                        </h4>
                        <div class="faq-answer" style="display: none; color: #666; line-height: 1.6;">
                            <p>Yes, we have excellent boarding facilities for both boys and girls. Our dormitories are modern, secure, and supervised by experienced matrons and patrons. Boarding students enjoy nutritious meals, study time, and recreational activities.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item" style="border-bottom: 1px solid #eee; padding: 1.5rem;">
                        <h4 style="color: #1a472a; margin-bottom: 1rem; cursor: pointer;" onclick="toggleFAQ(this)">
                            <i class="fas fa-plus" style="margin-right: 10px; transition: transform 0.3s;"></i>
                            What subjects do you offer?
                        </h4>
                        <div class="faq-answer" style="display: none; color: #666; line-height: 1.6;">
                            <p>We offer a comprehensive curriculum including Sciences (Physics, Chemistry, Biology), Mathematics, English, Literature, History, Geography, Arts subjects, Commercial subjects (Accounts, Entrepreneurship), and technical subjects. We also have strong programs in ICT, Sports, and Arts.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item" style="padding: 1.5rem;">
                        <h4 style="color: #1a472a; margin-bottom: 1rem; cursor: pointer;" onclick="toggleFAQ(this)">
                            <i class="fas fa-plus" style="margin-right: 10px; transition: transform 0.3s;"></i>
                            How can parents stay involved?
                        </h4>
                        <div class="faq-answer" style="display: none; color: #666; line-height: 1.6;">
                            <p>We encourage parent involvement through regular parent-teacher meetings, progress reports, parent committees, and school events. Parents can also communicate with teachers through our online portal and attend workshops on child development and academic support.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.faq-item h4:hover {
    color: #2d5a3d !important;
}

.faq-item.active .fas {
    transform: rotate(45deg);
}

@media (max-width: 768px) {
    .contact-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .contact-item {
        flex-direction: column;
        text-align: center;
    }
    
    .contact-item .icon {
        margin-bottom: 1rem;
        margin-right: 0;
    }
}
</style>

<script>
function toggleFAQ(element) {
    const faqItem = element.parentElement;
    const answer = faqItem.querySelector('.faq-answer');
    const icon = element.querySelector('.fas');
    
    // Close all other FAQ items
    document.querySelectorAll('.faq-item').forEach(item => {
        if (item !== faqItem) {
            item.classList.remove('active');
            item.querySelector('.faq-answer').style.display = 'none';
            item.querySelector('.fas').style.transform = 'rotate(0deg)';
        }
    });
    
    // Toggle current FAQ item
    if (answer.style.display === 'none' || answer.style.display === '') {
        answer.style.display = 'block';
        icon.style.transform = 'rotate(45deg)';
        faqItem.classList.add('active');
    } else {
        answer.style.display = 'none';
        icon.style.transform = 'rotate(0deg)';
        faqItem.classList.remove('active');
    }
}
</script>

<?php include 'includes/footer.php'; ?>
