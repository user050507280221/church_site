<?php
include 'config.php';
$page = 'pastor';
include 'header.php';

// Original Search Logic
$search = isset($_GET['search']) ? $_GET['search'] : '';
$stmt = $mysqli->prepare("SELECT * FROM pastors WHERE name LIKE ? ORDER BY id DESC");
$likeSearch = "%$search%";
$stmt->bind_param("s", $likeSearch);
$stmt->execute();
$res = $stmt->get_result();
?>

<!-- ABA STYLE HERO WITH GRADIENT -->
<section class="pastors-hero">
    <div class="hero-inner">
        <span class="category-eyebrow">Missionary and Local Pastor</span>
        <h1>Pastors List</h1>
        <p class="intro">Meet the dedicated leaders of our community who guide us in faith and service.</p>
        
        <!-- Expanding Search Bar -->
        <div class="search-wrapper">
            <form method="GET" class="aba-search-form">
                <div class="search-input-group">
                    <input type="text" name="search" id="aba-search-input" 
                           placeholder="Search pastor by name..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="search-submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- 4-COLUMN PROFESSIONAL GRID -->
<section class="pastors-grid-container">
    <div class="pastor-grid">
        <?php if($res->num_rows > 0): ?>
            <?php while($row = $res->fetch_assoc()): 
                $img = !empty($row['image']) ? $row['image'] : 'images/placeholder.png';
            ?>
                <article class="pastor-card">
                    <div class="image-container">
                        <?php if(!empty($row['image']) && file_exists($row['image'])): ?>
                            <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                        <?php else: ?>
                            <!-- Professional Placeholder -->
                            <div class="aba-placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-content">
                        <span class="role-tag">PASTOR</span>
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p class="bio-text"><?php echo htmlspecialchars($row['bio']); ?></p>
                        <a href="pastor_view.php?id=<?php echo $row['id']; ?>" class="view-btn">View Profile</a>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <p>No pastors found for "<?php echo htmlspecialchars($search); ?>".</p>
                <a href="pastors.php" class="reset-link">View All Pastors</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>