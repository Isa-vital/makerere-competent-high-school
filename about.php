<?php
// About Us page for Makerere Competent High School
$page_title = 'About Us';
$page_description = 'Learn about Makerere Competent High School\'s history, mission, vision, and commitment to excellence in education.';
$page_keywords = 'about Makerere Competent High School, school history, mission, vision, educational excellence';

include 'includes/header.php';
?>

<!-- Page Header -->
<div class="page-header bg-gradient-primary text-white py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3" data-aos="fade-down">About Makerere Competent High School</h1>
        <p class="lead mb-0" data-aos="fade-up" data-aos-delay="100">Discover our journey of excellence in education since 1998</p>
    </div>
</div>

<!-- Main Content -->
<section class="page-content py-5">
    <div class="container">
        <!-- Introduction -->
        <div class="row align-items-center mb-5" data-aos="fade-up">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <span class="badge bg-primary mb-3">Our Story</span>
                <h2 class="display-6 fw-bold text-primary mb-4">Welcome to Excellence</h2>
                <p class="lead text-muted mb-4">
                    Makerere Competent High School stands as a beacon of educational excellence in Uganda,
                    committed to nurturing young minds and shaping future leaders.
                </p>
                <p class="text-muted mb-4">
                    Since our establishment in 1998, we have consistently maintained the highest standards
                    of academic achievement while fostering character development and moral values.
                </p>
                <p class="text-muted">
                    Our school is strategically located on Hoima-Fort Road, providing an ideal
                    learning environment that combines academic rigor with holistic development. We take
                    pride in our track record of producing well-rounded graduates who excel in various
                    fields and contribute meaningfully to society.
                </p>
            </div>
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="assets/images/gate.jpeg" alt="School Building"
                        class="img-fluid rounded-4 shadow-lg">
                    <div class="position-absolute bottom-0 start-0 p-4 bg-white rounded-3 shadow m-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-award fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">25+ Years</h5>
                                <small class="text-muted">Of Excellence</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mission, Vision, Values -->
        <div class="bg-light rounded-4 p-5 mb-5">
            <div class="row g-4">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body p-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                                <i class="fas fa-bullseye fa-2x text-primary"></i>
                            </div>
                            <h3 class="h4 fw-bold mb-3">Our Mission</h3>
                            <p class="text-muted mb-0"><?php echo getSetting('school_mission', 'To provide quality education and nurture future leaders through excellence in teaching, character development, and community service, preparing students for success in a rapidly changing world.'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body p-4">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                                <i class="fas fa-eye fa-2x text-success"></i>
                            </div>
                            <h3 class="h4 fw-bold mb-3">Our Vision</h3>
                            <p class="text-muted mb-0"><?php echo getSetting('school_vision', 'To be the leading educational institution in Uganda, recognized for academic excellence, character formation, and producing globally competitive graduates who contribute to national development.'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body p-4">
                            <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                                <i class="fas fa-heart fa-2x text-danger"></i>
                            </div>
                            <h3 class="h4 fw-bold mb-3">Our Values</h3>
                            <p class="text-muted mb-0">Excellence, Integrity, Respect, Innovation, Community Service, and Leadership form the foundation of everything we do, guiding our students to become responsible citizens and future leaders.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- School History -->
        <div class="row align-items-center mb-5" data-aos="fade-up">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <img src="assets/images/gate.jpeg" alt="School Founders"
                    class="img-fluid rounded-4 shadow-lg">
            </div>
            <div class="col-lg-7">
                <span class="badge bg-success mb-3">Our Heritage</span>
                <h2 class="display-6 fw-bold text-primary mb-4">Our Rich History</h2>
                <div class="timeline-content">
                    <div class="mb-4">
                        <div class="d-flex align-items-start">
                            <div class="bg-primary rounded-circle p-2 me-3 flex-shrink-0">
                                <i class="fas fa-calendar-alt text-white"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-2">1998 - The Beginning</h5>
                                <p class="text-muted">
                                    Founded by Col Fred Baguma with a simple yet profound mission: to provide quality
                                    education that transforms lives and builds a better Uganda.
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex align-items-start">
                            <div class="bg-success rounded-circle p-2 me-3 flex-shrink-0">
                                <i class="fas fa-chart-line text-white"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-2">Growth & Expansion</h5>
                                <p class="text-muted">
                                    Over the years, we have expanded our facilities and strengthened our commitment to
                                    excellence. Our graduates have pursued higher education at prestigious universities
                                    locally and internationally.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex align-items-start">
                            <div class="bg-warning rounded-circle p-2 me-3 flex-shrink-0">
                                <i class="fas fa-star text-white"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-2">Today - A Legacy of Excellence</h5>
                                <p class="text-muted mb-0">
                                    We continue to build on our legacy, embracing innovation while maintaining our
                                    core values and commitment to holistic education.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leadership Team -->
        <div class="bg-light rounded-4 p-5 mb-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-primary mb-3">Meet Our Team</span>
                <h2 class="display-6 fw-bold text-primary mb-3">Our Leadership Team</h2>
                <p class="text-muted">Dedicated professionals committed to your child's success</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 shadow-sm h-100 text-center hover-lift">
                        <div class="card-body p-4">
                            <div class="position-relative d-inline-block mb-3">
                                <img src="assets/images/WhatsApp Image 2024-11-13 at 09.24.10.jpeg"
                                    alt="Resident Director"
                                    class="rounded-circle shadow"
                                    style="width: 150px; height: 150px; object-fit: cover;">
                                <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-2">
                                    <i class="fas fa-user-tie text-white"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-1">Mrs. Baguma Anita</h4>
                            <p class="text-primary fw-semibold mb-3">Resident Director</p>
                            <p class="text-muted small">With over 10 years in education, leadership and management, Mrs. Baguma leads our school with vision and dedication, ensuring every student reaches their full potential.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card border-0 shadow-sm h-100 text-center hover-lift">
                        <div class="card-body p-4">
                            <div class="position-relative d-inline-block mb-3">
                                <img src="assets/images/WhatsApp Image 2024-11-13 at 09.24.10.jpeg"
                                    alt="Head Teacher"
                                    class="rounded-circle shadow"
                                    style="width: 150px; height: 150px; object-fit: cover;">
                                <div class="position-absolute bottom-0 end-0 bg-success rounded-circle p-2">
                                    <i class="fas fa-chalkboard-teacher text-white"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-1">Mr. Mugisha Leonard</h4>
                            <p class="text-success fw-semibold mb-3">Head Teacher</p>
                            <p class="text-muted small">Mr. Mugisha oversees academic programs and student affairs, bringing expertise in curriculum development and student mentorship.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card border-0 shadow-sm h-100 text-center hover-lift">
                        <div class="card-body p-4">
                            <div class="position-relative d-inline-block mb-3">
                                <img src="assets/images/WhatsApp Image 2024-11-13 at 09.24.10.jpeg"
                                    alt="Dean of Studies"
                                    class="rounded-circle shadow"
                                    style="width: 150px; height: 150px; object-fit: cover;">
                                <div class="position-absolute bottom-0 end-0 bg-warning rounded-circle p-2">
                                    <i class="fas fa-book-reader text-white"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-1">Mr. Marvin</h4>
                            <p class="text-warning fw-semibold mb-3">Dean of Studies</p>
                            <p class="text-muted small">Mr. Marvin ensures academic excellence through innovative teaching methods and continuous professional development for our staff.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Achievements -->
        <div class="mb-5" data-aos="fade-up">
            <div class="text-center mb-5">
                <span class="badge bg-warning text-dark mb-3">Our Pride</span>
                <h2 class="display-6 fw-bold text-primary mb-3">Our Achievements</h2>
                <p class="text-muted">A track record of excellence and success</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 bg-gradient-primary text-white h-100 hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-trophy fa-3x opacity-75"></i>
                            </div>
                            <h3 class="h4 fw-bold mb-3">Academic Excellence</h3>
                            <p class="small mb-0">Consistently ranked 1st in Kikuube and among the top 10 schools in Bunyoro Region for O-Level and A-Level performance.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="card border-0 bg-gradient-primary text-white h-100 hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-medal fa-3x opacity-75"></i>
                            </div>
                            <h3 class="h4 fw-bold mb-3">Sports Champions</h3>
                            <p class="small mb-0">Multiple district and regional championships in football and athletics.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="card border-0 bg-gradient-primary text-white h-100 hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-graduation-cap fa-3x opacity-75"></i>
                            </div>
                            <h3 class="h4 fw-bold mb-3">University Scholarships</h3>
                            <p class="small mb-0">Over 200 students received full scholarships to top universities on Government Sponsorships in the past decade.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="card border-0 bg-gradient-primary text-white h-100 hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-award fa-3x opacity-75"></i>
                            </div>
                            <h3 class="h4 fw-bold mb-3">Debate Championship</h3>
                            <p class="small mb-0">Winner of Inter-School Debate Championship, showcasing leadership and critical thinking skills.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Facilities -->
        <div class="bg-light rounded-4 p-5 mb-5">
            <div class="text-center mb-5" data-aos="fade-up">

                <h2 class="display-6 fw-bold text-primary mb-3">World-Class Facilities</h2>
                <p class="text-muted">State-of-the-art infrastructure for comprehensive learning</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body p-4">
                            <div class="bg-primary bg-opacity-10 rounded-3 d-inline-flex p-3 mb-3">
                                <i class="fas fa-microscope fa-2x text-primary"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Science Laboratories</h5>
                            <p class="text-muted small mb-0">State-of-the-art physics, chemistry, and biology laboratories equipped with modern instruments and safety equipment.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body p-4">
                            <div class="bg-success bg-opacity-10 rounded-3 d-inline-flex p-3 mb-3">
                                <i class="fas fa-laptop fa-2x text-success"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Computer Laboratory</h5>
                            <p class="text-muted small mb-0">Modern computer lab with high-speed internet, latest software, and conducive environments for ICT education.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body p-4">
                            <div class="bg-warning bg-opacity-10 rounded-3 d-inline-flex p-3 mb-3">
                                <i class="fas fa-book fa-2x text-warning"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Library</h5>
                            <p class="text-muted small mb-0">Comprehensive library with relevant books, digital resources, and quiet study areas for research and learning.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body p-4">
                            <div class="bg-danger bg-opacity-10 rounded-3 d-inline-flex p-3 mb-3">
                                <i class="fas fa-running fa-2x text-danger"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Sports Complex</h5>
                            <p class="text-muted small mb-0">Multi-purpose sports hall, Football pitch, volleyball court, and athletics track for physical education.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body p-4">
                            <div class="bg-info bg-opacity-10 rounded-3 d-inline-flex p-3 mb-3">
                                <i class="fas fa-chalkboard fa-2x text-info"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Classrooms</h5>
                            <p class="text-muted small mb-0">Modern, spacious, airy classrooms with comfortable seating, proper lighting, and learning aids that foster engagement.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body p-4">
                            <div class="bg-secondary bg-opacity-10 rounded-3 d-inline-flex p-3 mb-3">
                                <i class="fas fa-bed fa-2x text-secondary"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Boarding Facilities</h5>
                            <p class="text-muted small mb-0">Comfortable dormitories with modern amenities, providing a safe and conducive environment for boarding students.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="bg-gradient-primary text-white rounded-4 p-5 text-center" data-aos="fade-up">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="display-6 fw-bold mb-3">Join Our School of Excellence</h2>
                    <p class="lead mb-4 opacity-90">
                        Discover how Makerere Competent High School can help your child achieve their dreams
                        and reach their full potential in a supportive, excellence-driven environment.
                    </p>
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
    </div>
</section>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .bg-gradient-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .bg-gradient-danger {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }

    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
    }

    .page-header.bg-gradient-primary {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    }
</style>

<?php include 'includes/footer.php'; ?>