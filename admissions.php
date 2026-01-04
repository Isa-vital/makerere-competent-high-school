<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = "Admissions - Makerere Competent High School";
$meta_description = "Join Makerere Competent High School. Learn about our admission requirements, application process, scholarships, and how to become part of our exceptional learning community.";

// Handle admission inquiry form submission
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_inquiry'])) {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $student_name = sanitizeInput($_POST['student_name']);
    $current_class = sanitizeInput($_POST['current_class']);
    $desired_class = sanitizeInput($_POST['desired_class']);
    $inquiry_type = sanitizeInput($_POST['inquiry_type']);
    $message = sanitizeInput($_POST['message']);

    if (empty($name) || empty($email) || empty($student_name) || empty($inquiry_type)) {
        $error = "Please fill in all required fields.";
    } elseif (!isValidEmail($email)) {
        $error = "Please enter a valid email address.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO contacts (name, email, phone, subject, message, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $inquiry_subject = "Admission Inquiry - " . $inquiry_type;
            $full_message = "Student Name: $student_name\nCurrent Class: $current_class\nDesired Class: $desired_class\n\nMessage:\n$message";

            if ($stmt->execute([$name, $email, $phone, $inquiry_subject, $full_message])) {
                $success = "Your admission inquiry has been submitted successfully! We'll contact you within 24 hours.";

                // Send email notification
                $to = SITE_EMAIL;
                $subject = "New Admission Inquiry - " . $inquiry_type;
                $email_message = "New admission inquiry received:\n\n";
                $email_message .= "Parent/Guardian: $name\n";
                $email_message .= "Email: $email\n";
                $email_message .= "Phone: $phone\n";
                $email_message .= "Student Name: $student_name\n";
                $email_message .= "Current Class: $current_class\n";
                $email_message .= "Desired Class: $desired_class\n";
                $email_message .= "Inquiry Type: $inquiry_type\n\n";
                $email_message .= "Message:\n$message";

                $headers = "From: " . $email . "\r\n";
                $headers .= "Reply-To: " . $email . "\r\n";

                mail($to, $subject, $email_message, $headers);

                // Clear form data
                unset($name, $email, $phone, $student_name, $current_class, $desired_class, $inquiry_type, $message);
            } else {
                $error = "Sorry, there was an error submitting your inquiry. Please try again.";
            }
        } catch (PDOException $e) {
            $error = "Sorry, there was an error submitting your inquiry. Please try again.";
        }
    }
}

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 data-aos="fade-up">Admissions</h1>
        <p data-aos="fade-up" data-aos-delay="200">Join Our School of Excellence</p>
    </div>
</section>

<!-- Main Content -->
<section class="page-content">
    <div class="container">
        <!-- Welcome Section -->
        <div class="section" data-aos="fade-up">
            <div style="text-align: center; max-width: 800px; margin: 0 auto 4rem;">
                <h2 style="color: var(--primary-blue); margin-bottom: 1.5rem;">Welcome to Makerere Competent High School</h2>
                <p style="font-size: 1.1rem; line-height: 1.8; color: var(--text-light);">
                    We are delighted that you are considering Makerere Competent High School for your child's education.
                    Our admission process is designed to identify students who will thrive in our challenging academic
                    environment and contribute positively to our school community.
                </p>
            </div>
        </div>

        <!-- Admission Requirements -->
        <div class="section bg-white" style="padding: 4rem 0;">
            <div class="section-title" data-aos="fade-up">
                <h2>Admission Requirements</h2>
                <p>Everything you need to know about joining our school</p>
            </div>

            <div class="features-grid">
                <!-- O-Level Requirements -->
                <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon" style="background: linear-gradient(135deg, var(--primary-blue), #3399ff);">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>O-Level (S1-S4)</h3>
                    <ul style="text-align: left; color: var(--text-light); line-height: 1.8;">
                        <li>Primary Leaving Examination (PLE) Certificate</li>
                        <li>Minimum of 24 aggregates in PLE</li>
                        <li>Results proof of the previous class attended</li>
                        <li>Non Refundable Registration Fee of UGX 30,000</li>
                    </ul>
                </div>

                <!-- A-Level Requirements -->
                <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon" style="background: linear-gradient(135deg, var(--accent-green), #55bb55);">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3>A-Level (S5-S6)</h3>
                    <ul style="text-align: left; color: var(--text-light); line-height: 1.8;">
                        <li>Uganda Certificate of Education (UCE) Passlip</li>
                        <li>Minimum of 5 passes in relevant subjects</li>
                        <li>At least 2 principal passes for chosen combination</li>
                        <li>Non Refundable Registration Fee of UGX 30,000</li>
                    </ul>
                </div>

                <!-- International Students -->
                <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="icon" style="background: linear-gradient(135deg, var(--accent-yellow), #ffdd00);">
                        <i class="fas fa-globe-africa"></i>
                    </div>
                    <h3>International Students</h3>
                    <ul style="text-align: left; color: var(--text-light); line-height: 1.8;">
                        <li>Equivalent academic qualifications</li>
                        <li>English proficiency test results</li>
                        <li>Previous school transcripts (translated)</li>
                        <li>Proof of financial support</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Application Process -->
        <div class="section">
            <div class="section-title" data-aos="fade-up">
                <h2>Application Process</h2>
                <p>Simple steps to join our school community</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 3rem;">
                <div class="process-step" data-aos="fade-up" data-aos-delay="100">
                    <div style="background: var(--primary-blue); color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem;">1</div>
                    <h4 style="color: var(--primary-blue); margin-bottom: 1rem;">Submit Application</h4>
                    <p style="color: var(--text-light); line-height: 1.6;">Complete the online application form or visit our school to collect and submit a physical application form with all required documents.</p>
                </div>

                <div class="process-step" data-aos="fade-up" data-aos-delay="200">
                    <div style="background: var(--accent-green); color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem;">2</div>
                    <h4 style="color: var(--primary-blue); margin-bottom: 1rem;">Document Review</h4>
                    <p style="color: var(--text-light); line-height: 1.6;">Our admissions team reviews your application and documents. We may contact you for additional information or clarification.</p>
                </div>


                <div class="process-step" data-aos="fade-up" data-aos-delay="200">
                    <div style="background: var(--primary-blue); color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem;">4</div>
                    <h4 style="color: var(--primary-blue); margin-bottom: 1rem;">Admission Decision</h4>
                    <p style="color: var(--text-light); line-height: 1.6;">Receive admission decision within 2 weeks. If accepted, complete enrollment by paying fees and attending orientation.</p>
                </div>
            </div>
        </div>

        <!-- School Fees Structure -->
        <div class="section bg-white" style="padding: 4rem 0;">
            <div class="section-title" data-aos="fade-up">
                <h2>School Fees Structure</h2>
                <p>Transparent and affordable education investment</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem; margin-top: 3rem;">
                <!-- Day Students -->
                <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-top: 4px solid var(--primary-blue);" data-aos="fade-up" data-aos-delay="100">
                    <h3 style="color: var(--primary-blue); margin-bottom: 1.5rem; text-align: center;">Day Students</h3>
                    <div style="margin-bottom: 1.5rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>O-Level (S1-S4):</span>
                            <strong>UGX 450,000/term</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>A-Level (S5-S6):</span>
                            <strong>UGX 650,000/term</strong>
                        </div>
                    </div>
                    <div style="background: var(--background-light); padding: 1rem; border-radius: 5px;">
                        <strong style="color: var(--primary-blue);">Includes:</strong>
                        <ul style="margin-top: 0.5rem; color: var(--text-light);">
                            <li>• Tuition fees</li>
                            <li>• Lunch</li>
                            <li>• Learning materials</li>
                            <li>• Sports & activities</li>
                        </ul>
                    </div>
                </div>

                <!-- Boarding Students -->
                <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-top: 4px solid var(--accent-green);" data-aos="fade-up" data-aos-delay="200">
                    <h3 style="color: var(--accent-green); margin-bottom: 1.5rem; text-align: center;">Boarding Students</h3>
                    <div style="margin-bottom: 1.5rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>O-Level (S1-S4):</span>
                            <strong>UGX 670,000/term</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>A-Level (S5-S6):</span>
                            <strong>UGX 780,000/term</strong>
                        </div>
                    </div>
                    <div style="background: var(--background-light); padding: 1rem; border-radius: 5px;">
                        <strong style="color: var(--accent-green);">Includes:</strong>
                        <ul style="margin-top: 0.5rem; color: var(--text-light);">
                            <li>• All day student benefits</li>
                            <li>• Accommodation</li>
                            <li>• All meals</li>
                            <li>• Evening prep supervision</li>
                            <li>• Weekend activities</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div style="text-align: center; margin-top: 2rem;" data-aos="fade-up">
                <p style="color: var(--text-light); font-style: italic;">
                    * Additional fees may apply for specialized programs, school uniform, domestic wears,trips, and examinations
                </p>
            </div>
        </div>

        <!-- Scholarships & Financial Aid -->
        <div class="section">
            <div class="section-title" data-aos="fade-up">
                <h2>Scholarships & Financial Aid</h2>
                <p>Making quality education accessible to all deserving students</p>
            </div>

            <div class="features-grid">
                <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon" style="background: linear-gradient(135deg, var(--accent-yellow), #ffdd00);">
                        <i class="fas fa-medal"></i>
                    </div>
                    <h3>Academic Excellence Scholarship</h3>
                    <p>Full or half school fees reduction for students with outstanding academic performance. Renewable based on continued excellence.</p>
                </div>

                <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon" style="background: linear-gradient(135deg, var(--accent-green), #55bb55);">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Need-Based Assistance</h3>
                    <p>Financial support for deserving students from low-income families. Partial to full scholarships available based on demonstrated need.</p>
                </div>

                <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="icon" style="background: linear-gradient(135deg, var(--primary-blue), #3399ff);">
                        <i class="fas fa-running"></i>
                    </div>
                    <h3>Sports & Talent Scholarships</h3>
                    <p>Special scholarships for students with exceptional talent in sports, arts, music, or other co-curricular activities.</p>
                </div>
            </div>
        </div>

        <!-- Important Dates -->
        <div class="section bg-white" style="padding: 4rem 0;">
            <div class="section-title" data-aos="fade-up">
                <h2>Important Dates</h2>
                <p>Mark your calendar for key admission periods</p>
            </div>

            <div style="max-width: 800px; margin: 0 auto;">
                <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);" data-aos="fade-up">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                        <div>
                            <h4 style="color: var(--primary-blue); margin-bottom: 1rem;">Term 1 (January - April)</h4>
                            <p style="color: var(--text-light);">
                                <strong>Application Deadline:</strong> Early January<br>
                                <strong>Interviews:</strong> No Schedules!<br>
                                <strong>Reporting:</strong> January 15
                            </p>
                        </div>

                        <div>
                            <h4 style="color: var(--accent-green); margin-bottom: 1rem;">Term 2 (May - August)</h4>
                            <p style="color: var(--text-light);">
                                <strong>Application Deadline:</strong> May - August<br>
                                <strong>Interviews:</strong> No Schedules!<br>
                                <strong>Reporting:</strong> :
                            </p>
                        </div>

                        <div>
                            <h4 style="color: var(--accent-yellow); margin-bottom: 1rem;">Term 3 (September - December)</h4>
                            <p style="color: var(--text-light);">
                                <strong>Application Deadline:</strong> September 1<br>
                                <strong>Interviews:</strong> No Schedules!<br>
                                <strong>Reporting:</strong> :
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admission Inquiry Form -->
        <div class="section">
            <div class="section-title" data-aos="fade-up">
                <h2>Admission Inquiry</h2>
                <p>Have questions? Get in touch with our admissions team</p>
            </div>

            <?php if (!empty($success)): ?>
                <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 2rem; text-align: center;" data-aos="fade-up">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 2rem; text-align: center;" data-aos="fade-up">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div style="max-width: 800px; margin: 0 auto;">
                <form method="POST" action="" style="background: white; padding: 3rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);" data-aos="fade-up">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label for="name">Parent/Guardian Name *</label>
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
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone"
                                value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>"
                                placeholder="Enter your phone number">
                        </div>

                        <div class="form-group">
                            <label for="student_name">Student Name *</label>
                            <input type="text" id="student_name" name="student_name" required
                                value="<?php echo isset($student_name) ? htmlspecialchars($student_name) : ''; ?>"
                                placeholder="Enter student's full name">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label for="current_class">Current Class/Level</label>
                            <input type="text" id="current_class" name="current_class"
                                value="<?php echo isset($current_class) ? htmlspecialchars($current_class) : ''; ?>"
                                placeholder="e.g., P7, S2, Form 4">
                        </div>

                        <div class="form-group">
                            <label for="desired_class">Desired Class/Level *</label>
                            <select id="desired_class" name="desired_class" required>
                                <option value="">Select desired class</option>
                                <option value="S1" <?php echo (isset($desired_class) && $desired_class == 'S1') ? 'selected' : ''; ?>>S1 (Form 1)</option>
                                <option value="S2" <?php echo (isset($desired_class) && $desired_class == 'S2') ? 'selected' : ''; ?>>S2 (Form 2)</option>
                                <option value="S3" <?php echo (isset($desired_class) && $desired_class == 'S3') ? 'selected' : ''; ?>>S3 (Form 3)</option>
                                <option value="S4" <?php echo (isset($desired_class) && $desired_class == 'S4') ? 'selected' : ''; ?>>S4 (Form 4)</option>
                                <option value="S5" <?php echo (isset($desired_class) && $desired_class == 'S5') ? 'selected' : ''; ?>>S5 (Lower 6)</option>
                                <option value="S6" <?php echo (isset($desired_class) && $desired_class == 'S6') ? 'selected' : ''; ?>>S6 (Upper 6)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inquiry_type">Inquiry Type *</label>
                        <select id="inquiry_type" name="inquiry_type" required>
                            <option value="">Select inquiry type</option>
                            <option value="General Admission Information" <?php echo (isset($inquiry_type) && $inquiry_type == 'General Admission Information') ? 'selected' : ''; ?>>General Admission Information</option>
                            <option value="Application Requirements" <?php echo (isset($inquiry_type) && $inquiry_type == 'Application Requirements') ? 'selected' : ''; ?>>Application Requirements</option>
                            <option value="School Fees & Payment" <?php echo (isset($inquiry_type) && $inquiry_type == 'School Fees & Payment') ? 'selected' : ''; ?>>School Fees & Payment</option>
                            <option value="Scholarships & Financial Aid" <?php echo (isset($inquiry_type) && $inquiry_type == 'Scholarships & Financial Aid') ? 'selected' : ''; ?>>Scholarships & Financial Aid</option>
                            <option value="Boarding Facilities" <?php echo (isset($inquiry_type) && $inquiry_type == 'Boarding Facilities') ? 'selected' : ''; ?>>Boarding Facilities</option>
                            <option value="Academic Programs" <?php echo (isset($inquiry_type) && $inquiry_type == 'Academic Programs') ? 'selected' : ''; ?>>Academic Programs</option>
                            <option value="Transfer Student" <?php echo (isset($inquiry_type) && $inquiry_type == 'Transfer Student') ? 'selected' : ''; ?>>Transfer Student</option>
                            <option value="International Student" <?php echo (isset($inquiry_type) && $inquiry_type == 'International Student') ? 'selected' : ''; ?>>International Student</option>
                            <option value="School Visit/Tour" <?php echo (isset($inquiry_type) && $inquiry_type == 'School Visit/Tour') ? 'selected' : ''; ?>>School Visit/Tour</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message">Additional Information</label>
                        <textarea id="message" name="message" rows="5"
                            placeholder="Please provide any additional details about your inquiry..."><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                    </div>

                    <button type="submit" name="submit_inquiry" class="btn" style="width: 100%;">
                        <i class="fas fa-paper-plane"></i> Submit Inquiry
                    </button>
                </form>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="section bg-white" style="padding: 4rem 0;">
            <div class="section-title" data-aos="fade-up">
                <h2>Admissions Office Contact</h2>
                <p>Reach out to our admissions team for personalized assistance</p>
            </div>

            <div class="contact-grid" style="max-width: 800px; margin: 0 auto;">
                <div data-aos="fade-right">
                    <div class="contact-item">
                        <div class="icon" style="background: var(--primary-blue);">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div>
                            <strong>Admissions Officer</strong><br>
                            Mrs Baguma Anita<br>
                            <a href="mailto:admissions@makererecompetenthighschool.com" style="color: var(--primary-blue);">admissions@makererecompetenthighschool.com</a>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="icon" style="background: var(--accent-green);">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <strong>Direct Line</strong><br>
                            <a href="tel:+256704 292872" style="color: var(--primary-blue);">+256 704 292872</a><br>
                            <small style="color: var(--text-light);">Monday - Friday: 8:00 AM - 5:00 PM</small>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="icon" style="background: var(--accent-yellow); color: var(--primary-blue);">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <strong>Office Hours</strong><br>
                            Monday - Friday: 8:00 AM - 5:00 PM<br>
                            Saturday: 8:00 AM - 1:00 PM<br>
                            <small style="color: var(--text-light);">Walk-ins welcome, appointments preferred</small>
                        </div>
                    </div>
                </div>

                <div data-aos="fade-left">
                    <div style="background: var(--background-light); padding: 2rem; border-radius: 10px;">
                        <h4 style="color: var(--primary-blue); margin-bottom: 1rem;">Quick Tips</h4>
                        <ul style="color: var(--text-light); line-height: 1.8;">
                            <li>Apply early to secure your preferred admission date</li>
                            <li>Visit our school for a tour before applying</li>
                            <li>Prepare all required documents in advance</li>
                            <li>Contact us for scholarship application guidance</li>
                            <li>Attend our open days for comprehensive information</li>
                        </ul>

                        <div style="margin-top: 1.5rem; text-align: center;">
                            <a href="contact.php" class="btn" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-calendar"></i> Schedule a Visit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>