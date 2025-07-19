<?php
// News & Events page for Makerere Competent High School
$page_title = 'News & Events';
$page_description = 'Stay updated with the latest news, events, and announcements from Makerere Competent High School.';
$page_keywords = 'school news, events, announcements, Makerere Competent High School, school activities';

include 'includes/header.php';

// Pagination settings
$perPage = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

// Get single news article if ID is provided
$singleNews = null;
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $singleNews = getNewsById($_GET['id']);
}

// Get news articles for listing
if (!$singleNews) {
    try {
        // Get total count for pagination
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM news WHERE status = 'published'");
        $stmt->execute();
        $totalNews = $stmt->fetchColumn();
        $totalPages = ceil($totalNews / $perPage);
        
        // Get news articles for current page
        $stmt = $pdo->prepare("SELECT * FROM news WHERE status = 'published' ORDER BY created_at DESC LIMIT ? OFFSET ?");
        $stmt->execute([$perPage, $offset]);
        $newsArticles = $stmt->fetchAll();
    } catch (PDOException $e) {
        $newsArticles = [];
        $totalPages = 0;
    }
}
?>

<?php if ($singleNews): ?>
<!-- Single News Article -->
<div class="page-header">
    <div class="container">
        <h1><?php echo htmlspecialchars($singleNews['title']); ?></h1>
        <p>
            <i class="fas fa-calendar"></i> <?php echo formatDate($singleNews['created_at']); ?>
            <?php if (!empty($singleNews['author'])): ?>
                | <i class="fas fa-user"></i> By <?php echo htmlspecialchars($singleNews['author']); ?>
            <?php endif; ?>
        </p>
    </div>
</div>

<section class="page-content">
    <div class="container">
        <div class="news-single">
            <?php if (!empty($singleNews['image'])): ?>
            <div class="news-featured-image" data-aos="fade-up">
                <img src="<?php echo SITE_URL; ?>/<?php echo UPLOAD_PATH . $singleNews['image']; ?>" alt="<?php echo htmlspecialchars($singleNews['title']); ?>" style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 10px; margin-bottom: 2rem;">
            </div>
            <?php endif; ?>
            
            <div class="news-content" data-aos="fade-up" data-aos-delay="200">
                <div style="font-size: 1.1rem; line-height: 1.8; color: #333;">
                    <?php echo nl2br(makeLinksClickable(htmlspecialchars($singleNews['content']))); ?>
                </div>
            </div>
            
            <div class="news-meta" style="border-top: 1px solid #eee; padding-top: 2rem; margin-top: 3rem;" data-aos="fade-up" data-aos-delay="300">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <strong>Published:</strong> <?php echo formatDate($singleNews['created_at'], 'F j, Y \a\t g:i A'); ?>
                    </div>
                    <div>
                        <a href="news.php" class="btn">← Back to All News</a>
                    </div>
                </div>
                
                <!-- Social sharing buttons -->
                <div style="margin-top: 1.5rem;">
                    <strong>Share this article:</strong><br>
                    <div style="margin-top: 10px;">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(SITE_URL . '/news.php?id=' . $singleNews['id']); ?>" target="_blank" style="display: inline-block; background: #3b5998; color: white; padding: 8px 12px; border-radius: 4px; text-decoration: none; margin-right: 10px;">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(SITE_URL . '/news.php?id=' . $singleNews['id']); ?>&text=<?php echo urlencode($singleNews['title']); ?>" target="_blank" style="display: inline-block; background: #1da1f2; color: white; padding: 8px 12px; border-radius: 4px; text-decoration: none; margin-right: 10px;">
                            <i class="fab fa-twitter"></i> Twitter
                        </a>
                        <a href="mailto:?subject=<?php echo urlencode($singleNews['title']); ?>&body=<?php echo urlencode('Check out this article from ' . SITE_NAME . ': ' . SITE_URL . '/news.php?id=' . $singleNews['id']); ?>" style="display: inline-block; background: #333; color: white; padding: 8px 12px; border-radius: 4px; text-decoration: none;">
                            <i class="fas fa-envelope"></i> Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related News -->
        <?php
        try {
            $stmt = $pdo->prepare("SELECT * FROM news WHERE status = 'published' AND id != ? ORDER BY created_at DESC LIMIT 3");
            $stmt->execute([$singleNews['id']]);
            $relatedNews = $stmt->fetchAll();
        } catch (PDOException $e) {
            $relatedNews = [];
        }
        ?>
        
        <?php if (!empty($relatedNews)): ?>
        <section style="margin-top: 4rem;">
            <h3 style="color: #1a472a; margin-bottom: 2rem; text-align: center;">Related News</h3>
            <div class="news-grid">
                <?php foreach ($relatedNews as $news): ?>
                <div class="news-card" data-aos="fade-up">
                    <?php if (!empty($news['image'])): ?>
                    <img src="<?php echo SITE_URL; ?>/<?php echo UPLOAD_PATH . $news['image']; ?>" alt="<?php echo htmlspecialchars($news['title']); ?>">
                    <?php else: ?>
                    <img src="<?php echo SITE_URL; ?>/assets/images/default-news.jpg" alt="<?php echo htmlspecialchars($news['title']); ?>">
                    <?php endif; ?>
                    
                    <div class="news-card-content">
                        <h3><?php echo htmlspecialchars($news['title']); ?></h3>
                        <div class="news-date">
                            <i class="fas fa-calendar"></i>
                            <?php echo formatDate($news['created_at']); ?>
                        </div>
                        <p><?php echo truncateText(strip_tags($news['content']), 120); ?></p>
                        <a href="news.php?id=<?php echo $news['id']; ?>" class="btn">Read More</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
</section>

<?php else: ?>
<!-- News Listing Page -->
<div class="page-header">
    <div class="container">
        <h1>News & Events</h1>
        <p>Stay updated with the latest happenings at Makerere Competent High School</p>
    </div>
</div>

<section class="page-content">
    <div class="container">
        <?php if (!empty($newsArticles)): ?>
        <div class="news-grid">
            <?php foreach ($newsArticles as $index => $news): ?>
            <div class="news-card" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                <?php if (!empty($news['image'])): ?>
                <img src="<?php echo SITE_URL; ?>/<?php echo UPLOAD_PATH . $news['image']; ?>" alt="<?php echo htmlspecialchars($news['title']); ?>">
                <?php else: ?>
                <img src="<?php echo SITE_URL; ?>/assets/images/default-news.jpg" alt="<?php echo htmlspecialchars($news['title']); ?>">
                <?php endif; ?>
                
                <div class="news-card-content">
                    <h3><?php echo htmlspecialchars($news['title']); ?></h3>
                    <div class="news-date">
                        <i class="fas fa-calendar"></i>
                        <?php echo formatDate($news['created_at']); ?>
                        <?php if (!empty($news['author'])): ?>
                            | <i class="fas fa-user"></i> <?php echo htmlspecialchars($news['author']); ?>
                        <?php endif; ?>
                    </div>
                    <p><?php echo truncateText(strip_tags($news['content']), 150); ?></p>
                    <a href="news.php?id=<?php echo $news['id']; ?>" class="btn">Read More</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div style="margin-top: 3rem;">
            <?php echo generatePagination($page, $totalPages, 'news.php'); ?>
        </div>
        <?php endif; ?>
        
        <?php else: ?>
        <div class="text-center" style="padding: 4rem 0;">
            <i class="fas fa-newspaper" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
            <h3 style="color: #666; margin-bottom: 1rem;">No News Available</h3>
            <p style="color: #888;">Check back later for the latest news and updates from our school.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Newsletter Subscription -->
<section class="section bg-green">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2 style="color: white;">Stay Updated</h2>
            <p style="color: rgba(255,255,255,0.9);">Subscribe to our newsletter to receive the latest news and announcements directly in your inbox.</p>
        </div>
        
        <div class="text-center">
            <form class="newsletter-form" style="display: inline-flex; gap: 10px; max-width: 400px;">
                <input type="email" placeholder="Enter your email address" required style="flex: 1; padding: 12px; border: none; border-radius: 5px;">
                <button type="submit" class="btn">Subscribe</button>
            </form>
        </div>
    </div>
</section>
<?php endif; ?>

<style>
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5px;
    margin: 2rem 0;
}

.pagination-btn {
    display: inline-block;
    padding: 8px 12px;
    background: white;
    color: #1a472a;
    text-decoration: none;
    border: 1px solid #ddd;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.pagination-btn:hover,
.pagination-btn.active {
    background: #1a472a;
    color: white;
    border-color: #1a472a;
}

.pagination-dots {
    padding: 8px 4px;
    color: #666;
}

.news-single {
    max-width: 800px;
    margin: 0 auto;
}

@media (max-width: 768px) {
    .pagination {
        flex-wrap: wrap;
        gap: 3px;
    }
    
    .pagination-btn {
        padding: 6px 8px;
        font-size: 0.9rem;
    }
    
    .newsletter-form {
        flex-direction: column !important;
        width: 100% !important;
        max-width: 300px !important;
    }
    
    .newsletter-form input {
        margin-bottom: 10px;
    }
}
</style>

<?php include 'includes/footer.php'; ?>