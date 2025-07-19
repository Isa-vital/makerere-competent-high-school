<?php
// Gallery page for Makerere Competent High School
$page_title = 'Photo Gallery';
$page_description = 'Explore life at Makerere Competent High School through our photo gallery showcasing academics, sports, events, and facilities.';
$page_keywords = 'school gallery, photos, events, sports, academics, Makerere Competent High School, student life';

include 'includes/header.php';

// Get gallery images
$category = isset($_GET['category']) ? sanitizeInput($_GET['category']) : null;
$galleryImages = getGalleryImages($category);

// Get unique categories for filter
try {
    $stmt = $pdo->query("SELECT DISTINCT category FROM gallery WHERE status = 'active' ORDER BY category");
    $categories = $stmt->fetchAll();
} catch (PDOException $e) {
    $categories = [];
}
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1>Photo Gallery</h1>
        <p>Discover life at Makerere Competent High School through our lens</p>
    </div>
</div>

<!-- Main Content -->
<section class="page-content">
    <div class="container">
        <!-- Gallery Filters -->
        <div class="gallery-filters" style="text-align: center; margin-bottom: 3rem;" data-aos="fade-up">
            <h3 style="color: #1a472a; margin-bottom: 1.5rem;">Explore Our Gallery</h3>
            <div class="filter-buttons" style="display: flex; justify-content: center; flex-wrap: wrap; gap: 10px;">
                <button class="gallery-filter-btn <?php echo !$category ? 'active' : ''; ?>" data-filter="all" onclick="filterGallery('all')">
                    <i class="fas fa-th"></i> All Photos
                </button>
                <button class="gallery-filter-btn <?php echo $category == 'academics' ? 'active' : ''; ?>" data-filter="academics" onclick="filterGallery('academics')">
                    <i class="fas fa-graduation-cap"></i> Academics
                </button>
                <button class="gallery-filter-btn <?php echo $category == 'sports' ? 'active' : ''; ?>" data-filter="sports" onclick="filterGallery('sports')">
                    <i class="fas fa-running"></i> Sports
                </button>
                <button class="gallery-filter-btn <?php echo $category == 'events' ? 'active' : ''; ?>" data-filter="events" onclick="filterGallery('events')">
                    <i class="fas fa-calendar-alt"></i> Events
                </button>
                <button class="gallery-filter-btn <?php echo $category == 'facilities' ? 'active' : ''; ?>" data-filter="facilities" onclick="filterGallery('facilities')">
                    <i class="fas fa-building"></i> Facilities
                </button>
                <button class="gallery-filter-btn <?php echo $category == 'students' ? 'active' : ''; ?>" data-filter="students" onclick="filterGallery('students')">
                    <i class="fas fa-users"></i> Student Life
                </button>
            </div>
        </div>

        <!-- Gallery Grid -->
        <?php if (!empty($galleryImages)): ?>
        <div class="gallery-grid" id="gallery-container">
            <?php foreach ($galleryImages as $index => $image): ?>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="<?php echo ($index + 1) * 50; ?>" 
                 data-category="<?php echo $image['category']; ?>">
                <img src="<?php echo SITE_URL; ?>/assets/images/<?php echo $image['image_path']; ?>" 
                     alt="<?php echo htmlspecialchars($image['title']); ?>"
                     loading="lazy"
                     onerror="this.src='<?php echo SITE_URL; ?>/assets/images/placeholder-facility.jpg'">
                <div class="gallery-overlay">
                    <h4><?php echo htmlspecialchars($image['title']); ?></h4>
                    <?php if (!empty($image['description'])): ?>
                        <p><?php echo htmlspecialchars(truncateText($image['description'], 80)); ?></p>
                    <?php endif; ?>
                    <div class="gallery-meta">
                        <span class="category-badge category-<?php echo $image['category']; ?>">
                            <?php echo ucfirst($image['category']); ?>
                        </span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Load More Button -->
        <div class="text-center" style="margin-top: 3rem;" data-aos="fade-up">
            <button id="load-more-btn" class="btn" onclick="loadMoreImages()" style="display: none;">
                <i class="fas fa-plus"></i> Load More Photos
            </button>
        </div>
        
        <?php else: ?>
        <!-- Empty State -->
        <div class="empty-gallery" style="text-align: center; padding: 4rem 0;" data-aos="fade-up">
            <i class="fas fa-images" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
            <h3 style="color: #666; margin-bottom: 1rem;">No Photos Available</h3>
            <p style="color: #888;">
                <?php if ($category): ?>
                    No photos found in the <?php echo ucfirst($category); ?> category. 
                    <a href="gallery.php" style="color: #1a472a;">View all photos</a>
                <?php else: ?>
                    Photos will be added soon. Check back later to see our school in action!
                <?php endif; ?>
            </p>
        </div>
        <?php endif; ?>

        <!-- Gallery Stats -->
        <div class="gallery-stats" style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-top: 4rem;" data-aos="fade-up">
            <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 2rem; text-align: center;">
                <?php
                // Get stats for each category
                $categoryStats = [];
                foreach ($categories as $cat) {
                    try {
                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM gallery WHERE category = ? AND status = 'active'");
                        $stmt->execute([$cat['category']]);
                        $categoryStats[$cat['category']] = $stmt->fetchColumn();
                    } catch (PDOException $e) {
                        $categoryStats[$cat['category']] = 0;
                    }
                }
                
                $categoryIcons = [
                    'academics' => 'fas fa-graduation-cap',
                    'sports' => 'fas fa-running',
                    'events' => 'fas fa-calendar-alt',
                    'facilities' => 'fas fa-building',
                    'students' => 'fas fa-users',
                    'other' => 'fas fa-image'
                ];
                
                foreach ($categoryStats as $category => $count):
                    if ($count > 0):
                ?>
                <div class="stat-item">
                    <i class="<?php echo $categoryIcons[$category] ?? 'fas fa-image'; ?>" style="font-size: 2rem; color: #1a472a; margin-bottom: 0.5rem;"></i>
                    <h4 style="color: #1a472a; margin-bottom: 0.5rem;"><?php echo $count; ?></h4>
                    <p style="color: #666; font-size: 0.9rem;"><?php echo ucfirst($category); ?> Photos</p>
                </div>
                <?php 
                    endif;
                endforeach; 
                ?>
            </div>
        </div>

        <!-- Featured Highlights -->
        <div class="gallery-highlights" style="margin-top: 4rem;" data-aos="fade-up">
            <h3 style="color: #1a472a; text-align: center; margin-bottom: 3rem;">Gallery Highlights</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <div class="highlight-card" style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center;">
                    <i class="fas fa-microscope" style="font-size: 3rem; color: #1a472a; margin-bottom: 1rem;"></i>
                    <h4 style="color: #1a472a; margin-bottom: 1rem;">Modern Laboratories</h4>
                    <p style="color: #666; line-height: 1.6;">State-of-the-art science laboratories equipped with the latest technology for hands-on learning experiences.</p>
                    <button class="btn" onclick="filterGallery('facilities')" style="margin-top: 1rem; padding: 8px 16px; font-size: 0.9rem;">
                        View Lab Photos
                    </button>
                </div>
                
                <div class="highlight-card" style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center;">
                    <i class="fas fa-trophy" style="font-size: 3rem; color: #1a472a; margin-bottom: 1rem;"></i>
                    <h4 style="color: #1a472a; margin-bottom: 1rem;">Championship Moments</h4>
                    <p style="color: #666; line-height: 1.6;">Celebrating our students' achievements in sports, academics, and various competitions throughout the years.</p>
                    <button class="btn" onclick="filterGallery('sports')" style="margin-top: 1rem; padding: 8px 16px; font-size: 0.9rem;">
                        View Sports Photos
                    </button>
                </div>
                
                <div class="highlight-card" style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center;">
                    <i class="fas fa-graduation-cap" style="font-size: 3rem; color: #1a472a; margin-bottom: 1rem;"></i>
                    <h4 style="color: #1a472a; margin-bottom: 1rem;">Academic Excellence</h4>
                    <p style="color: #666; line-height: 1.6;">Classroom moments, graduations, and academic ceremonies showcasing our commitment to educational excellence.</p>
                    <button class="btn" onclick="filterGallery('academics')" style="margin-top: 1rem; padding: 8px 16px; font-size: 0.9rem;">
                        View Academic Photos
                    </button>
                </div>
            </div>
        </div>

        <!-- School Virtual Tour -->
        <div class="virtual-tour" style="background: linear-gradient(135deg, #1a472a 0%, #2d5a3d 100%); color: white; padding: 4rem 0; margin-top: 4rem; border-radius: 15px; text-align: center;" data-aos="fade-up">
            <div style="max-width: 600px; margin: 0 auto; padding: 0 2rem;">
                <i class="fas fa-video" style="font-size: 4rem; margin-bottom: 1.5rem; opacity: 0.9;"></i>
                <h3 style="margin-bottom: 1.5rem; font-size: 2rem;">Take a Virtual Tour</h3>
                <p style="font-size: 1.1rem; line-height: 1.6; margin-bottom: 2rem; opacity: 0.9;">
                    Can't visit us in person? Take our interactive virtual tour to explore our facilities, 
                    classrooms, laboratories, and campus grounds from the comfort of your home.
                </p>
                <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
                    <a href="#" class="btn" style="background: white; color: #1a472a;" onclick="startVirtualTour()">
                        <i class="fas fa-play"></i> Start Virtual Tour
                    </a>
                    <a href="contact.php" class="btn btn-secondary">
                        <i class="fas fa-calendar"></i> Schedule Visit
                    </a>
                </div>
            </div>
        </div>

        <!-- Photo Submission -->
        <div class="photo-submission" style="background: #f8f9fa; padding: 3rem; border-radius: 10px; margin-top: 4rem; text-align: center;" data-aos="fade-up">
            <h3 style="color: #1a472a; margin-bottom: 1.5rem;">Share Your Memories</h3>
            <p style="color: #666; line-height: 1.6; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
                Are you a student, parent, or alumni with great photos of school events? 
                We'd love to feature your memories in our gallery! Send us your best shots.
            </p>
            <a href="contact.php?subject=Photo%20Submission" class="btn">
                <i class="fas fa-camera"></i> Submit Your Photos
            </a>
        </div>
    </div>
</section>

<!-- Additional CSS for Gallery -->
<style>
.gallery-filter-btn {
    background: white;
    border: 2px solid #1a472a;
    color: #1a472a;
    padding: 10px 20px;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.gallery-filter-btn:hover,
.gallery-filter-btn.active {
    background: #1a472a;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(26, 71, 42, 0.3);
}

.gallery-item {
    position: relative;
    overflow: hidden;
    border-radius: 12px;
    cursor: pointer;
    transition: transform 0.3s ease;
    background: white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.gallery-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
}

.gallery-item img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-item:hover img {
    transform: scale(1.05);
}

.gallery-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(26, 71, 42, 0.95));
    color: white;
    padding: 1.5rem;
    transform: translateY(100%);
    transition: transform 0.3s ease;
}

.gallery-item:hover .gallery-overlay {
    transform: translateY(0);
}

.gallery-overlay h4 {
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.gallery-overlay p {
    margin: 0 0 1rem 0;
    font-size: 0.9rem;
    opacity: 0.9;
    line-height: 1.4;
}

.gallery-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.category-badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.category-academics { background-color: #3498db; }
.category-sports { background-color: #e74c3c; }
.category-events { background-color: #f39c12; }
.category-facilities { background-color: #9b59b6; }
.category-students { background-color: #2ecc71; }
.category-other { background-color: #95a5a6; }

.highlight-card {
    transition: transform 0.3s ease;
}

.highlight-card:hover {
    transform: translateY(-5px);
}

@media (max-width: 768px) {
    .filter-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .gallery-filter-btn {
        width: 200px;
        justify-content: center;
    }
    
    .gallery-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .gallery-item img {
        height: 200px;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 1rem !important;
    }
    
    .virtual-tour {
        margin-left: -20px;
        margin-right: -20px;
        border-radius: 0;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr !important;
    }
}

/* Loading animation */
.gallery-item.loading {
    opacity: 0.5;
}

.gallery-item.loading::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 30px;
    height: 30px;
    margin: -15px 0 0 -15px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #1a472a;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<!-- JavaScript for Gallery Functionality -->
<script>
let currentFilter = 'all';
let isLoading = false;

function filterGallery(category) {
    // Update URL without reload
    const url = new URL(window.location);
    if (category === 'all') {
        url.searchParams.delete('category');
    } else {
        url.searchParams.set('category', category);
    }
    window.history.pushState({}, '', url);
    
    // Update active button
    document.querySelectorAll('.gallery-filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector(`[data-filter="${category}"]`).classList.add('active');
    
    // Filter items
    const items = document.querySelectorAll('.gallery-item');
    items.forEach(item => {
        const itemCategory = item.getAttribute('data-category');
        if (category === 'all' || itemCategory === category) {
            item.style.display = 'block';
            item.style.animation = 'fadeIn 0.5s ease';
        } else {
            item.style.display = 'none';
        }
    });
    
    currentFilter = category;
    
    // Update gallery stats visibility
    updateStatsVisibility();
}

function updateStatsVisibility() {
    const statItems = document.querySelectorAll('.stat-item');
    statItems.forEach(item => {
        const categoryText = item.querySelector('p').textContent.toLowerCase();
        const category = categoryText.split(' ')[0];
        
        if (currentFilter === 'all' || category === currentFilter) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function loadMoreImages() {
    if (isLoading) return;
    
    isLoading = true;
    const loadMoreBtn = document.getElementById('load-more-btn');
    const originalText = loadMoreBtn.innerHTML;
    
    loadMoreBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    loadMoreBtn.disabled = true;
    
    // Simulate loading more images (replace with actual AJAX call)
    setTimeout(() => {
        // Reset button
        loadMoreBtn.innerHTML = originalText;
        loadMoreBtn.disabled = false;
        isLoading = false;
        
        // Hide button if no more images
        loadMoreBtn.style.display = 'none';
    }, 2000);
}

function startVirtualTour() {
    // This would typically open a virtual tour application or embed
    alert('Virtual tour feature coming soon! Contact us to schedule an in-person visit.');
}

// Initialize gallery
document.addEventListener('DOMContentLoaded', function() {
    // Set initial filter based on URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const category = urlParams.get('category') || 'all';
    
    if (category !== 'all') {
        filterGallery(category);
    }
    
    // Add click handlers for gallery items (lightbox functionality is in gallery.js)
    const galleryItems = document.querySelectorAll('.gallery-item');
    galleryItems.forEach((item, index) => {
        item.addEventListener('click', function() {
            if (typeof openLightbox === 'function') {
                openLightbox(index);
            }
        });
    });
    
    // Lazy loading for images
    const images = document.querySelectorAll('.gallery-item img[loading="lazy"]');
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.classList.add('loaded');
                    observer.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    }
});

// Add fade-in animation keyframe
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
`;
document.head.appendChild(style);
</script>

<?php include 'includes/footer.php'; ?>