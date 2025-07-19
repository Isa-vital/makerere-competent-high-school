<?php
// Academics page for Makerere Competent High School
$page_title = 'Academic Programs';
$page_description = 'Discover our comprehensive academic programs, experienced faculty, and educational excellence at Makerere Competent High School.';
$page_keywords = 'academics, curriculum, subjects, O-level, A-level, teachers, education, Makerere Competent High School';

include 'includes/header.php';

// Get latest academic news/updates
$academicNews = getLatestNews(3, 'academic');
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1>Academic Excellence</h1>
        <p>Nurturing minds, building futures through quality education</p>
    </div>
</div>

<!-- Main Content -->
<section class="page-content">
    <div class="container">
        <!-- Academic Overview -->
        <div class="section" data-aos="fade-up">
            <div class="academic-intro" style="text-align: center; max-width: 800px; margin: 0 auto 4rem auto;">
                <h2 style="color: #1a472a; margin-bottom: 1.5rem;">Our Academic Philosophy</h2>
                <p style="font-size: 1.1rem; line-height: 1.8; color: #555; margin-bottom: 2rem;">
                    At Makerere Competent High School, we believe in providing holistic education that 
                    prepares students for success in their academic pursuits and future careers. Our 
                    curriculum is designed to foster critical thinking, creativity, and character development.
                </p>
                <div style="display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap; margin-top: 2rem;">
                    <div style="text-align: center;">
                        <div style="background: #1a472a; color: white; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; font-size: 2rem;">
                            <i class="fas fa-medal"></i>
                        </div>
                        <h4 style="color: #1a472a; margin-bottom: 0.5rem;">Excellence</h4>
                        <p style="color: #666; font-size: 0.9rem;">Academic achievement<br>and recognition</p>
                    </div>
                    <div style="text-align: center;">
                        <div style="background: #1a472a; color: white; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; font-size: 2rem;">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h4 style="color: #1a472a; margin-bottom: 0.5rem;">Innovation</h4>
                        <p style="color: #666; font-size: 0.9rem;">Modern teaching<br>methodologies</p>
                    </div>
                    <div style="text-align: center;">
                        <div style="background: #1a472a; color: white; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; font-size: 2rem;">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4 style="color: #1a472a; margin-bottom: 0.5rem;">Collaboration</h4>
                        <p style="color: #666; font-size: 0.9rem;">Teamwork and<br>peer learning</p>
                    </div>
                    <div style="text-align: center;">
                        <div style="background: #1a472a; color: white; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; font-size: 2rem;">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h4 style="color: #1a472a; margin-bottom: 0.5rem;">Global Vision</h4>
                        <p style="color: #666; font-size: 0.9rem;">International<br>perspectives</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Programs -->
        <div class="section bg-white" style="padding: 4rem 0;" data-aos="fade-up">
            <h3 style="color: #1a472a; text-align: center; margin-bottom: 3rem;">Academic Programs</h3>
            
            <!-- O-Level Program -->
            <div class="program-section" style="margin-bottom: 4rem;">
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 3rem; align-items: center;">
                    <div>
                        <div style="background: linear-gradient(135deg, #1a472a 0%, #2d5a3d 100%); color: white; padding: 3rem; border-radius: 15px; text-align: center;">
                            <i class="fas fa-graduation-cap" style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.9;"></i>
                            <h3 style="margin-bottom: 1rem;">O-Level Program</h3>
                            <p style="opacity: 0.9; line-height: 1.6;">Four-year comprehensive secondary education program (S1-S4)</p>
                            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.2);">
                                <h4 style="margin-bottom: 1rem;">Duration: 4 Years</h4>
                                <p style="font-size: 0.9rem; opacity: 0.8;">Ages 13-16 | Senior 1-4</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 style="color: #1a472a; margin-bottom: 1.5rem; font-size: 1.3rem;">Core Subjects</h4>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                            <div class="subject-card">
                                <i class="fas fa-calculator"></i>
                                <h5>Mathematics</h5>
                                <p>Algebra, geometry, statistics</p>
                            </div>
                            <div class="subject-card">
                                <i class="fas fa-language"></i>
                                <h5>English Language</h5>
                                <p>Communication & literature</p>
                            </div>
                            <div class="subject-card">
                                <i class="fas fa-flask"></i>
                                <h5>Sciences</h5>
                                <p>Physics, Chemistry, Biology, Agriculture</p>
                            </div>
                            <div class="subject-card">
                                <i class="fas fa-globe-africa"></i>
                                <h5>Social Studies</h5>
                                <p>History, Geography, Religious Education, Entrepreneurship, Runyoro, Kiswahili</p>
                            </div>
                            <div class="subject-card">
                                <i class="fas fa-laptop"></i>
                                <h5>ICT</h5>
                                <p>Computer studies </p>
                            </div>
                            <div class="subject-card">
                                <i class="fas fa-palette"></i>
                                <h5>Arts & Crafts</h5>
                                <p>Fine Art, Physical Education, TD</p>
                            </div>
                        </div>
                        
                        <h4 style="color: #1a472a; margin: 2rem 0 1rem 0; font-size: 1.3rem;">Optional Subjects</h4>
                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                            <span class="subject-tag">Runyoro</span>
                            <span class="subject-tag">Fine Art</span>
                            <span class="subject-tag">Luganda</span>
                            <span class="subject-tag">Agriculture</span>
                            <span class="subject-tag">Home Economics</span>
                            <span class="subject-tag">Music</span>
                            <span class="subject-tag">Religious Eductaion</span>
                            <span class="subject-tag">Technical Drawing</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- A-Level Program -->
            <div class="program-section">
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem; align-items: center;">
                    <div>
                        <h4 style="color: #1a472a; margin-bottom: 1.5rem; font-size: 1.3rem;">Popular Subject Combinations</h4>
                        <div style="display: grid; gap: 1.5rem;">
                            <!-- Science Combinations -->
                            <div class="combination-card">
                                <h5><i class="fas fa-atom"></i> Sciences</h5>
                                <div class="combination-options">
                                    <div class="option">
                                        <strong>PCM:</strong> Physics, Chemistry, Mathematics
                                    </div>
                                    <div class="option">
                                        <strong>PEM:</strong> Physics, Economics, Mathematics
                                    </div>
                                    <div class="option">
                                        <strong>PCB:</strong> Physics, Chemistry, Biology
                                    </div>
                                    <div class="option">
                                        <strong>BCM:</strong> Biology, Chemistry, Mathematics
                                    </div>
                                    <div class="option">
                                        <strong>BAG:</strong> Biology, Agriculture, Geography
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Arts Combinations -->
                            <div class="combination-card">
                                <h5><i class="fas fa-book"></i> Arts</h5>
                                <div class="combination-options">
                                    <div class="option">
                                        <strong>HEG:</strong> History, Economics, Geography
                                    </div>
                                    <div class="option">
                                        <strong>HLD:</strong> History, Literature, Divinity
                                    </div>
                                    <div class="option">
                                        <strong>LEK:</strong> Literature, Economics, Kinyarwanda
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Commercial Combinations -->
                            <div class="combination-card">
                                <h5><i class="fas fa-chart-line"></i> Commercial</h5>
                                <div class="combination-options">
                                    <div class="option">
                                        <strong>MEG:</strong> Mathematics, Economics, Geography
                                    </div>
                                    <div class="option">
                                        <strong>MEA:</strong> Math, Economics, Agriculture
                                    </div>
                                    <div class="option">
                                        <strong>GEA:</strong> Geography, Economics, Agriculture/Art
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div style="background: linear-gradient(135deg, #2d5a3d 0%, #1a472a 100%); color: white; padding: 3rem; border-radius: 15px; text-align: center;">
                            <i class="fas fa-university" style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.9;"></i>
                            <h3 style="margin-bottom: 1rem;">A-Level Program</h3>
                            <p style="opacity: 0.9; line-height: 1.6;">Advanced level education preparing students for university</p>
                            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.2);">
                                <h4 style="margin-bottom: 1rem;">Duration: 2 Years</h4>
                                <p style="font-size: 0.9rem; opacity: 0.8;">Ages 17-22 | Senior 5-6</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Faculty & Teaching -->
        <div class="section" data-aos="fade-up">
            <h3 style="color: #1a472a; text-align: center; margin-bottom: 3rem;">Our Distinguished Faculty</h3>
            <div style="text-align: center; margin-bottom: 3rem;">
                <p style="font-size: 1.1rem; line-height: 1.7; color: #555; max-width: 700px; margin: 0 auto;">
                    Our experienced and dedicated teaching staff are committed to providing quality education 
                    and mentorship to every student. With advanced degrees and years of teaching experience, 
                    they bring both expertise and passion to the classroom.
                </p>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
                <div class="faculty-highlight" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h4>Qualified Teachers</h4>
                    <p>All our teachers hold Diploma or bachelor's degrees or higher in their respective subjects</p>
                    <div class="stat">75% Graduate Degree</div>
                </div>
                
                <div class="faculty-highlight" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h4>Continuous Training</h4>
                    <p>Regular professional development ensures our staff stay current with educational trends</p>
                    <div class="stat">40+ Hours Annually</div>
                </div>
                
                <div class="faculty-highlight" data-aos="fade-up" data-aos-delay="300">
                    <div class="icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4>Student-Centered</h4>
                    <p>Small class sizes ensure personalized attention and individualized learning support</p>
                    <div class="stat">1:15 Teacher Ratio</div>
                </div>
                
                <div class="faculty-highlight" data-aos="fade-up" data-aos-delay="400">
                    <div class="icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h4>Research Excellence</h4>
<p>Engaged in impactful research that drives innovation and informed learning practices</p>
<div class="stat">15+ Published Studies</div>

                </div>
            </div>
        </div>

        <!-- Academic Facilities -->
        <div class="section bg-white" style="padding: 4rem 0;" data-aos="fade-up">
            <h3 style="color: #1a472a; text-align: center; margin-bottom: 3rem;">State-of-the-Art Facilities</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <div class="facility-card" data-aos="zoom-in" data-aos-delay="100">
                    <img src="<?php echo SITE_URL; ?>/assets/images/science-lab.jpg" alt="Science Laboratory" 
                         style="width: 100%; height: 200px; object-fit: cover; border-radius: 10px 10px 0 0;"
                         onerror="this.src='<?php echo SITE_URL; ?>/assets/images/microscope.jpg'">
                    <div style="padding: 1.5rem;">
                        <h4 style="color: #1a472a; margin-bottom: 1rem;">
                            <i class="fas fa-flask"></i> Science Laboratories
                        </h4>
                        <p style="color: #666; line-height: 1.6; margin-bottom: 1rem;">
                            Fully equipped physics, chemistry, and biology labs with modern equipment 
                            for hands-on experiments and research.
                        </p>
                        <ul style="color: #555; font-size: 0.9rem; line-height: 1.5;">
                            <li> Microscopes and specimens</li>
                            <li>Modern chemical analysis equipment</li>
                            <li>Interactive physics demonstration tools</li>
                            <li>Safety equipment and protocols</li>
                        </ul>
                    </div>
                </div>
                
                <div class="facility-card" data-aos="zoom-in" data-aos-delay="200">
                    <img src="<?php echo SITE_URL; ?>/assets/images/computer-lab.jpg" alt="Computer Laboratory" 
                         style="width: 100%; height: 200px; object-fit: cover; border-radius: 10px 10px 0 0;"
                         onerror="this.src='<?php echo SITE_URL; ?>/assets/images/microscope.jpg'">
                    <div style="padding: 1.5rem;">
                        <h4 style="color: #1a472a; margin-bottom: 1rem;">
                            <i class="fas fa-computer"></i> ICT Center
                        </h4>
                        <p style="color: #666; line-height: 1.6; margin-bottom: 1rem;">
                            Modern computer laboratory with high-speed internet connectivity 
                            for digital literacy and computer science education.
                        </p>
                        <ul style="color: #555; font-size: 0.9rem; line-height: 1.5;">
                            <li>50+ modern computers</li>
                            <li>High-speed internet</li>
                            <li>Required software and tools</li>
                            <li>Interactive lessons</li>
                        </ul>
                    </div>
                </div>
                
                <div class="facility-card" data-aos="zoom-in" data-aos-delay="300">
                    <img src="<?php echo SITE_URL; ?>/assets/images/library.jpg" alt="Library" 
                         style="width: 100%; height: 200px; object-fit: cover; border-radius: 10px 10px 0 0;"
                         onerror="this.src='<?php echo SITE_URL; ?>/assets/images/microscope.jpg'">
                    <div style="padding: 1.5rem;">
                        <h4 style="color: #1a472a; margin-bottom: 1rem;">
                            <i class="fas fa-book"></i> Library & Resource Center
                        </h4>
                        <p style="color: #666; line-height: 1.6; margin-bottom: 1rem;">
                            Comprehensive library with extensive collection of books, 
                            journals, and digital resources for research and study.
                        </p>
                        <ul style="color: #555; font-size: 0.9rem; line-height: 1.5;">
                            <li>500+ books and references</li>
                            <li>Quiet study areas</li>
                            <li>Research assistance</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Performance -->
        <div class="section" data-aos="fade-up">
            <h3 style="color: #1a472a; text-align: center; margin-bottom: 3rem;">Academic Excellence Record</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center;">
                <div>
                    <h4 style="color: #1a472a; margin-bottom: 1.5rem; font-size: 1.3rem;">Our Achievements</h4>
                    <div class="achievement-list">
                        <div class="achievement-item" data-aos="fade-right" data-aos-delay="100">
                            <div class="achievement-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div>
                                <h5>Top Performance in National Exams</h5>
                                <p>Consistently ranking among the top 10 schools in Bunyoro for both O-Level and A-Level results</p>
                            </div>
                        </div>
                        
                        <div class="achievement-item" data-aos="fade-right" data-aos-delay="200">
                            <div class="achievement-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div>
                                <h5>85% University Admission Rate</h5>
                                <p>Our graduates successfully gain admission to top universities in Uganda and internationally</p>
                            </div>
                        </div>
                        
                        <div class="achievement-item" data-aos="fade-right" data-aos-delay="300">
                            <div class="achievement-icon">
                                <i class="fas fa-medal"></i>
                            </div>
                            <div>
                                <h5>Academic Competitions</h5>
                                <p>Multiple wins in debate competitions, and academic challenges</p>
                            </div>
                        </div>
                        
                        <div class="achievement-item" data-aos="fade-right" data-aos-delay="400">
                            <div class="achievement-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div>
                                <h5>Government Scholarships</h5>
                                <p>Most of Our Students at A' Level are always awarded University Admissions on Government Sponsorships</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div data-aos="fade-left">
                    <div style="background: linear-gradient(135deg, #1a472a 0%, #2d5a3d 100%); color: white; padding: 3rem; border-radius: 15px;">
                        <h4 style="margin-bottom: 2rem; text-align: center;">Recent Exam Results</h4>
                        
                        <div style="margin-bottom: 2rem;">
                            <h5 style="margin-bottom: 1rem;">O-Level Results (UCE)</h5>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span>Division 1</span>
                                <span style="font-weight: bold;">65%</span>
                            </div>
                            <div style="background: rgba(255,255,255,0.2); height: 8px; border-radius: 4px; overflow: hidden;">
                                <div style="background: #ffd700; height: 100%; width: 65%; border-radius: 4px;"></div>
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 2rem;">
                            <h5 style="margin-bottom: 1rem;">A-Level Results (UACE)</h5>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span>2 Principal Passes</span>
                                <span style="font-weight: bold;">60%</span>
                            </div>
                            <div style="background: rgba(255,255,255,0.2); height: 8px; border-radius: 4px; overflow: hidden;">
                                <div style="background: #ffd700; height: 100%; width: 82%; border-radius: 4px;"></div>
                            </div>
                        </div>
                        
                        <div style="text-align: center; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.2);">
                            <h5>School Ranking</h5>
                            <div style="font-size: 2rem; font-weight: bold; color: #ffd700; margin: 0.5rem 0;">
                                #1
                            </div>
                            <p style="font-size: 0.9rem; opacity: 0.9;">In Kikuube District</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Support Services -->
        <div class="section bg-white" style="padding: 4rem 0;" data-aos="fade-up">
            <h3 style="color: #1a472a; text-align: center; margin-bottom: 3rem;">Student Support Services</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
                <div class="support-service" data-aos="flip-left" data-aos-delay="100">
                    <div class="service-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h4>Academic Counseling</h4>
                    <p>Personalized guidance for subject selection, study strategies, and academic goal setting.</p>
                    <div class="service-details">
                        <ul>
                            <li>One-on-one consultations</li>
                            <li>Study skills workshops</li>
                            <li>Academic progress monitoring</li>
                        </ul>
                    </div>
                </div>
                
                <div class="support-service" data-aos="flip-left" data-aos-delay="200">
                    <div class="service-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <h4>Remedial Classes</h4>
                    <p>Additional support for students who need extra help in challenging subjects.</p>
                    <div class="service-details">
                        <ul>
                            <li>After-school tutoring</li>
                            <li>Small group sessions</li>
                            <li>Peer learning programs</li>
                        </ul>
                    </div>
                </div>
                
                <div class="support-service" data-aos="flip-left" data-aos-delay="300">
                    <div class="service-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <h4>University Preparation</h4>
                    <p>Comprehensive guidance for university applications and career planning.</p>
                    <div class="service-details">
                        <ul>
                            <li>Application assistance</li>
                            <li>Scholarship guidance</li>
                            <li>Career counseling</li>
                        </ul>
                    </div>
                </div>
                
                <div class="support-service" data-aos="flip-left" data-aos-delay="400">
                    <div class="service-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h4>Learning Support</h4>
                    <p>Specialized assistance for students with different learning needs and abilities.</p>
                    <div class="service-details">
                        <ul>
                            <li>Individual learning plans</li>
                            <li>Special needs support</li>
                            <li>Gifted student programs</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Calendar & News -->
        <?php if (!empty($academicNews)): ?>
        <div class="section" data-aos="fade-up">
            <h3 style="color: #1a472a; text-align: center; margin-bottom: 3rem;">Latest Academic Updates</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <?php foreach ($academicNews as $index => $article): ?>
                <div class="news-card" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                    <div class="news-date">
                        <span><?php echo formatDate($article['created_at'], 'M'); ?></span>
                        <span><?php echo formatDate($article['created_at'], 'd'); ?></span>
                    </div>
                    <div style="padding: 1.5rem;">
                        <h4 style="color: #1a472a; margin-bottom: 1rem;">
                            <a href="news.php?id=<?php echo $article['id']; ?>" style="text-decoration: none; color: inherit;">
                                <?php echo htmlspecialchars($article['title']); ?>
                            </a>
                        </h4>
                        <p style="color: #666; line-height: 1.6; margin-bottom: 1rem;">
                            <?php echo htmlspecialchars(truncateText($article['content'], 120)); ?>
                        </p>
                        <a href="news.php?id=<?php echo $article['id']; ?>" class="read-more">
                            Read More <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div style="text-align: center; margin-top: 3rem;">
                <a href="news.php?category=academic" class="btn">
                    <i class="fas fa-newspaper"></i> View All Academic News
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- Call to Action -->
        <div class="cta-section" style="background: linear-gradient(135deg, #1a472a 0%, #2d5a3d 100%); color: white; padding: 4rem 0; margin-top: 4rem; border-radius: 15px; text-align: center;" data-aos="fade-up">
            <div style="max-width: 600px; margin: 0 auto; padding: 0 2rem;">
                <i class="fas fa-rocket" style="font-size: 4rem; margin-bottom: 1.5rem; opacity: 0.9;"></i>
                <h3 style="margin-bottom: 1.5rem; font-size: 2rem;">Ready to Excel?</h3>
                <p style="font-size: 1.1rem; line-height: 1.6; margin-bottom: 2rem; opacity: 0.9;">
                    Join our community of high achievers and unlock your academic potential. 
                    Our comprehensive programs and dedicated faculty are here to guide your success.
                </p>
                <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
                    <a href="admissions.php" class="btn" style="background: white; color: #1a472a;">
                        <i class="fas fa-user-plus"></i> Apply Now
                    </a>
                    <a href="contact.php" class="btn btn-secondary">
                        <i class="fas fa-calendar"></i> Schedule Visit
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Additional CSS for Academics Page -->
<style>
.subject-card {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 2px solid transparent;
}

.subject-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    border-color: #1a472a;
}

.subject-card i {
    font-size: 2rem;
    color: #1a472a;
    margin-bottom: 1rem;
}

.subject-card h5 {
    color: #1a472a;
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.subject-card p {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.4;
    margin: 0;
}

.subject-tag {
    background: #e8f5e8;
    color: #1a472a;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    border: 1px solid #c8e6c8;
    transition: all 0.3s ease;
}

.subject-tag:hover {
    background: #1a472a;
    color: white;
    transform: translateY(-2px);
}

.combination-card {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    border-left: 4px solid #1a472a;
    transition: all 0.3s ease;
}

.combination-card:hover {
    background: white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transform: translateX(5px);
}

.combination-card h5 {
    color: #1a472a;
    margin-bottom: 1rem;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.combination-options {
    display: grid;
    gap: 0.5rem;
}

.option {
    background: white;
    padding: 0.75rem;
    border-radius: 5px;
    font-size: 0.9rem;
    border: 1px solid #e0e0e0;
}

.option strong {
    color: #1a472a;
}

.faculty-highlight {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.faculty-highlight:hover {
    transform: translateY(-10px);
}

.faculty-highlight .icon {
    background: linear-gradient(135deg, #1a472a 0%, #2d5a3d 100%);
    color: white;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem auto;
    font-size: 2rem;
}

.faculty-highlight h4 {
    color: #1a472a;
    margin-bottom: 1rem;
    font-size: 1.2rem;
}

.faculty-highlight p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.stat {
    background: #e8f5e8;
    color: #1a472a;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 0.9rem;
    display: inline-block;
}

.facility-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.facility-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.facility-card ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.facility-card li {
    padding: 4px 0;
    padding-left: 20px;
    position: relative;
}

.facility-card li::before {
    content: "✓";
    position: absolute;
    left: 0;
    color: #1a472a;
    font-weight: bold;
}

.achievement-item {
    display: flex;
    gap: 1rem;
    padding: 1.5rem;
    background: white;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.achievement-item:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transform: translateX(10px);
}

.achievement-icon {
    background: linear-gradient(135deg, #1a472a 0%, #2d5a3d 100%);
    color: white;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.achievement-item h5 {
    color: #1a472a;
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.achievement-item p {
    color: #666;
    line-height: 1.5;
    margin: 0;
    font-size: 0.9rem;
}

.support-service {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s ease;
    border-top: 4px solid #1a472a;
}

.support-service:hover {
    transform: translateY(-10px);
}

.service-icon {
    background: linear-gradient(135deg, #1a472a 0%, #2d5a3d 100%);
    color: white;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem auto;
    font-size: 2rem;
}

.support-service h4 {
    color: #1a472a;
    margin-bottom: 1rem;
    font-size: 1.2rem;
}

.support-service p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.service-details ul {
    list-style: none;
    padding: 0;
    margin: 0;
    text-align: left;
}

.service-details li {
    padding: 6px 0;
    padding-left: 24px;
    position: relative;
    color: #555;
    font-size: 0.9rem;
}

.service-details li::before {
    content: "→";
    position: absolute;
    left: 0;
    color: #1a472a;
    font-weight: bold;
}

.news-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    position: relative;
}

.news-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

.news-date {
    position: absolute;
    top: 15px;
    right: 15px;
    background: #1a472a;
    color: white;
    padding: 10px;
    border-radius: 8px;
    text-align: center;
    font-weight: bold;
    font-size: 0.9rem;
    line-height: 1.2;
    z-index: 1;
}

.news-date span {
    display: block;
}

.read-more {
    color: #1a472a;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.read-more:hover {
    color: #2d5a3d;
    transform: translateX(5px);
}

@media (max-width: 768px) {
    .program-section div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
        gap: 2rem !important;
    }
    
    .combination-options {
        gap: 0.75rem;
    }
    
    .achievement-item {
        flex-direction: column;
        text-align: center;
    }
    
    .achievement-icon {
        margin: 0 auto 1rem auto;
    }
    
    .faculty-highlight .icon,
    .service-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
}

@media (max-width: 480px) {
    .subject-card,
    .combination-card,
    .faculty-highlight,
    .support-service {
        padding: 1rem;
    }
    
    .cta-section {
        margin-left: -20px;
        margin-right: -20px;
        border-radius: 0;
    }
}
</style>

<?php include 'includes/footer.php'; ?>