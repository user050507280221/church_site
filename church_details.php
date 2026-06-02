<?php 
include 'config.php';
include 'header.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $mysqli->prepare("SELECT * FROM churches WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$church = $result->fetch_assoc();

if (!$church) {
  echo "<section class='error-hero'><h1>Church not found.</h1><a href='churches.php'>Back to List</a></section>";
  include 'footer.php';
  exit;
}
?>

<!-- ABA STYLE HERO -->
<section class="church-view-hero">
    <div class="hero-inner">
        <a href="churches.php" class="aba-back-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
            </svg>
            Back to Churches
        </a>
        
        <div class="profile-header-flex">
            <div class="profile-image-wrapper">
                <?php if(!empty($church['image'])): ?>
                    <img src="<?php echo $church['image']; ?>" alt="<?php echo htmlspecialchars($church['name']); ?>">
                <?php else: ?>
                    <div class="profile-image-placeholder">
                        <span><?php echo strtoupper(substr($church['name'], 0, 1)); ?></span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="profile-title-wrapper">
                <span class="category-eyebrow">ECCLESIASTICAL DIRECTORY</span>
                <h1><?php echo htmlspecialchars($church['name']); ?></h1>
                <?php if(!empty($church['mother_church'])): ?>
                    <p class="mother-church-text">
                         Mother Church: <strong><?php echo htmlspecialchars($church['mother_church']); ?></strong>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- MAIN DETAILS -->
<section class="church-details-body">
    <div class="aba-container">
        <div class="details-grid">
            
            <!-- Left Side: Narrative & Info -->
            <div class="details-main">
                <h2 class="section-label">About this Church</h2>
                <?php if(!empty($church['description'])): ?>
                    <div class="church-description-text">
                        <?php echo nl2br(htmlspecialchars($church['description'])); ?>
                    </div>
                <?php else: ?>
                    <p class="empty-note">No description available for this congregation.</p>
                <?php endif; ?>

                <div class="info-stamps">
                    <?php if(!empty($church['date_organized'])): ?>
                        <div class="stamp">
                            <span class="stamp-label">Organized On</span>
                            <span class="stamp-value"><?php echo date("F j, Y", strtotime($church['date_organized'])); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($church['pastor'])): ?>
                        <div class="stamp">
                            <span class="stamp-label">Presiding Pastor</span>
                            <span class="stamp-value"><?php echo htmlspecialchars($church['pastor']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Side: Contact & Location -->
            <div class="details-sidebar">
                <div class="sidebar-card">
                    <h2 class="section-label">Connection</h2>
                    
                    <?php if(!empty($church['location'])): ?>
                        <div class="sidebar-item">
                            <i class="bi bi-geo-alt-fill"></i>
                            <div>
                                <strong>Location</strong><br>
                                <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($church['location']); ?>" target="_blank">
                                    <?php echo htmlspecialchars($church['location']); ?>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="sidebar-contact-list">
                        <?php if(!empty($church['contact'])): ?>
                            <div class="sidebar-item">
                                <i class="bi bi-telephone-fill"></i>
                                <span>
                                    <?php 
                                        $contact = preg_replace('/\D/', '', $church['contact']); 
                                        echo htmlspecialchars(preg_replace('/^(\d{4})(\d{3})(\d{4})$/', '$1-$2-$3', $contact));
                                    ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <?php if(!empty($church['email'])): ?>
                            <a href="mailto:<?php echo htmlspecialchars($church['email']); ?>" class="sidebar-item link">
                                <i class="bi bi-envelope-fill"></i>
                                <span><?php echo htmlspecialchars($church['email']); ?></span>
                            </a>
                        <?php endif; ?>

                        <div class="social-grid">
                            <?php if(!empty($church['facebook'])): ?>
                                <a href="<?php echo htmlspecialchars($church['facebook']); ?>" target="_blank" title="Facebook"><i class="bi bi-facebook"></i></a>
                            <?php endif; ?>
                            <?php if(!empty($church['instagram'])): ?>
                                <a href="<?php echo htmlspecialchars($church['instagram']); ?>" target="_blank" title="Instagram"><i class="bi bi-instagram"></i></a>
                            <?php endif; ?>
                            <?php if(!empty($church['website'])): ?>
                                <a href="<?php echo htmlspecialchars($church['website']); ?>" target="_blank" title="Website"><i class="bi bi-globe"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include 'footer.php'; ?>