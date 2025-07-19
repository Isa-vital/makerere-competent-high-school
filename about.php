<?php
// About Us page for Makerere Competent High School
$page_title = 'About Us';
$page_description = 'Learn about Makerere Competent High School\'s history, mission, vision, and commitment to excellence in education.';
$page_keywords = 'about Makerere Competent High School, school history, mission, vision, educational excellence';

include 'includes/header.php';
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1>About Makerere Competent High School</h1>
        <p>Discover our journey of excellence in education since 1998</p>
    </div>
</div>

<!-- Main Content -->
<section class="page-content">
    <div class="container">
        <!-- Introduction -->
        <div class="section" data-aos="fade-up">
            <div class="intro-grid">
                <div class="intro-content">
                    <h2 style="color: #1a472a; margin-bottom: 1.5rem;">Welcome to Excellence</h2>
                    <p style="font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.5rem;">
                        Makerere Competent High School stands as a beacon of educational excellence in Uganda, 
                        committed to nurturing young minds and shaping future leaders. Since our establishment 
                        in 1998, we have consistently maintained the highest standards of academic achievement 
                        while fostering character development and moral values.
                    </p>
                    <p style="font-size: 1.1rem; line-height: 1.8;">
                        Our school is strategically located on Makerere Hill in Kampala, providing an ideal 
                        learning environment that combines academic rigor with holistic development. We take 
                        pride in our track record of producing well-rounded graduates who excel in various 
                        fields and contribute meaningfully to society.
                    </p>
                </div>
                <div class="intro-image">
                    <img src="assets/images/gate.jpeg" alt="School Building" 
                         style="width: 100%; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                </div>
            </div>
        </div>

        <!-- Mission, Vision, Values -->
        <div class="section bg-white" style="padding: 4rem 0;">
            <div class="features-grid">
                <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3>Our Mission</h3>
                    <p><?php echo getSetting('school_mission', 'To provide quality education and nurture future leaders through excellence in teaching, character development, and community service, preparing students for success in a rapidly changing world.'); ?></p>
                </div>
                
                <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Our Vision</h3>
                    <p><?php echo getSetting('school_vision', 'To be the leading educational institution in Uganda, recognized for academic excellence, character formation, and producing globally competitive graduates who contribute to national development.'); ?></p>
                </div>
                
                <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Our Values</h3>
                    <p>Excellence, Integrity, Respect, Innovation, Community Service, and Leadership form the foundation of everything we do, guiding our students to become responsible citizens and future leaders.</p>
                </div>
            </div>
        </div>

        <!-- School History -->
        <div class="section" data-aos="fade-up">
            <h2 style="color: #1a472a; text-align: center; margin-bottom: 3rem;">Our Rich History</h2>
            <div style="display: grid; grid-template-columns: 300px 1fr; gap: 3rem; align-items: start;">
                <div>
                    <img src="assets/images/gate.jpeg" alt="School Founders" 
                         style="width: 100%; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                </div>
                <div style="font-size: 1.1rem; line-height: 1.8;">
                    <p style="margin-bottom: 1.5rem;">
                        Founded in 1998 by Col Fred Baguma, Makerere Competent High School began 
                        with a simple yet profound mission: to provide quality education that transforms lives 
                        and builds a better Uganda. What started as a small institution with just 50 students 
                        has grown into one of the most respected secondary schools in the country.
                    </p>
                    <p style="margin-bottom: 1.5rem;">
                        Over the years, we have expanded our facilities, and strengthened 
                        our commitment to excellence. Our graduates have gone on to pursue higher education at 
                        prestigious universities both locally and internationally, making significant contributions 
                        in various fields including medicine, engineering, law, business, and public service.
                    </p>
                    <p>
                        Today, we continue to build on our legacy of excellence, embracing innovation while 
                        maintaining our core values and commitment to holistic education.
                    </p>
                </div>
            </div>
        </div>

        <!-- Leadership Team -->
        <div class="section bg-white" style="padding: 4rem 0;">
            <h2 style="color: #1a472a; text-align: center; margin-bottom: 3rem;" data-aos="fade-up">Our Leadership Team</h2>
            <div class="features-grid">
                <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                    <img src="assets/images/WhatsApp Image 2024-11-13 at 09.24.10.jpeg" alt=" Resident Director" 
                         style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin: 0 auto 1rem;">
                    <h3>Mrs. Baguma Anita</h3>
                    <p style="color: #1a472a; font-weight: 500; margin-bottom: 1rem;">Resident Director</p>
                    <p>With over 10 years in education, leadership and management, Mrs. Baguma leads our school with vision and dedication, ensuring every student reaches their full potential.</p>
                </div>
                
                <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                    <img src="assets/images/WhatsApp Image 2024-11-13 at 09.24.10.jpeg" alt="Head Teacher" 
                         style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin: 0 auto 1rem;">
                    <h3>Mr. Ibrahim</h3>
                    <p style="color: #1a472a; font-weight: 500; margin-bottom: 1rem;"> Head Teacher</p>
                    <p>Mr. Ibrahim oversees academic programs and student affairs, bringing expertise in curriculum development and student mentorship.</p>
                </div>
                
                <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                    <img src="assets/images/WhatsApp Image 2024-11-13 at 09.24.10.jpeg" alt="Dean of Studies" 
                         style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin: 0 auto 1rem;">
                    <h3>Mr. Marvin</h3>
                    <p style="color: #1a472a; font-weight: 500; margin-bottom: 1rem;">Dean of Studies</p>
                    <p>Mr. Marvin ensures academic excellence through innovative teaching methods and continuous professional development for our staff.</p>
                </div>
            </div>
        </div>

        <!-- Achievements -->
        <div class="section" data-aos="fade-up">
            <h2 style="color: #1a472a; text-align: center; margin-bottom: 3rem;">Our Achievements</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center;">
                    <i class="fas fa-trophy" style="font-size: 3rem; color: #ffd700; margin-bottom: 1rem;"></i>
                    <h3 style="color: #1a472a; margin-bottom: 1rem;">Academic Excellence</h3>
                    <p>Consistently ranked the 1st in Kikuube and among the top 10 schools in Bunyoro Region for O-Level and A-Level performance.</p>
                </div>
                
                <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center;">
                    <i class="fas fa-medal" style="font-size: 3rem; color: #ffd700; margin-bottom: 1rem;"></i>
                    <h3 style="color: #1a472a; margin-bottom: 1rem;">Sports Champions</h3>
                    <p>Multiple district and regional championships in football.</p>
                </div>
                
                <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center;">
                    <i class="fas fa-graduation-cap" style="font-size: 3rem; color: #ffd700; margin-bottom: 1rem;"></i>
                    <h3 style="color: #1a472a; margin-bottom: 1rem;">University Scholarships</h3>
                    <p>Over 200 students have received full scholarships to top universities on Government Sponsorships and District Quota in the past decade.</p>
                </div>
                
                <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center;">
                    <i class="fas fa-award" style="font-size: 3rem; color: #ffd700; margin-bottom: 1rem;"></i>
                    <h3 style="color: #1a472a; margin-bottom: 1rem;">Inter-School Debate Championship Victory</h3>
                    <p></p>Recognition for excellence in public speaking and winning the Inter-School Debate Championship, showcasing leadership, critical thinking, and communication skills among students.
                </div>
            </div>
        </div>

        <!-- Facilities -->
        <div class="section bg-white" style="padding: 4rem 0;">
            <h2 style="color: #1a472a; text-align: center; margin-bottom: 3rem;" data-aos="fade-up">World-Class Facilities</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px;" data-aos="fade-up" data-aos-delay="100">
                    <i class="fas fa-microscope" style="font-size: 2rem; color: #1a472a; margin-bottom: 1rem;"></i>
                    <h4 style="color: #1a472a; margin-bottom: 1rem;">Science Laboratories</h4>
                    <p>State-of-the-art physics, chemistry, and biology laboratories equipped with modern instruments and safety equipment.</p>
                </div>
                
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px;" data-aos="fade-up" data-aos-delay="200">
                    <i class="fas fa-laptop" style="font-size: 2rem; color: #1a472a; margin-bottom: 1rem;"></i>
                    <h4 style="color: #1a472a; margin-bottom: 1rem;">Computer Laboratory</h4>
                    <p>Modern computer lab with high-speed internet, latest software, conducive environments for ICT education.</p>
                </div>
                
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px;" data-aos="fade-up" data-aos-delay="300">
                    <i class="fas fa-book" style="font-size: 2rem; color: #1a472a; margin-bottom: 1rem;"></i>
                    <h4 style="color: #1a472a; margin-bottom: 1rem;">Library</h4>
                    <p>Comprehensive library with relevant books, digital resources, and quiet study areas for research and learning.</p>
                </div>
                
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px;" data-aos="fade-up" data-aos-delay="400">
                    <i class="fas fa-running" style="font-size: 2rem; color: #1a472a; margin-bottom: 1rem;"></i>
                    <h4 style="color: #1a472a; margin-bottom: 1rem;">Sports Complex</h4>
                    <p>Multi-purpose sports hall, Football pitch, volleyball court, and athletics track for physical education.</p>
                </div>
                
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px;" data-aos="fade-up" data-aos-delay="500">
                    <i class="fas fa-utensils" style="font-size: 2rem; color: #1a472a; margin-bottom: 1rem;"></i>
                    <h4 style="color: #1a472a; margin-bottom: 1rem;">Classrooms</h4>
                    <p>Modern, spacious, airy classrooms with comfortable seating, proper lighting, and learning aids that foster engagement and academic focus.</p>
                </div>
                
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px;" data-aos="fade-up" data-aos-delay="600">
                    <i class="fas fa-bed" style="font-size: 2rem; color: #1a472a; margin-bottom: 1rem;"></i>
                    <h4 style="color: #1a472a; margin-bottom: 1rem;">Boarding Facilities</h4>
                    <p>Comfortable dormitories with modern amenities, providing a safe and conducive environment for boarding students.</p>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="section bg-green" style="text-align: center; padding: 4rem 0;">
            <div data-aos="fade-up">
                <h2 style="color: white; margin-bottom: 1rem;">Join Our School of Excellence</h2>
                <p style="color: rgba(255,255,255,0.9); font-size: 1.1rem; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
                    Discover how Makerere Competent High School can help your child achieve their dreams and reach their full potential in a supportive, excellence-driven environment.
                </p>
                <a href="admissions.php" class="btn" style="margin-right: 15px;">Apply for Admission</a>
                <a href="contact.php" class="btn btn-secondary">Schedule a Visit</a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
