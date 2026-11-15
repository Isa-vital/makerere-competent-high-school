<?php

namespace Database\Seeders;

use App\Models\AcademicCombination;
use App\Models\AcademicSubject;
use App\Models\Achievement;
use App\Models\Facility;
use App\Models\Faq;
use App\Models\FeesStructure;
use App\Models\HistoryMilestone;
use App\Models\Leadership;
use App\Models\Scholarship;
use App\Models\SiteStat;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedLeaderships();
        $this->seedFacilities();
        $this->seedFaqs();
        $this->seedSiteStats();
        $this->seedFeesStructures();
        $this->seedAchievements();
        $this->seedHistoryMilestones();
        $this->seedScholarships();
        $this->seedAcademicSubjects();
        $this->seedAcademicCombinations();
    }

    private function seedLeaderships(): void
    {
        $rows = [
            // School Leadership Team (about page)
            ['name' => 'Mrs. Baguma Anita',    'role' => 'Resident Director',  'category' => 'school_leadership', 'bio' => 'With over 10 years in education, leadership and management, Mrs. Baguma leads our school with vision and dedication, ensuring every student reaches their full potential.', 'sort_order' => 1],
            ['name' => 'Mr. Mugisha Leonard',  'role' => 'Head Teacher',       'category' => 'school_leadership', 'bio' => 'Mr. Mugisha oversees academic programs and student affairs, bringing expertise in curriculum development and student mentorship.', 'sort_order' => 2],
            ['name' => 'Mr. Marvin',           'role' => 'Dean of Studies',    'category' => 'school_leadership', 'bio' => 'Mr. Marvin ensures academic excellence through innovative teaching methods and continuous professional development for our staff.', 'sort_order' => 3],

            // MACOSA / Alumni leadership messages
            ['name' => 'Emmanuel Nyesigomu', 'role' => 'MACOSA Founder',     'category' => 'macosa_message', 'extra_info' => 'Senior Accounting Officer', 'email' => 'nyesigomu@gmail.com', 'phone' => '+256 774 296 700',
             'quote' => '"As founders of this association, we had a vision to create a platform that would reconnect us with our alma mater, with each other, and with our shared experiences. Today, we have built a vibrant community of old students who share a passion for our school, our values, and our mission."',
             'bio' => "One of MACOSA's founding members, leading Uganda's digital transformation through ethical accounting practices and mentoring youth across Uganda.",
             'sort_order' => 1],
            ['name' => 'Richard Bagada',     'role' => 'MACOSA President',   'category' => 'macosa_message', 'email' => 'macosa24@gmail.com', 'phone' => '+256 779 767 250',
             'quote' => '"As we strive to elevate our association, I encourage everyone to collaborate toward this shared goal. Together, we can enhance our school community through ongoing involvement in various activities, active participation in association events, and opportunities to connect."',
             'sort_order' => 2],
            ['name' => 'Leonard Mugisha',    'role' => 'Headteacher',        'category' => 'macosa_message',
             'quote' => "\"Our school's commitment to excellence, integrity, and service remains unwavering, and we are proud to see these values reflected in the accomplishments and endeavors of our alumni. You are an integral part of our school's story.\"",
             'sort_order' => 3],

            // Department contacts (contact page)
            ['name' => 'Ms. Baguma Anita', 'role' => 'Admissions Officer',  'category' => 'dept_contact',
             'extra_info' => 'Admissions Office', 'email' => 'admissions@makererecompetenthighschool.com', 'phone' => '+256 704 292872', 'sort_order' => 1],
            ['name' => 'Mr. Ibrahim',      'role' => 'Dean of Studies',      'category' => 'dept_contact',
             'extra_info' => 'Academic Affairs', 'email' => 'academic@makererecompetenthighschool.com', 'phone' => '+256 704 292872', 'sort_order' => 2],
        ];

        foreach ($rows as $row) {
            Leadership::updateOrCreate(
                ['name' => $row['name'], 'category' => $row['category']],
                $row
            );
        }
    }

    private function seedFacilities(): void
    {
        $rows = [
            ['name' => 'Science Laboratories', 'description' => 'State-of-the-art physics, chemistry, and biology laboratories equipped with modern instruments and safety equipment.', 'icon' => 'fa-microscope',  'category' => 'school', 'sort_order' => 1],
            ['name' => 'Computer Laboratory',   'description' => 'Modern computer lab with high-speed internet, latest software, and conducive environments for ICT education.',         'icon' => 'fa-laptop',      'category' => 'school', 'sort_order' => 2],
            ['name' => 'Library',               'description' => 'Comprehensive library with relevant books, digital resources, and quiet study areas for research and learning.',         'icon' => 'fa-book',        'category' => 'school', 'sort_order' => 3],
            ['name' => 'Sports Complex',        'description' => 'Multi-purpose sports hall, Football pitch, volleyball court, and athletics track for physical education.',              'icon' => 'fa-running',     'category' => 'sports', 'sort_order' => 4],
            ['name' => 'Classrooms',            'description' => 'Modern, spacious, airy classrooms with comfortable seating, proper lighting, and learning aids that foster engagement.', 'icon' => 'fa-chalkboard',  'category' => 'school', 'sort_order' => 5],
            ['name' => 'Boarding Facilities',   'description' => 'Comfortable dormitories with modern amenities, providing a safe and conducive environment for boarding students.',       'icon' => 'fa-bed',         'category' => 'boarding', 'sort_order' => 6],
        ];
        foreach ($rows as $r) {
            Facility::updateOrCreate(['name' => $r['name']], $r);
        }
    }

    private function seedFaqs(): void
    {
        $rows = [
            ['question' => 'What are your admission requirements?',
             'answer'   => 'For O-Level admission, students need to have completed Primary 7 with a good PLE certificate. For A-Level, students must have completed O-Level with at least 5 passes including English and Mathematics. We also conduct entrance interviews to assess student readiness.',
             'category' => 'admissions', 'sort_order' => 1],
            ['question' => 'Do you offer boarding facilities?',
             'answer'   => 'Yes, we have excellent boarding facilities for both boys and girls. Our dormitories are modern, secure, and supervised by experienced matrons and patrons. Boarding students enjoy nutritious meals, study time, and recreational activities.',
             'category' => 'boarding', 'sort_order' => 2],
            ['question' => 'What subjects do you offer?',
             'answer'   => 'We offer a comprehensive curriculum including Sciences (Physics, Chemistry, Biology), Mathematics, English, Literature, History, Geography, Arts subjects, Commercial subjects (Accounts, Entrepreneurship), and technical subjects. We also have strong programs in ICT, Sports, and Arts.',
             'category' => 'academics', 'sort_order' => 3],
            ['question' => 'How can parents stay involved?',
             'answer'   => 'We encourage parent involvement through regular parent-teacher meetings, progress reports, parent committees, and school events. Parents can also communicate with teachers through our online portal and attend workshops on child development and academic support.',
             'category' => 'general', 'sort_order' => 4],
            ['question' => 'What is the school fee structure?',
             'answer'   => 'Our school fees vary depending on the level (O-Level or A-Level) and whether the student is a day scholar or boarder. For detailed fee structures and payment plans, please contact our admissions office or visit our admissions page.',
             'category' => 'fees', 'sort_order' => 5],
        ];
        foreach ($rows as $r) {
            Faq::updateOrCreate(['question' => $r['question']], $r);
        }
    }

    private function seedSiteStats(): void
    {
        $rows = [
            // Alumni hero stats
            ['stat_key' => 'macosa_members',     'label' => 'Proud MACOSA Members', 'value' => '5000', 'suffix' => '+', 'category' => 'alumni', 'sort_order' => 1],
            ['stat_key' => 'macosa_countries',   'label' => 'Countries Worldwide',   'value' => '25',   'suffix' => '+', 'category' => 'alumni', 'sort_order' => 2],
            ['stat_key' => 'macosa_fields',      'label' => 'Professional Fields',   'value' => '30',   'suffix' => '+', 'category' => 'alumni', 'sort_order' => 3],
            ['stat_key' => 'macosa_years',       'label' => 'Years of Excellence',    'value' => '8',    'suffix' => '',  'category' => 'alumni', 'sort_order' => 4],

            // About-page hero / facts
            ['stat_key' => 'years_of_excellence', 'label' => 'Years of Excellence', 'value' => '25', 'suffix' => '+', 'category' => 'about', 'sort_order' => 1],
            ['stat_key' => 'university_admission_rate', 'label' => 'University Admission Rate', 'value' => '85', 'suffix' => '%', 'category' => 'academic', 'sort_order' => 1],
            ['stat_key' => 'graduate_teachers_pct', 'label' => 'Graduate Degree Teachers', 'value' => '75', 'suffix' => '%', 'category' => 'academic', 'sort_order' => 2],
            ['stat_key' => 'teacher_training_hours', 'label' => 'Annual Training Hours', 'value' => '40', 'suffix' => '+', 'category' => 'academic', 'sort_order' => 3],
            ['stat_key' => 'teacher_ratio',          'label' => 'Teacher Ratio', 'value' => '1:15', 'suffix' => '', 'category' => 'academic', 'sort_order' => 4],
            ['stat_key' => 'published_studies',      'label' => 'Published Studies', 'value' => '15', 'suffix' => '+', 'category' => 'academic', 'sort_order' => 5],
        ];
        foreach ($rows as $r) {
            SiteStat::updateOrCreate(['stat_key' => $r['stat_key']], $r);
        }
    }

    private function seedFeesStructures(): void
    {
        // From circular page table — Term I 2026
        $rows = [
            // S.1 - S.3
            ['class_level' => 'S.1 - S.3', 'fee_type' => 'boarding',         'amount' => 480000, 'amount_secondary' => null,                  'term' => 'Term I', 'year' => '2026', 'sort_order' => 1],
            ['class_level' => 'S.1 - S.3', 'fee_type' => 'boarding_bursary', 'amount' => 203000, 'amount_secondary' => '203,000/= / 300,000/=', 'term' => 'Term I', 'year' => '2026', 'sort_order' => 2],
            ['class_level' => 'S.1 - S.3', 'fee_type' => 'day',              'amount' => 183000, 'amount_secondary' => null,                  'term' => 'Term I', 'year' => '2026', 'sort_order' => 3],
            ['class_level' => 'S.1 - S.3', 'fee_type' => 'day_bursary',      'amount' => 100000, 'amount_secondary' => null,                  'term' => 'Term I', 'year' => '2026', 'sort_order' => 4],
            // S.4
            ['class_level' => 'S.4',       'fee_type' => 'boarding',         'amount' => 500000, 'amount_secondary' => null,                  'term' => 'Term I', 'year' => '2026', 'sort_order' => 5],
            ['class_level' => 'S.4',       'fee_type' => 'boarding_bursary', 'amount' => 250000, 'amount_secondary' => '250,000/= / 350,000/=', 'term' => 'Term I', 'year' => '2026', 'sort_order' => 6],
            ['class_level' => 'S.4',       'fee_type' => 'day',              'amount' => 230000, 'amount_secondary' => null,                  'term' => 'Term I', 'year' => '2026', 'sort_order' => 7],
            // S.5 - S.6
            ['class_level' => 'S.5 - S.6', 'fee_type' => 'boarding',         'amount' => 530000, 'amount_secondary' => null,                  'term' => 'Term I', 'year' => '2026', 'sort_order' => 8],
            ['class_level' => 'S.5 - S.6', 'fee_type' => 'boarding_bursary', 'amount' => 300000, 'amount_secondary' => '300,000/= / 403,000/=', 'term' => 'Term I', 'year' => '2026', 'sort_order' => 9],
            ['class_level' => 'S.5 - S.6', 'fee_type' => 'day',              'amount' => 310000, 'amount_secondary' => null,                  'term' => 'Term I', 'year' => '2026', 'sort_order' => 10],
            ['class_level' => 'S.5 - S.6', 'fee_type' => 'day_bursary',      'amount' => 203000, 'amount_secondary' => null,                  'term' => 'Term I', 'year' => '2026', 'sort_order' => 11],
        ];
        foreach ($rows as $r) {
            FeesStructure::updateOrCreate(
                ['class_level' => $r['class_level'], 'fee_type' => $r['fee_type'], 'term' => $r['term'], 'year' => $r['year']],
                $r
            );
        }
    }

    private function seedAchievements(): void
    {
        $rows = [
            // About page achievement cards
            ['title' => 'Academic Excellence',     'description' => 'Consistently ranked 1st in Kikuube and among the top 10 schools in Bunyoro Region for O-Level and A-Level performance.', 'icon' => 'fa-trophy',          'color' => 'primary', 'category' => 'school', 'sort_order' => 1],
            ['title' => 'Sports Champions',         'description' => 'Multiple district and regional championships in football and athletics.', 'icon' => 'fa-medal', 'color' => 'success', 'category' => 'school', 'sort_order' => 2],
            ['title' => 'University Scholarships',  'description' => 'Over 200 students received full scholarships to top universities on Government Sponsorships in the past decade.', 'icon' => 'fa-graduation-cap', 'color' => 'warning', 'category' => 'school', 'sort_order' => 3],
            ['title' => 'Debate Championship',      'description' => 'Winner of Inter-School Debate Championship, showcasing leadership and critical thinking skills.', 'icon' => 'fa-award', 'color' => 'info',  'category' => 'school', 'sort_order' => 4],

            // Academics page — exam results highlights
            ['title' => 'UCE Division 1 Pass Rate', 'value' => '65%',  'description' => 'Percentage of O-Level (UCE) candidates achieving Division 1.', 'icon' => 'fa-trophy',          'color' => 'success', 'category' => 'academic_results', 'sort_order' => 1],
            ['title' => 'UACE 2 Principal Passes', 'value' => '60%',  'description' => 'Percentage of A-Level (UACE) candidates achieving 2+ principal passes.', 'icon' => 'fa-medal',  'color' => 'success', 'category' => 'academic_results', 'sort_order' => 2],
            ['title' => 'School Ranking',          'value' => '#1',   'description' => 'In Kikuube District',                                          'icon' => 'fa-star',   'color' => 'warning', 'category' => 'academic_results', 'sort_order' => 3],
            ['title' => 'Top Performance in National Exams', 'description' => 'Consistently ranking among the top 10 schools in Bunyoro for both O-Level and A-Level results', 'icon' => 'fa-trophy', 'color' => 'primary', 'category' => 'academic_results', 'sort_order' => 4],
            ['title' => '85% University Admission Rate',     'description' => 'Our graduates successfully gain admission to top universities in Uganda and internationally', 'icon' => 'fa-graduation-cap', 'color' => 'success', 'category' => 'academic_results', 'sort_order' => 5],
            ['title' => 'Academic Competitions',             'description' => 'Multiple wins in debate competitions, and academic challenges', 'icon' => 'fa-medal', 'color' => 'warning', 'category' => 'academic_results', 'sort_order' => 6],
            ['title' => 'Government Scholarships',           'description' => "Most of Our Students at A' Level are always awarded University Admissions on Government Sponsorships", 'icon' => 'fa-star',  'color' => 'info',    'category' => 'academic_results', 'sort_order' => 7],
        ];
        foreach ($rows as $r) {
            Achievement::updateOrCreate(['title' => $r['title'], 'category' => $r['category']], $r);
        }
    }

    private function seedHistoryMilestones(): void
    {
        $rows = [
            ['year' => '1998', 'title' => 'The Beginning',
             'description' => 'Founded by Col Fred Baguma with a simple yet profound mission: to provide quality education that transforms lives and builds a better Uganda.',
             'sort_order' => 1],
            ['year' => '2000s', 'title' => 'Growth & Expansion',
             'description' => 'Over the years, we have expanded our facilities and strengthened our commitment to excellence. Our graduates have pursued higher education at prestigious universities locally and internationally.',
             'sort_order' => 2],
            ['year' => date('Y'), 'title' => 'Today - A Legacy of Excellence',
             'description' => 'We continue to build on our legacy, embracing innovation while maintaining our core values and commitment to holistic education.',
             'sort_order' => 3],
        ];
        foreach ($rows as $r) {
            HistoryMilestone::updateOrCreate(['year' => $r['year'], 'title' => $r['title']], $r);
        }
    }

    private function seedScholarships(): void
    {
        $rows = [
            ['name' => 'Academic Excellence Scholarship', 'description' => 'Full or half school fees reduction for students with outstanding academic performance. Renewable based on continued excellence.', 'icon' => 'fa-medal', 'color' => 'warning', 'sort_order' => 1],
            ['name' => 'Need-Based Assistance',           'description' => 'Financial support for deserving students from low-income families. Partial to full scholarships available based on demonstrated need.', 'icon' => 'fa-heart', 'color' => 'success', 'sort_order' => 2],
            ['name' => 'Sports & Talent Scholarships',    'description' => 'Special scholarships for students with exceptional talent in sports, arts, music, or other co-curricular activities.', 'icon' => 'fa-running', 'color' => 'primary', 'sort_order' => 3],
        ];
        foreach ($rows as $r) {
            Scholarship::updateOrCreate(['name' => $r['name']], $r);
        }
    }

    private function seedAcademicSubjects(): void
    {
        $oLevelCore = [
            ['name' => 'Mathematics',     'code' => 'MATH', 'category' => 'core'],
            ['name' => 'English Language','code' => 'ENG',  'category' => 'core'],
            ['name' => 'Physics',         'code' => 'PHY',  'category' => 'sciences'],
            ['name' => 'Chemistry',       'code' => 'CHM',  'category' => 'sciences'],
            ['name' => 'Biology',         'code' => 'BIO',  'category' => 'sciences'],
            ['name' => 'Agriculture',     'code' => 'AGR',  'category' => 'sciences'],
            ['name' => 'History',         'code' => 'HIS',  'category' => 'humanities'],
            ['name' => 'Geography',       'code' => 'GEO',  'category' => 'humanities'],
            ['name' => 'Religious Education','code' => 'RE','category' => 'humanities'],
            ['name' => 'Entrepreneurship','code' => 'ENT',  'category' => 'commercial'],
            ['name' => 'Runyoro',         'code' => 'RUN',  'category' => 'humanities'],
            ['name' => 'Kiswahili',       'code' => 'KIS',  'category' => 'humanities'],
            ['name' => 'ICT / Computer Studies','code' => 'ICT','category' => 'core'],
            ['name' => 'Fine Art',        'code' => 'ART',  'category' => 'elective'],
            ['name' => 'Physical Education','code'=>'PE',   'category' => 'elective'],
            ['name' => 'Technical Drawing','code'=>'TD',    'category' => 'elective'],
        ];
        $oLevelOptional = [
            ['name' => 'Luganda',        'code' => 'LUG',  'category' => 'elective'],
            ['name' => 'Home Economics', 'code' => 'HEC',  'category' => 'elective'],
            ['name' => 'Music',          'code' => 'MUS',  'category' => 'elective'],
        ];

        $i = 0;
        foreach (array_merge($oLevelCore, $oLevelOptional) as $row) {
            $i++;
            AcademicSubject::updateOrCreate(
                ['name' => $row['name'], 'level' => 'O-Level'],
                array_merge($row, ['level' => 'O-Level', 'sort_order' => $i, 'status' => 'active'])
            );
        }
    }

    private function seedAcademicCombinations(): void
    {
        $rows = [
            // Sciences
            ['code' => 'PCM', 'subjects' => 'Physics, Chemistry, Mathematics',              'faculty' => 'sciences',  'sort_order' => 1],
            ['code' => 'PEM', 'subjects' => 'Physics, Economics, Mathematics',              'faculty' => 'sciences',  'sort_order' => 2],
            ['code' => 'PCB', 'subjects' => 'Physics, Chemistry, Biology',                  'faculty' => 'sciences',  'sort_order' => 3],
            ['code' => 'BCM', 'subjects' => 'Biology, Chemistry, Mathematics',              'faculty' => 'sciences',  'sort_order' => 4],
            ['code' => 'BAG', 'subjects' => 'Biology, Agriculture, Geography',              'faculty' => 'sciences',  'sort_order' => 5],
            // Arts
            ['code' => 'HEG', 'subjects' => 'History, Economics, Geography',                'faculty' => 'arts',      'sort_order' => 6],
            ['code' => 'HLD', 'subjects' => 'History, Literature, Divinity',                'faculty' => 'arts',      'sort_order' => 7],
            ['code' => 'LEK', 'subjects' => 'Literature, Economics, Kinyarwanda',           'faculty' => 'arts',      'sort_order' => 8],
            // Commercial
            ['code' => 'MEG', 'subjects' => 'Mathematics, Economics, Geography',            'faculty' => 'commercial','sort_order' => 9],
            ['code' => 'MEA', 'subjects' => 'Math, Economics, Agriculture',                 'faculty' => 'commercial','sort_order' => 10],
            ['code' => 'GEA', 'subjects' => 'Geography, Economics, Agriculture/Art',        'faculty' => 'commercial','sort_order' => 11],
        ];
        foreach ($rows as $r) {
            AcademicCombination::updateOrCreate(['code' => $r['code']], $r);
        }
    }
}
