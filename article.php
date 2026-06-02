<?php
include 'header.php';
include 'config.php';

// Get the article/event ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $mysqli->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();
?>

<!-- ARTICLE HERO SECTION -->
<section class="article-hero">
    <div class="article-hero-inner">
        <a href="index.php" class="aba-back-link">
            <i class="fas fa-arrow-left"></i> Back to Latest Articles
        </a>
        
        <?php if($event): ?>
            <span class="category-eyebrow">Ecclesiastical Events</span>
            <h1><?php echo htmlspecialchars($event['title']); ?></h1>
            <div class="article-meta">
                <span class="meta-date">
                    <i class="far fa-calendar-alt"></i> 
                    <?php echo date("F j, Y", strtotime($event['date'])); ?>
                </span>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- MAIN CONTENT SECTION -->
<section class="article-body-container">
    <div class="article-content-wrapper">
        <?php if($event): ?>
            <!-- Featured Image -->
            <?php if(!empty($event['image'])): ?>
                <div class="article-featured-image">
                    <img src="<?php echo $event['image']; ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">
                </div>
            <?php endif; ?>

            <!-- Article Text -->
            <div class="article-narrative">
                <?php echo nl2br(htmlspecialchars($event['description'])); ?>
            </div>

            <!-- Footer Navigation -->
            <div class="article-footer-nav">
                <a href="index.php" class="aba-btn-outline">Return to Directory Index</a>
            </div>

        <?php else: ?>
            <div class="article-not-found">
                <i class="fas fa-exclamation-circle"></i>
                <h2>Article Not Found</h2>
                <p>The ecclesiastical record you are looking for may have been moved or archived.</p>
                <a href="index.php" class="aba-back-link">Return to Home</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>