<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = "MACOSA - Makerere Competent High School";
$meta_description = "Connect with Makerere Competent High School MACOSA community. Discover success stories, networking opportunities, events, and ways to give back to your alma mater.";

// Handle MACOSA registration form submission
$registration_success = '';
$registration_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_MACOSA'])) {
    $full_name = sanitizeInput($_POST['full_name']);
    $maiden_name = sanitizeInput($_POST['maiden_name']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $graduation_year = sanitizeInput($_POST['graduation_year']);
    $class_level = sanitizeInput($_POST['class_level']);
    $current_profession = sanitizeInput($_POST['current_profession']);
    $company = sanitizeInput($_POST['company']);
    $location = sanitizeInput($_POST['location']);
    $achievements = sanitizeInput($_POST['achievements']);
    $willing_to_mentor = isset($_POST['willing_to_mentor']) ? 1 : 0;
    $willing_to_speak = isset($_POST['willing_to_speak']) ? 1 : 0;
    $willing_to_donate = isset($_POST['willing_to_donate']) ? 1 : 0;
    
    if (empty($full_name) || empty($email) || empty($graduation_year) || empty($class_level)) {
        $registration_error = "Please fill in all required fields.";
    } elseif (!isValidEmail($email)) {
        $registration_error = "Please enter a valid email address.";
    } else {
        try {
            // Create MACOSA table if it doesn't exist
            $pdo->exec("CREATE TABLE IF NOT EXISTS MACOSA (
                id INT AUTO_INCREMENT PRIMARY KEY,
                full_name VARCHAR(255) NOT NULL,
                maiden_name VARCHAR(255),
                email VARCHAR(255) UNIQUE NOT NULL,
                phone VARCHAR(20),
                graduation_year YEAR NOT NULL,
                class_level VARCHAR(10) NOT NULL,
                current_profession VARCHAR(255),
                company VARCHAR(255),
                location VARCHAR(255),
                achievements TEXT,
                willing_to_mentor BOOLEAN DEFAULT 0,
                willing_to_speak BOOLEAN DEFAULT 0,
                willing_to_donate BOOLEAN DEFAULT 0,
                status ENUM('pending', 'approved') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
            
            $stmt = $pdo->prepare("INSERT INTO MACOSA (full_name, maiden_name, email, phone, graduation_year, class_level, current_profession, company, location, achievements, willing_to_mentor, willing_to_speak, willing_to_donate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            if ($stmt->execute([$full_name, $maiden_name, $email, $phone, $graduation_year, $class_level, $current_profession, $company, $location, $achievements, $willing_to_mentor, $willing_to_speak, $willing_to_donate])) {
                $registration_success = "Your MACOSA registration has been submitted successfully! We'll review and approve your profile within 48 hours.";
                
                // Send email notification
                $to = SITE_EMAIL;
                $subject = "New MACOSA Registration - " . $full_name;
                $email_message = "New MACOSA registration received:\n\n";
                $email_message .= "Name: $full_name\n";
                $email_message .= "Email: $email\n";
                $email_message .= "Graduation Year: $graduation_year\n";
                $email_message .= "Class Level: $class_level\n";
                $email_message .= "Current Profession: $current_profession\n";
                $email_message .= "Company: $company\n";
                $email_message .= "Location: $location\n\n";
                $email_message .= "Willing to mentor: " . ($willing_to_mentor ? 'Yes' : 'No') . "\n";
                $email_message .= "Willing to speak: " . ($willing_to_speak ? 'Yes' : 'No') . "\n";
                $email_message .= "Willing to donate: " . ($willing_to_donate ? 'Yes' : 'No') . "\n\n";
                $email_message .= "Achievements:\n$achievements";
                
                $headers = "From: " . $email . "\r\n";
                $headers .= "Reply-To: " . $email . "\r\n";
                
                mail($to, $subject, $email_message, $headers);
                
                // Clear form data
                unset($full_name, $maiden_name, $email, $phone, $graduation_year, $class_level, $current_profession, $company, $location, $achievements);
            } else {
                $registration_error = "Sorry, there was an error submitting your registration. Please try again.";
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $registration_error = "This email address is already registered. Please use a different email or contact us for assistance.";
            } else {
                $registration_error = "Sorry, there was an error submitting your registration. Please try again.";
            }
        }
    }
}

// Get featured alumni for success stories
try {
    $stmt = $pdo->prepare("SELECT * FROM alumni WHERE status = 'approved' AND achievements IS NOT NULL AND achievements != '' ORDER BY RAND() LIMIT 6");
    $stmt->execute();
    $featured_alumni = $stmt->fetchAll();
} catch (PDOException $e) {
    $featured_alumni = [];
}

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="hero" style="height: 60vh; background: linear-gradient(rgba(77, 166, 255, 0.9), rgba(51, 153, 255, 0.9)), url('assets/images/MACOSA-bg.jpg');">
    <div class="container">
        <div class="hero-content">
            <h2 data-aos="fade-up" style="font-size: 3rem;">Welcome Back, MACOSA!</h2>
            <p data-aos="fade-up" data-aos-delay="200" style="font-size: 1.2rem;">
                Once a Competent student, always part of our family. Connect, inspire, and make a difference.
            </p>
            <div data-aos="fade-up" data-aos-delay="400">
                <a href="#register" class="btn">Join MACOSA Network</a>
                <a href="#success-stories" class="btn-secondary">Read Success Stories</a>
            </div>
        </div>
    </div>
</section>


<!-- MACOSA Stats -->
<section class="stats section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
                <h3 class="counter" data-target="5000">5000+</h3>
                <p>Proud MACOSA Members</p>
            </div>
            <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
                <h3 class="counter" data-target="25">25+</h3>
                <p>Countries Worldwide</p>
            </div>
            <div class="stat-item" data-aos="fade-up" data-aos-delay="300">
                <h3 class="counter" data-target="30">30+</h3>
                <p>Professional Fields</p>
            </div>
            <div class="stat-item" data-aos="fade-up" data-aos-delay="400">
                <h3 class="counter" data-target="8">8</h3>
                <p>Years of Excellence</p>
            </div>
        </div>
    </div>
</section>

<!-- About MACOSA Network -->
<section class="section bg-white">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Our Global MACOSA Community</h2>
            <p>Connecting generations of achievers across the world</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 3rem; margin-top: 3rem; align-items: center;">
            <div data-aos="fade-right">
                <h3 style="color: var(--primary-blue); margin-bottom: 1.5rem; font-size: 1.8rem;">Building Bridges, Creating Opportunities</h3>
                <p style="color: var(--text-light); line-height: 1.8; margin-bottom: 1.5rem;">
                    Founded in 2017 by dedicated alumni including Emmanuel, Christopher, Lot, Julius, Opio & Tyson, 
                    MACOSA has evolved to become a thriving community of over 5,000 old students who share a common bond 
                    and passion for their alma mater. Our diverse membership spans various backgrounds and professions 
                    across different continents.
                </p>
                <p style="color: var(--text-light); line-height: 1.8; margin-bottom: 2rem;">
                    We are committed to upholding the values of our alma mater, promoting lifelong learning, 
                    and fostering a sense of belonging among our members. Whether you're looking to network, 
                    mentor current students, or give back to your alma mater, our MACOSA community provides 
                    endless opportunities for meaningful connections and impact.
                </p>
                <a href="#benefits" class="btn">Discover Benefits</a>
            </div>
            
            <div data-aos="fade-left">
                <div style="background: var(--background-light); padding: 2rem; border-radius: 15px;">
                    <h4 style="color: var(--primary-blue); margin-bottom: 1rem;">Our Mission & Vision</h4>
                    <div style="margin-bottom: 1.5rem;">
                        <h5 style="color: var(--accent-green); margin-bottom: 0.5rem;">Mission</h5>
                        <p style="color: var(--text-light); font-size: 0.9rem; line-height: 1.6;">
                            To foster a lifelong connection among alumni, promote the interests of our alma mater, 
                            and support the personal and professional growth of our members.
                        </p>
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <h5 style="color: var(--accent-green); margin-bottom: 0.5rem;">Vision</h5>
                        <p style="color: var(--text-light); font-size: 0.9rem; line-height: 1.6;">
                            To be the premier old students association, recognized for our commitment to excellence, 
                            our passion for community, and our dedication to supporting the next generation of leaders.
                        </p>
                    </div>
                    <div>
                        <h5 style="color: var(--accent-green); margin-bottom: 0.5rem;">Core Values</h5>
                        <ul style="color: var(--text-light); font-size: 0.9rem; line-height: 1.6; list-style: none; padding: 0;">
                            <li>✓ Community & Excellence</li>
                            <li>✓ Inclusivity & Service</li>
                            <li>✓ Integrity & Respect</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Success Stories -->
<section id="success-stories" class="section">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Inspiring Success Stories</h2>
            <p>Celebrating the achievements of our remarkable Alumni</p>
        </div>

        <?php if (!empty($featured_alumni)): ?>
            <style>
                @media (min-width: 1200px) {
                    .stories-grid { grid-template-columns: repeat(2, 1fr) !important; }
                }
                @media (min-width: 768px) and (max-width: 1199px) {
                    .stories-grid { grid-template-columns: repeat(2, 1fr) !important; }
                }
                .news-card {
                    background: white;
                    border-radius: 20px;
                    overflow: hidden;
                    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                    border: 1px solid rgba(0,0,0,0.05);
                }
                .news-card:hover {
                    transform: translateY(-8px);
                    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
                }
                .news-card-content {
                    padding: 1.5rem;
                }
                .news-card-content h3 {
                    color: var(--primary-blue);
                    margin-bottom: 0.5rem;
                    font-size: 1.3rem;
                }
                .news-date {
                    font-size: 0.9rem;
                    margin-bottom: 1rem;
                    font-weight: 500;
                }
            </style>
            <div class="stories-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem; margin-top: 3rem;">
                <?php foreach (array_slice($featured_alumni, 0, 4) as $index => $alumni): ?>
                    <div class="news-card" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                        <div style="background: linear-gradient(135deg, var(--primary-blue), var(--accent-green)); height: 200px; display: flex; align-items: center; justify-content: center; position: relative;">
                            <div style="width: 120px; height: 120px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: var(--primary-blue); font-weight: bold; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                                <?php echo strtoupper(substr($alumni['full_name'], 0, 1)); ?>
                            </div>
                            <div style="position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.9); padding: 5px 10px; border-radius: 15px; font-size: 0.8rem; color: var(--primary-blue); font-weight: bold;">
                                Class of <?php echo $alumni['graduation_year']; ?>
                            </div>
                        </div>
                        <div class="news-card-content">
                            <h3><?php echo htmlspecialchars($alumni['full_name']); ?></h3>
                            <div class="news-date" style="color: var(--accent-green);">
                                <?php echo htmlspecialchars($alumni['current_profession']); ?>
                            </div>
                            <?php if (!empty($alumni['company'])): ?>
                                <p style="font-weight: bold; color: var(--primary-blue); margin-bottom: 0.5rem; font-size: 0.95rem;">
                                    <i class="fas fa-building" style="margin-right: 0.3rem;"></i><?php echo htmlspecialchars($alumni['company']); ?>
                                </p>
                            <?php endif; ?>
                            <p style="line-height: 1.6; color: var(--text-light);"><?php echo htmlspecialchars(truncateText($alumni['achievements'], 120)); ?></p>
                            <?php if (!empty($alumni['location'])): ?>
                                <div style="margin-top: 1rem; padding: 0.5rem; background: var(--background-light); border-radius: 8px;">
                                    <small style="color: var(--text-light); display: flex; align-items: center;">
                                        <i class="fas fa-map-marker-alt" style="color: var(--accent-green); margin-right: 0.5rem;"></i> 
                                        <?php echo htmlspecialchars($alumni['location']); ?>
                                    </small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- Default success stories when no alumni data is available -->
            <style>
                @media (min-width: 1200px) {
                    .stories-grid { grid-template-columns: repeat(2, 1fr) !important; }
                }
                @media (min-width: 768px) and (max-width: 1199px) {
                    .stories-grid { grid-template-columns: repeat(2, 1fr) !important; }
                }
                .news-card {
                    background: white;
                    border-radius: 20px;
                    overflow: hidden;
                    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                    border: 1px solid rgba(0,0,0,0.05);
                }
                .news-card:hover {
                    transform: translateY(-8px);
                    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
                }
                .news-card-content {
                    padding: 1.5rem;
                }
                .news-card-content h3 {
                    color: var(--primary-blue);
                    margin-bottom: 0.5rem;
                    font-size: 1.3rem;
                }
                .news-date {
                    font-size: 0.9rem;
                    margin-bottom: 1rem;
                    font-weight: 500;
                }
            </style>
            <div class="stories-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem; margin-top: 3rem;">
                <div class="news-card" data-aos="fade-up" data-aos-delay="100">
                    <div style="background: linear-gradient(135deg, var(--primary-blue), var(--accent-green)); height: 200px; display: flex; align-items: center; justify-content: center; position: relative;">
                        <div style="width: 120px; height: 120px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: var(--primary-blue); font-weight: bold; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">E</div>
                        <div style="position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.9); padding: 5px 10px; border-radius: 15px; font-size: 0.8rem; color: var(--primary-blue); font-weight: bold;">
                            Founder
                        </div>
                    </div>
                    <div class="news-card-content">
                        <h3>Emmanuel Nyesigomu</h3>
                        <div class="news-date" style="color: var(--accent-green);">Senior Accounting Officer</div>
                        <p style="font-weight: bold; color: var(--primary-blue); margin-bottom: 0.5rem; font-size: 0.95rem;">
                            <i class="fas fa-building" style="margin-right: 0.3rem;"></i>Mulago National Referral Hospital
                        </p>
                        <p style="line-height: 1.6; color: var(--text-light);">One of MACOSA's founding members, leading Uganda's healthcare transformation through ethical accounting practices and mentoring youth across East Africa.</p>
                        <div style="margin-top: 1rem; padding: 0.5rem; background: var(--background-light); border-radius: 8px;">
                            <small style="color: var(--text-light); display: flex; align-items: center;">
                                <i class="fas fa-map-marker-alt" style="color: var(--accent-green); margin-right: 0.5rem;"></i> 
                                Kampala, Uganda
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="news-card" data-aos="fade-up" data-aos-delay="200">
                    <div style="background: linear-gradient(135deg, var(--accent-yellow), var(--accent-green)); height: 200px; display: flex; align-items: center; justify-content: center; position: relative;">
                        <div style="width: 120px; height: 120px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: var(--primary-blue); font-weight: bold; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">J</div>
                        <div style="position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.9); padding: 5px 10px; border-radius: 15px; font-size: 0.8rem; color: var(--primary-blue); font-weight: bold;">
                            Head Girl 2024-25
                        </div>
                    </div>
                    <div class="news-card-content">
                        <h3>Komuhendo Jackline</h3>
                        <div class="news-date" style="color: var(--accent-green);">Head Girl 2024-2025 • Student Leader</div>
                        <p style="font-weight: bold; color: var(--primary-blue); margin-bottom: 0.5rem; font-size: 0.95rem;">
                            <i class="fas fa-graduation-cap" style="margin-right: 0.3rem;"></i>GEA/SM Program
                        </p>
                        <p style="line-height: 1.6; color: var(--text-light);">"MY SCHOOL MY PRIDE where a flag of debate is risen, and all students talents are recognized as well as leadership. Makerere Competent made me what I am."</p>
                        <div style="margin-top: 1rem; padding: 0.5rem; background: var(--background-light); border-radius: 8px;">
                            <small style="color: var(--text-light); display: flex; align-items: center;">
                                <i class="fas fa-map-marker-alt" style="color: var(--accent-green); margin-right: 0.5rem;"></i> 
                                Uganda
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 3rem;" data-aos="fade-up">
            <a href="#register" class="btn">Share Your Story</a>
        </div>
    </div>
</section>

<!-- MACOSA Benefits -->
<section id="benefits" class="section bg-white">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>MACOSA Network Benefits</h2>
            <p>Exclusive perks and opportunities for our valued MACOSA</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
            <!-- Grid adapts: 4 cards on xl screens, 3 on lg, 2 on md, 1 on sm -->
            <style>
                @media (min-width: 1400px) {
                    .benefits-grid { grid-template-columns: repeat(4, 1fr) !important; }
                }
                @media (min-width: 1000px) and (max-width: 1399px) {
                    .benefits-grid { grid-template-columns: repeat(3, 1fr) !important; }
                }
                @media (min-width: 700px) and (max-width: 999px) {
                    .benefits-grid { grid-template-columns: repeat(2, 1fr) !important; }
                }
                .feature-card {
                    background: white;
                    padding: 2rem;
                    border-radius: 15px;
                    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
                    text-align: center;
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                    border-top: 3px solid transparent;
                }
                .feature-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
                }
                .feature-card .icon {
                    width: 80px;
                    height: 80px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 1.5rem;
                    font-size: 2rem;
                    color: white;
                }
                .feature-card h3 {
                    color: var(--primary-blue);
                    margin-bottom: 1rem;
                    font-size: 1.3rem;
                }
                .feature-card p {
                    color: var(--text-light);
                    line-height: 1.6;
                    margin: 0;
                }
            </style>
            <div class="benefits-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
                <div class="feature-card" data-aos="fade-up" data-aos-delay="100" style="border-top-color: var(--primary-blue);">
                    <div class="icon" style="background: linear-gradient(135deg, var(--primary-blue), #3399ff);">
                        <i class="fas fa-network-wired"></i>
                    </div>
                    <h3>Global Networking</h3>
                    <p>Connect with fellow alumni worldwide through our exclusive online platform, regional chapters, and international events across 25+ countries.</p>
                </div>

                <div class="feature-card" data-aos="fade-up" data-aos-delay="200" style="border-top-color: var(--accent-green);">
                    <div class="icon" style="background: linear-gradient(135deg, var(--accent-green), #55bb55);">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h3>Career Development</h3>
                    <p>Access exclusive job postings, career mentorship programs, professional development workshops, and industry insights from our 5000+ member network.</p>
                </div>

                <div class="feature-card" data-aos="fade-up" data-aos-delay="300" style="border-top-color: var(--accent-yellow);">
                    <div class="icon" style="background: linear-gradient(135deg, var(--accent-yellow), #ffdd00);">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Mentorship Programs</h3>
                    <p>Mentor current students through our structured programs or connect with senior alumni for guidance in your professional and personal growth journey.</p>
                </div>

                <div class="feature-card" data-aos="fade-up" data-aos-delay="400" style="border-top-color: var(--primary-blue);">
                    <div class="icon" style="background: linear-gradient(135deg, var(--primary-blue), var(--accent-green));">
                        <i class="fas fa-piggy-bank"></i>
                    </div>
                    <h3>MACOSA SACCO</h3>
                    <p>Access financial services through our SACCO project: savings mobilization, competitive credit facilities, investment opportunities, and financial empowerment.</p>
                </div>

                <div class="feature-card" data-aos="fade-up" data-aos-delay="500" style="border-top-color: var(--accent-green);">
                    <div class="icon" style="background: linear-gradient(135deg, var(--accent-green), var(--accent-yellow));">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3>Exclusive Events</h3>
                    <p>Attend annual reunions, career fairs, professional workshops, alumni awards ceremonies, and networking sessions across different regions.</p>
                </div>

                <div class="feature-card" data-aos="fade-up" data-aos-delay="600" style="border-top-color: var(--accent-yellow);">
                    <div class="icon" style="background: linear-gradient(135deg, var(--accent-yellow), var(--primary-blue));">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Community Impact</h3>
                    <p>Participate in school improvement projects, scholarship programs, infrastructure development, and community service initiatives that benefit current students.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MACOSA SACCO Section -->
<section class="section">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>MACOSA SACCO - Financial Empowerment</h2>
            <p>Building wealth together through our Savings & Credit Cooperative</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 3rem; margin-top: 3rem; align-items: center;">
            <div data-aos="fade-right">
                <div style="background: linear-gradient(135deg, var(--primary-blue), var(--accent-green)); color: white; padding: 3rem; border-radius: 20px; text-align: center;">
                    <i class="fas fa-university" style="font-size: 4rem; margin-bottom: 1.5rem; opacity: 0.9;"></i>
                    <h3 style="margin-bottom: 1rem; font-size: 1.8rem;">Join MACOSA SACCO Today</h3>
                    <p style="opacity: 0.9; line-height: 1.6; margin-bottom: 2rem;">
                        Be part of our growing financial cooperative that's empowering alumni through 
                        savings, credit, and investment opportunities.
                    </p>
                    <div style="background: rgba(255,255,255,0.1); padding: 1.5rem; border-radius: 10px; margin-bottom: 2rem;">
                        <h4 style="margin-bottom: 1rem;">Quick Stats</h4>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; text-align: center;">
                            <div>
                                <h3 style="margin: 0; font-size: 1.5rem;">28+</h3>
                                <small style="opacity: 0.8;">Active Members</small>
                            </div>
                            <div>
                                <h3 style="margin: 0; font-size: 1.5rem;">UGX 50M+</h3>
                                <small style="opacity: 0.8;">Total Savings</small>
                            </div>
                            <div>
                                <h3 style="margin: 0; font-size: 1.5rem;">15+</h3>
                                <small style="opacity: 0.8;">Loans Disbursed</small>
                            </div>
                            <div>
                                <h3 style="margin: 0; font-size: 1.5rem;">12%</h3>
                                <small style="opacity: 0.8;">Annual Interest</small>
                            </div>
                        </div>
                    </div>
                    <a href="contact.php" style="background: white; color: var(--primary-blue); padding: 15px 30px; border-radius: 50px; text-decoration: none; font-weight: bold; display: inline-block;">
                        <i class="fas fa-handshake"></i> Join SACCO
                    </a>
                </div>
            </div>
            
            <div data-aos="fade-left">
                <h3 style="color: var(--primary-blue); margin-bottom: 2rem; font-size: 2rem;">SACCO Services & Benefits</h3>
                
                <div style="display: grid; gap: 1.5rem;">
                    <div style="background: white; padding: 1.5rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-left: 5px solid var(--accent-green);">
                        <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                            <div style="background: var(--accent-green); color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                                <i class="fas fa-piggy-bank"></i>
                            </div>
                            <h4 style="margin: 0; color: var(--primary-blue);">Savings Mobilization</h4>
                        </div>
                        <p style="color: var(--text-light); line-height: 1.6; margin: 0;">
                            Flexible savings plans with competitive interest rates. Build your financial future with regular deposits and watch your money grow.
                        </p>
                    </div>
                    
                    <div style="background: white; padding: 1.5rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-left: 5px solid var(--primary-blue);">
                        <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                            <div style="background: var(--primary-blue); color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                                <i class="fas fa-hand-holding-usd"></i>
                            </div>
                            <h4 style="margin: 0; color: var(--primary-blue);">Credit Facilities</h4>
                        </div>
                        <p style="color: var(--text-light); line-height: 1.6; margin: 0;">
                            Access affordable loans for education, business, emergency needs, and investment opportunities with member-friendly terms.
                        </p>
                    </div>
                    
                    <div style="background: white; padding: 1.5rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-left: 5px solid var(--accent-yellow);">
                        <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                            <div style="background: var(--accent-yellow); color: var(--primary-blue); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h4 style="margin: 0; color: var(--primary-blue);">Investment Opportunities</h4>
                        </div>
                        <p style="color: var(--text-light); line-height: 1.6; margin: 0;">
                            Participate in group investments, share dividends, and benefit from SACCO's profitable ventures and partnerships.
                        </p>
                    </div>
                    
                    <div style="background: white; padding: 1.5rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-left: 5px solid var(--accent-green);">
                        <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                            <div style="background: var(--accent-green); color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h4 style="margin: 0; color: var(--primary-blue);">Financial Education</h4>
                        </div>
                        <p style="color: var(--text-light); line-height: 1.6; margin: 0;">
                            Monthly workshops on financial literacy, investment strategies, business planning, and wealth management for all members.
                        </p>
                    </div>
                </div>
                
                <div style="margin-top: 2rem; text-align: center;">
                    <p style="color: var(--text-light); font-style: italic; margin-bottom: 1rem;">
                        "Together we save, together we grow, together we prosper"
                    </p>
                    <a href="contact.php" style="background: var(--accent-green); color: white; padding: 12px 25px; border-radius: 25px; text-decoration: none; font-weight: bold;">
                        <i class="fas fa-info-circle"></i> Learn More About SACCO
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MACOSA Activities & Events -->
<section class="section">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Upcoming Events & Activities</h2>
            <p>MACOSA Retreat 2025 & Annual Reunion</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem; margin-top: 3rem;">
            <!-- Annual Reunion -->
            <div style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-top: 5px solid var(--primary-blue);" data-aos="fade-up" data-aos-delay="100">
                <div style="background: linear-gradient(135deg, var(--primary-blue), #3399ff); color: white; padding: 1.5rem; text-align: center;">
                    <i class="fas fa-users" style="font-size: 2.5rem; margin-bottom: 1rem;"></i>
                    <h3>Annual MACOSA Reunion</h3>
                    <p style="opacity: 0.9;">August 15, 2025</p>
                </div>
                <div style="padding: 1.5rem;">
                    <p style="color: var(--text-light); line-height: 1.6; margin-bottom: 1rem;">
                        Join us for our biggest celebration of the year! Reconnect with classmates, tour the school, 
                        enjoy entertainment, and celebrate our collective achievements. Following the success of our 2024 retreat.
                    </p>
                    <ul style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 1.5rem;">
                        <li>✓ School tours and facility showcases</li>
                        <li>✓ Class photo sessions by year</li>
                        <li>✓ MACOSA achievement awards ceremony</li>
                        <li>✓ Cultural performances and entertainment</li>
                        <li>✓ Networking dinner & alumni recognition</li>
                        <li>✓ Football and Volleyball gala matches</li>
                    </ul>
                    <a href="#register" class="btn" style="width: 100%; justify-content: center;">Register Now</a>
                </div>
            </div>

            <!-- Professional Development -->
            <div style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-top: 5px solid var(--accent-green);" data-aos="fade-up" data-aos-delay="200">
                <div style="background: linear-gradient(135deg, var(--accent-green), #55bb55); color: white; padding: 1.5rem; text-align: center;">
                    <i class="fas fa-chart-line" style="font-size: 2.5rem; margin-bottom: 1rem;"></i>
                    <h3>Leadership Workshop Series</h3>
                    <p style="opacity: 0.9;">Monthly Sessions</p>
                </div>
                <div style="padding: 1.5rem;">
                    <p style="color: var(--text-light); line-height: 1.6; margin-bottom: 1rem;">
                        Enhance your leadership skills through our monthly workshop series featuring expert facilitators 
                        and successful MACOSA sharing their experiences.
                    </p>
                    <ul style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 1.5rem;">
                        <li>✓ Executive leadership strategies</li>
                        <li>✓ Entrepreneurship and innovation workshops</li>
                        <li>✓ Digital transformation in business</li>
                        <li>✓ Financial planning and SACCO management</li>
                        <li>✓ Work-life balance and wellness</li>
                    </ul>
                    <a href="contact.php" class="btn" style="width: 100%; justify-content: center; background: var(--accent-green);">Learn More</a>
                </div>
            </div>

            <!-- Mentorship Program -->
            <div style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-top: 5px solid var(--accent-yellow);" data-aos="fade-up" data-aos-delay="300">
                <div style="background: linear-gradient(135deg, var(--accent-yellow), #ffdd00); color: var(--primary-blue); padding: 1.5rem; text-align: center;">
                    <i class="fas fa-handshake" style="font-size: 2.5rem; margin-bottom: 1rem;"></i>
                    <h3>MACOSA Mentorship</h3>
                    <p style="opacity: 0.8;">Ongoing Program</p>
                </div>
                <div style="padding: 1.5rem;">
                    <p style="color: var(--text-light); line-height: 1.6; margin-bottom: 1rem;">
                        Make a lasting impact by mentoring current students or benefit from guidance by connecting 
                        with senior MACOSA in your field of interest.
                    </p>
                    <ul style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 1.5rem;">
                        <li>✓ Career guidance and professional advice</li>
                        <li>✓ Academic support and tutoring programs</li>
                        <li>✓ Industry insights and networking opportunities</li>
                        <li>✓ Personal development and leadership coaching</li>
                        <li>✓ Interview preparation and job search assistance</li>
                    </ul>
                    <a href="#register" class="btn" style="width: 100%; justify-content: center; background: var(--accent-yellow); color: var(--primary-blue);">Join Program</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MACOSA Registration -->
<section id="register" class="section bg-white">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Join Our MACOSA Network</h2>
            <p>Register today and become part of our global community</p>
        </div>
        
        <?php if (!empty($registration_success)): ?>
            <div style="background: #d4edda; color: #155724; padding: 20px; border-radius: 10px; margin-bottom: 2rem; text-align: center; max-width: 800px; margin-left: auto; margin-right: auto;" data-aos="fade-up">
                <i class="fas fa-check-circle" style="font-size: 1.5rem; margin-right: 0.5rem;"></i> 
                <?php echo $registration_success; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($registration_error)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 20px; border-radius: 10px; margin-bottom: 2rem; text-align: center; max-width: 800px; margin-left: auto; margin-right: auto;" data-aos="fade-up">
                <i class="fas fa-exclamation-triangle" style="font-size: 1.5rem; margin-right: 0.5rem;"></i> 
                <?php echo $registration_error; ?>
            </div>
        <?php endif; ?>

        <div style="max-width: 900px; margin: 0 auto;">
            <form method="POST" action="" style="background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);" data-aos="fade-up">
                <h3 style="color: var(--primary-blue); margin-bottom: 2rem; text-align: center;">MACOSA Registration Form</h3>
                
                <!-- Personal Information -->
                <div style="margin-bottom: 2.5rem;">
                    <h4 style="color: var(--accent-green); margin-bottom: 1.5rem; border-bottom: 2px solid var(--background-light); padding-bottom: 0.5rem;">
                        <i class="fas fa-user"></i> Personal Information
                    </h4>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                        <div class="form-group">
                            <label for="full_name">Full Name *</label>
                            <input type="text" id="full_name" name="full_name" required 
                                   value="<?php echo isset($full_name) ? htmlspecialchars($full_name) : ''; ?>"
                                   placeholder="Enter your full name">
                        </div>
                        
                        <div class="form-group">
                            <label for="maiden_name">Maiden Name (if applicable)</label>
                            <input type="text" id="maiden_name" name="maiden_name" 
                                   value="<?php echo isset($maiden_name) ? htmlspecialchars($maiden_name) : ''; ?>"
                                   placeholder="Enter your maiden name">
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-top: 1.5rem;">
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
                    </div>
                </div>

                <!-- Academic Information -->
                <div style="margin-bottom: 2.5rem;">
                    <h4 style="color: var(--accent-green); margin-bottom: 1.5rem; border-bottom: 2px solid var(--background-light); padding-bottom: 0.5rem;">
                        <i class="fas fa-graduation-cap"></i> Academic Information
                    </h4>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                        <div class="form-group">
                            <label for="graduation_year">Graduation Year *</label>
                            <select id="graduation_year" name="graduation_year" required>
                                <option value="">Select graduation year</option>
                                <?php for ($year = date('Y'); $year >= 1990; $year--): ?>
                                    <option value="<?php echo $year; ?>" <?php echo (isset($graduation_year) && $graduation_year == $year) ? 'selected' : ''; ?>>
                                        <?php echo $year; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="class_level">Highest Class Completed *</label>
                            <select id="class_level" name="class_level" required>
                                <option value="">Select class level</option>
                                <option value="S4" <?php echo (isset($class_level) && $class_level == 'S4') ? 'selected' : ''; ?>>S4 (O-Level)</option>
                                <option value="S6" <?php echo (isset($class_level) && $class_level == 'S6') ? 'selected' : ''; ?>>S6 (A-Level)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Professional Information -->
                <div style="margin-bottom: 2.5rem;">
                    <h4 style="color: var(--accent-green); margin-bottom: 1.5rem; border-bottom: 2px solid var(--background-light); padding-bottom: 0.5rem;">
                        <i class="fas fa-briefcase"></i> Professional Information
                    </h4>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                        <div class="form-group">
                            <label for="current_profession">Current Profession</label>
                            <input type="text" id="current_profession" name="current_profession" 
                                   value="<?php echo isset($current_profession) ? htmlspecialchars($current_profession) : ''; ?>"
                                   placeholder="e.g., Software Engineer, Doctor, Teacher">
                        </div>
                        
                        <div class="form-group">
                            <label for="company">Company/Organization</label>
                            <input type="text" id="company" name="company" 
                                   value="<?php echo isset($company) ? htmlspecialchars($company) : ''; ?>"
                                   placeholder="Enter your current workplace">
                        </div>
                    </div>
                    
                    <div class="form-group" style="margin-top: 1.5rem;">
                        <label for="location">Current Location</label>
                        <input type="text" id="location" name="location" 
                               value="<?php echo isset($location) ? htmlspecialchars($location) : ''; ?>"
                               placeholder="e.g., Kampala, Uganda or New York, USA">
                    </div>
                </div>

                <!-- Achievements & Involvement -->
                <div style="margin-bottom: 2.5rem;">
                    <h4 style="color: var(--accent-green); margin-bottom: 1.5rem; border-bottom: 2px solid var(--background-light); padding-bottom: 0.5rem;">
                        <i class="fas fa-trophy"></i> Achievements & Involvement
                    </h4>
                    
                    <div class="form-group">
                        <label for="achievements">Professional Achievements & Awards</label>
                        <textarea id="achievements" name="achievements" rows="4" 
                                  placeholder="Share your notable achievements, awards, publications, or significant contributions to your field..."><?php echo isset($achievements) ? htmlspecialchars($achievements) : ''; ?></textarea>
                    </div>
                    
                    <div style="margin-top: 1.5rem;">
                        <label style="display: block; margin-bottom: 1rem; color: var(--primary-blue); font-weight: 500;">
                            How would you like to contribute to the MACOSA network?
                        </label>
                        
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                            <label style="display: flex; align-items: center; padding: 1rem; background: var(--background-light); border-radius: 8px; cursor: pointer; transition: background 0.3s;">
                                <input type="checkbox" name="willing_to_mentor" value="1" <?php echo (isset($willing_to_mentor) && $willing_to_mentor) ? 'checked' : ''; ?> style="margin-right: 0.5rem;">
                                <span>Mentor Students</span>
                            </label>
                            
                            <label style="display: flex; align-items: center; padding: 1rem; background: var(--background-light); border-radius: 8px; cursor: pointer; transition: background 0.3s;">
                                <input type="checkbox" name="willing_to_speak" value="1" <?php echo (isset($willing_to_speak) && $willing_to_speak) ? 'checked' : ''; ?> style="margin-right: 0.5rem;">
                                <span>Guest Speaking</span>
                            </label>
                            
                            <label style="display: flex; align-items: center; padding: 1rem; background: var(--background-light); border-radius: 8px; cursor: pointer; transition: background 0.3s;">
                                <input type="checkbox" name="willing_to_donate" value="1" <?php echo (isset($willing_to_donate) && $willing_to_donate) ? 'checked' : ''; ?> style="margin-right: 0.5rem;">
                                <span>Financial Support</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div style="text-align: center;">
                    <button type="submit" name="register_MACOSA" class="btn" style="padding: 15px 40px; font-size: 1.1rem;">
                        <i class="fas fa-user-plus"></i> Register as MACOSA
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Leadership Messages -->
<section class="section bg-white">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Messages from Leadership</h2>
            <p>Words from our esteemed leaders and founders</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem; margin-top: 3rem;">
            <!-- Founder's Message -->
            <div style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-left: 5px solid var(--primary-blue);" data-aos="fade-up" data-aos-delay="100">
                <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary-blue), var(--accent-green)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; font-weight: bold; margin-right: 1rem;">E</div>
                    <div>
                        <h4 style="color: var(--primary-blue); margin: 0;">Emmanuel Nyesigomu</h4>
                        <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">MACOSA Founder</p>
                    </div>
                </div>
                <blockquote style="font-style: italic; color: var(--text-light); line-height: 1.6; margin: 0; padding-left: 1rem; border-left: 3px solid var(--background-light);">
                    "As founders of this association, we had a vision to create a platform that would reconnect us with our alma mater, with each other, and with our shared experiences. Today, we have built a vibrant community of old students who share a passion for our school, our values, and our mission."
                </blockquote>
            </div>

            <!-- President's Message -->
            <div style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-left: 5px solid var(--accent-green);" data-aos="fade-up" data-aos-delay="200">
                <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--accent-green), var(--accent-yellow)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; font-weight: bold; margin-right: 1rem;">R</div>
                    <div>
                        <h4 style="color: var(--accent-green); margin: 0;">Richard Bagada</h4>
                        <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">MACOSA President</p>
                    </div>
                </div>
                <blockquote style="font-style: italic; color: var(--text-light); line-height: 1.6; margin: 0; padding-left: 1rem; border-left: 3px solid var(--background-light);">
                    "As we strive to elevate our association, I encourage everyone to collaborate toward this shared goal. Together, we can enhance our school community through ongoing involvement in various activities, active participation in association events, and opportunities to connect."
                </blockquote>
            </div>

            <!-- School Leadership -->
            <div style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-left: 5px solid var(--accent-yellow);" data-aos="fade-up" data-aos-delay="300">
                <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--accent-yellow), var(--primary-blue)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: var(--primary-blue); font-weight: bold; margin-right: 1rem;">L</div>
                    <div>
                        <h4 style="color: var(--accent-yellow); margin: 0;">Leonard Mugisha</h4>
                        <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">Headteacher</p>
                    </div>
                </div>
                <blockquote style="font-style: italic; color: var(--text-light); line-height: 1.6; margin: 0; padding-left: 1rem; border-left: 3px solid var(--background-light);">
                    "Our school's commitment to excellence, integrity, and service remains unwavering, and we are proud to see these values reflected in the accomplishments and endeavors of our alumni. You are an integral part of our school's story."
                </blockquote>
            </div>
        </div>
    </div>
</section>

<!-- MACOSA Executive Committee -->
<section class="section">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Executive Committee (2025-2027)</h2>
            <p>Meet our dedicated leadership team serving the MACOSA community</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 3rem; max-width: 1000px; margin-left: auto; margin-right: auto;">
            <!-- President -->
            <div style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; border-top: 5px solid var(--primary-blue); position: relative;" data-aos="fade-up" data-aos-delay="100">
                <div style="position: absolute; top: -15px; right: 20px; background: var(--primary-blue); color: white; padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: bold;">
                    PRESIDENT
                </div>
                <div style="width: 100px; height: 100px; background: linear-gradient(135deg, var(--primary-blue), #3399ff); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: white; font-weight: bold; margin: 0 auto 1.5rem;">
                    R
                </div>
                <h3 style="color: var(--primary-blue); margin-bottom: 0.5rem; font-size: 1.3rem;">Richard Bagada</h3>
                <p style="color: var(--accent-green); font-weight: bold; margin-bottom: 1rem;">President</p>
                <div style="background: var(--background-light); padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem;">
                    <p style="color: var(--text-light); font-size: 0.9rem; line-height: 1.6; margin: 0;">
                        Leading MACOSA with vision and dedication, fostering unity and growth within our alumni community.
                    </p>
                </div>
                <a href="tel:+256779767250" style="background: var(--primary-blue); color: white; padding: 10px 20px; border-radius: 25px; text-decoration: none; font-size: 0.9rem; display: inline-block;">
                    <i class="fas fa-phone"></i> 0779 767 250
                </a>
            </div>

            <!-- Vice President -->
            <div style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; border-top: 5px solid var(--accent-green); position: relative;" data-aos="fade-up" data-aos-delay="200">
                <div style="position: absolute; top: -15px; right: 20px; background: var(--accent-green); color: white; padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: bold;">
                    VICE PRESIDENT
                </div>
                <div style="width: 100px; height: 100px; background: linear-gradient(135deg, var(--accent-green), #55bb55); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: white; font-weight: bold; margin: 0 auto 1.5rem;">
                    G
                </div>
                <h3 style="color: var(--accent-green); margin-bottom: 0.5rem; font-size: 1.3rem;">Guma Steven</h3>
                <p style="color: var(--primary-blue); font-weight: bold; margin-bottom: 1rem;">Vice President</p>
                <div style="background: var(--background-light); padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem;">
                    <p style="color: var(--text-light); font-size: 0.9rem; line-height: 1.6; margin: 0;">
                        Supporting presidential initiatives and ensuring seamless execution of MACOSA programs and activities.
                    </p>
                </div>
                <a href="contact.php" style="background: var(--accent-green); color: white; padding: 10px 20px; border-radius: 25px; text-decoration: none; font-size: 0.9rem; display: inline-block;">
                    <i class="fas fa-envelope"></i> Contact
                </a>
            </div>

            <!-- Secretary -->
            <div style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; border-top: 5px solid var(--accent-yellow); position: relative;" data-aos="fade-up" data-aos-delay="300">
                <div style="position: absolute; top: -15px; right: 20px; background: var(--accent-yellow); color: var(--primary-blue); padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: bold;">
                    SECRETARY
                </div>
                <div style="width: 100px; height: 100px; background: linear-gradient(135deg, var(--accent-yellow), #ffdd00); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: var(--primary-blue); font-weight: bold; margin: 0 auto 1.5rem;">
                    F
                </div>
                <h3 style="color: var(--accent-yellow); margin-bottom: 0.5rem; font-size: 1.3rem;">Flower Lucky Bridget</h3>
                <p style="color: var(--primary-blue); font-weight: bold; margin-bottom: 1rem;">Secretary</p>
                <div style="background: var(--background-light); padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem;">
                    <p style="color: var(--text-light); font-size: 0.9rem; line-height: 1.6; margin: 0;">
                        Managing communications, documentation, and ensuring efficient coordination of all MACOSA activities.
                    </p>
                </div>
                <a href="contact.php" style="background: var(--accent-yellow); color: var(--primary-blue); padding: 10px 20px; border-radius: 25px; text-decoration: none; font-size: 0.9rem; display: inline-block;">
                    <i class="fas fa-envelope"></i> Contact
                </a>
            </div>

            <!-- Treasurer -->
            <div style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; border-top: 5px solid var(--primary-blue); position: relative;" data-aos="fade-up" data-aos-delay="400">
                <div style="position: absolute; top: -15px; right: 20px; background: var(--primary-blue); color: white; padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: bold;">
                    TREASURER
                </div>
                <div style="width: 100px; height: 100px; background: linear-gradient(135deg, var(--primary-blue), var(--accent-green)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: white; font-weight: bold; margin: 0 auto 1.5rem;">
                    D
                </div>
                <h3 style="color: var(--primary-blue); margin-bottom: 0.5rem; font-size: 1.3rem;">Dorothy Bainomugisa</h3>
                <p style="color: var(--accent-green); font-weight: bold; margin-bottom: 1rem;">Treasurer</p>
                <div style="background: var(--background-light); padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem;">
                    <p style="color: var(--text-light); font-size: 0.9rem; line-height: 1.6; margin: 0;">
                        Overseeing financial management, SACCO operations, and ensuring transparent use of association resources.
                    </p>
                </div>
                <a href="contact.php" style="background: var(--primary-blue); color: white; padding: 10px 20px; border-radius: 25px; text-decoration: none; font-size: 0.9rem; display: inline-block;">
                    <i class="fas fa-envelope"></i> Contact
                </a>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 3rem;" data-aos="fade-up" data-aos-delay="500">
            <div style="background: var(--background-light); padding: 2rem; border-radius: 15px; max-width: 600px; margin: 0 auto;">
                <h4 style="color: var(--primary-blue); margin-bottom: 1rem;">Committee Term: 2025-2027</h4>
                <p style="color: var(--text-light); line-height: 1.6; margin-bottom: 1.5rem;">
                    Our executive committee is committed to serving the MACOSA community with integrity, 
                    transparency, and dedication. Together, we work to strengthen our alumni network 
                    and support the continued growth of our alma mater.
                </p>
                <a href="contact.php" style="background: var(--primary-blue); color: white; padding: 12px 25px; border-radius: 25px; text-decoration: none; font-weight: bold;">
                    <i class="fas fa-handshake"></i> Contact Executive Committee
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Ways to Give Back -->
<section class="section">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Ways to Give Back</h2>
            <p>Support the next generation of leaders</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <!-- Enhanced responsive grid for Ways to Give Back -->
            <style>
                @media (min-width: 1200px) {
                    .giveback-grid { grid-template-columns: repeat(3, 1fr) !important; }
                }
                @media (min-width: 768px) and (max-width: 1199px) {
                    .giveback-grid { grid-template-columns: repeat(2, 1fr) !important; }
                }
                .giveback-card {
                    background: white;
                    padding: 2.5rem;
                    border-radius: 20px;
                    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
                    text-align: center;
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                    border-top: 4px solid transparent;
                    position: relative;
                    overflow: hidden;
                }
                .giveback-card::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 4px;
                    background: linear-gradient(90deg, var(--primary-blue), var(--accent-green), var(--accent-yellow));
                    opacity: 0;
                    transition: opacity 0.3s ease;
                }
                .giveback-card:hover {
                    transform: translateY(-8px);
                    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
                }
                .giveback-card:hover::before {
                    opacity: 1;
                }
                .giveback-card .icon {
                    width: 90px;
                    height: 90px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 2rem;
                    font-size: 2.2rem;
                    color: white;
                    position: relative;
                    overflow: hidden;
                }
                .giveback-card .icon::before {
                    content: '';
                    position: absolute;
                    top: -50%;
                    left: -50%;
                    width: 200%;
                    height: 200%;
                    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.2), transparent);
                    transform: rotate(45deg);
                    transition: transform 0.6s;
                }
                .giveback-card:hover .icon::before {
                    transform: rotate(45deg) translate(100%, 100%);
                }
                .giveback-card h3 {
                    color: var(--primary-blue);
                    margin-bottom: 1.2rem;
                    font-size: 1.4rem;
                    font-weight: 600;
                }
                .giveback-card p {
                    color: var(--text-light);
                    line-height: 1.7;
                    margin-bottom: 1.5rem;
                    font-size: 1rem;
                }
                .giveback-card a {
                    display: inline-flex;
                    align-items: center;
                    padding: 12px 25px;
                    border-radius: 25px;
                    text-decoration: none;
                    font-weight: 600;
                    font-size: 0.95rem;
                    transition: all 0.3s ease;
                    background: var(--background-light);
                    border: 2px solid transparent;
                }
                .giveback-card a:hover {
                    background: white;
                    border-color: currentColor;
                    transform: translateY(-2px);
                    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                }
            </style>
            <div class="giveback-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <div class="giveback-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon" style="background: linear-gradient(135deg, var(--primary-blue), #3399ff);">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Scholarship Fund</h3>
                    <p>Establish named scholarships or contribute to our scholarship fund to help deserving students access quality education and recognize academic excellence.</p>
                    <a href="contact.php" style="color: var(--primary-blue);">
                        <i class="fas fa-heart" style="margin-right: 0.5rem;"></i>
                        Learn More
                    </a>
                </div>

                <div class="giveback-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon" style="background: linear-gradient(135deg, var(--accent-green), #55bb55);">
                        <i class="fas fa-building"></i>
                    </div>
                    <h3>Infrastructure Development</h3>
                    <p>Support school improvements, new facilities, technology upgrades, security enhancements, and learning resources for enhanced education quality.</p>
                    <a href="contact.php" style="color: var(--accent-green);">
                        <i class="fas fa-hammer" style="margin-right: 0.5rem;"></i>
                        Contribute
                    </a>
                </div>

                <div class="giveback-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="icon" style="background: linear-gradient(135deg, var(--accent-yellow), #ffdd00);">
                        <i class="fas fa-piggy-bank"></i>
                    </div>
                    <h3>MACOSA SACCO Project</h3>
                    <p>Join our savings and credit cooperative to access financial services, investment opportunities, and support fellow alumni's financial empowerment.</p>
                    <a href="contact.php" style="color: var(--accent-yellow);">
                        <i class="fas fa-handshake" style="margin-right: 0.5rem;"></i>
                        Join SACCO
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact MACOSA Office -->
<section class="section bg-white">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>MACOSA Office Contact</h2>
            <p>We're here to help you stay connected</p>
        </div>
        
        <div style="max-width: 800px; margin: 0 auto;">
            <div class="contact-grid">
                <div data-aos="fade-right">
                    <div class="contact-item">
                        <div class="icon" style="background: var(--primary-blue);">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <div>
                            <strong>MACOSA President</strong><br>
                            Richard Bagada<br>
                            <a href="mailto:macosa24@gmail.com" style="color: var(--primary-blue);">macosa24@gmail.com</a>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="icon" style="background: var(--accent-green);">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div>
                            <strong>MACOSA Founder</strong><br>
                            Emmanuel Nyesigomu<br>
                            <a href="mailto:nyesigomu@gmail.com" style="color: var(--primary-blue);">nyesigomu@gmail.com</a>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="icon" style="background: var(--accent-green);">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <strong>Contact Lines</strong><br>
                            <a href="tel:+256779767250" style="color: var(--primary-blue);">+256 779 767 250</a> (President)<br>
                            <a href="tel:+256774296700" style="color: var(--primary-blue);">+256 774 296 700</a> (Founder)<br>
                            <a href="tel:+256700314647" style="color: var(--primary-blue);">+256 700 314 647</a> (Founder Alt)<br>
                            <small style="color: var(--text-light);">Monday - Friday: 8:00 AM - 5:00 PM</small>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="icon" style="background: var(--accent-yellow); color: var(--primary-blue);">
                            <i class="fas fa-school"></i>
                        </div>
                        <div>
                            <strong>School Director</strong><br>
                            Baguma Fred<br>
                            <a href="tel:+256758154199" style="color: var(--primary-blue);">+256 758 154 199</a>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="icon" style="background: var(--accent-yellow); color: var(--primary-blue);">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div>
                            <strong>WhatsApp Group</strong><br>
                            Join our MACOSA WhatsApp community<br>
                            <a href="https://chat.whatsapp.com/KCD6P8GSLoh6aSjO3qbJNd" style="color: var(--primary-blue);">Join MACOSA Group</a>
                        </div>
                    </div>
                </div>
                
                <div data-aos="fade-left">
                    <div style="background: var(--background-light); padding: 2rem; border-radius: 10px;">
                        <h4 style="color: var(--primary-blue); margin-bottom: 1rem;">Connect With Us</h4>
                        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
                            <a href="#" style="background: #1877f2; color: white; padding: 0.8rem; border-radius: 8px; text-decoration: none; display: flex; align-items: center; flex: 1; justify-content: center;">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" style="background: #1da1f2; color: white; padding: 0.8rem; border-radius: 8px; text-decoration: none; display: flex; align-items: center; flex: 1; justify-content: center;">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" style="background: #0077b5; color: white; padding: 0.8rem; border-radius: 8px; text-decoration: none; display: flex; align-items: center; flex: 1; justify-content: center;">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" style="background: #25d366; color: white; padding: 0.8rem; border-radius: 8px; text-decoration: none; display: flex; align-items: center; flex: 1; justify-content: center;">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                        
                        <p style="color: var(--text-light); line-height: 1.6; margin-bottom: 1.5rem;">
                            Stay updated with the latest MACOSA news, events, and opportunities. 
                            Follow us on social media and join our mailing list.
                        </p>
                        
                        <a href="news.php" class="btn" style="width: 100%; justify-content: center;">
                            <i class="fas fa-newspaper"></i> Read MACOSA News
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
