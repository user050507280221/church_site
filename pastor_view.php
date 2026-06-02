<?php
include 'config.php';
include 'header.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$res = $mysqli->query("
    SELECT p.*, c.name AS church_name, c.location AS church_location
    FROM pastors p
    LEFT JOIN churches c ON c.pastor = p.name
    WHERE p.id=$id
");
  
if($res->num_rows == 0){
    echo "<section class='error-hero'><h1>Pastor not found.</h1><a href='pastor.php'>Back to List</a></section>";
    include 'footer.php';
    exit;
}
$row = $res->fetch_assoc();

$church_name = $row['church_name'] ?? '';
?>

<!-- ABA STYLE HERO -->
<section class="pastor-view-hero">
    <div class="hero-inner">
        <a href="pastor.php" class="aba-back-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
            </svg>
            Back to Pastors
        </a>
        
        <div class="profile-header-flex">
            <!-- Image / Placeholder Column -->
            <div class="profile-image-wrapper">
                <?php if (!empty($row['image']) && file_exists($row['image'])): ?>
                    <img src="<?php echo $row['image']; ?>" alt="Pastor Profile">
                <?php else: ?>
                    <div class="profile-image-placeholder">
                        <span><?php echo strtoupper(substr($row['name'], 0, 1)); ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Name and Title Column -->
            <div class="profile-title-wrapper">
                <span class="category-eyebrow">CLERGY PROFILE</span>
                <h1><?php echo htmlspecialchars($row['name']); ?></h1>
                <?php if($church_name): ?>
                    <p class="pastor-of">Pastor of <strong><?php echo htmlspecialchars($church_name); ?></strong></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- PROFILE DETAILS -->
<section class="pastor-details-section">
    <div class="aba-container">
        <div class="details-grid">
            
            <!-- Left Column: Bio & Social -->
            <div class="details-main">
                <h2 class="section-label">Biography</h2>
                <div class="bio-content">
                    <p><?php echo nl2br(htmlspecialchars($row['bio'])); ?></p>
                </div>

                <div class="contact-chips">
                    <?php if(!empty($row['email'])): ?>
                        <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="chip">📧 <?php echo htmlspecialchars($row['email']); ?></a>
                    <?php endif; ?>
                    <?php if(!empty($row['phone'])): ?>
                        <span class="chip">📞 <?php echo htmlspecialchars($row['phone']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="social-links-row">
                    <?php if(!empty($row['facebook'])): ?>
                        <a href="<?php echo htmlspecialchars($row['facebook']); ?>" target="_blank" class="social-btn fb">Facebook</a>
                    <?php endif; ?>
                    <?php if(!empty($row['instagram'])): ?>
                        <a href="<?php echo htmlspecialchars($row['instagram']); ?>" target="_blank" class="social-btn ig">Instagram</a>
                    <?php endif; ?>
                    <?php if(!empty($row['website'])): ?>
                        <a href="<?php echo htmlspecialchars($row['website']); ?>" target="_blank" class="social-btn web">Official Website</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column: Sermons & Latest Media -->
            <div class="details-sidebar">
                <?php if(!empty($row['sermon_link'])): ?>
                    <div class="featured-media">
                        <h2 class="section-label">Latest Media</h2>
                        <a href="<?php echo htmlspecialchars($row['sermon_link']); ?>" target="_blank" class="aba-btn-primary">
                            Watch Latest Sermon
                        </a>
                    </div>
                <?php endif; ?>

                <div class="sermons-list-wrapper">
                    <h2 class="section-label">Publications & Sermons</h2>
                    <div class="sermons-stack">
                        <?php
                        $sid = $row['id'];
                        $sermons = $mysqli->query("SELECT * FROM sermons WHERE pastor_id=$sid ORDER BY id DESC");
                        if($sermons->num_rows > 0){
                            while($s = $sermons->fetch_assoc()){
                                echo "<div class='sermon-row'>
                                        <div class='sermon-info'>
                                            <span class='sermon-date'>PDF RESOURCE</span>
                                            <p>".htmlspecialchars($s['title'])."</p>
                                        </div>
                                        <a href='".$s['file']."' target='_blank' class='aba-download-link'>Download</a>
                                      </div>";
                            }
                        } else {
                            echo "<p class='empty-note'>No sermons uploaded yet.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>