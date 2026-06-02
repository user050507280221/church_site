<?php 
include 'config.php';
include 'header.php'; 
?> 

<!-- CENTERED LEADERSHIP HERO -->
<section class="leader-hero-centered">
    <div class="hero-inner-centered">
        <div class="leader-profile-img">
            <img src="images/pastor1.jpg" alt="Bishop Shinji Amari Ph. D">
        </div>
        <span class="category-eyebrow">Head Pastor</span>
        </span>
        <h1>Bishop Shinji Amari Ph. D</h1>
        <p class="subtitle">Senior Pastor of Abiko Baptist Church & President of MBTS Japan</p>
    </div>
</section>

<div class="container-centered">
    <!-- BIOGRAPHY SECTION -->
    <section class="bio-section-centered">
        <h2 class="section-title">Biography</h2>
        <div class="bio-text">
            <p>
                Bishop Shinji Amari is a servant of God whose ministry has greatly impacted Japan and beyond. 
                He serves as the Senior Pastor of Abiko Baptist Church in Japan, faithfully guiding his 
                congregation with wisdom and dedication. In addition, he is the President of the Missionary 
                Baptist Theological School, where he has trained and equipped many future ministers and 
                leaders for the work of the Gospel.
            </p>
            <p>
                Bishop Amari has published numerous books, including <em>Bible in 8 Ages, Bible Plants, Bible and Science, 
                Church Authority</em>, and many more, which continue to inspire and educate believers worldwide.
            </p>
            <p>
                Through his visionary leadership, Abiko Baptist Church has sent missionaries abroad, extending the 
                reach of the Gospel and fulfilling the Great Commission. Bishop Amari’s life and ministry stand 
                as a testament to faith.
            </p>
        </div>
    </section>

    <hr class="divider-gold">

    <!-- PROJECTS SECTION -->
    <section class="projects-centered">
        <h2 class="section-title">Ministry Projects & Updates</h2>
        
        <div class="projects-status-box">
            <?php
            // Fetch projects from DB
            $projects = $mysqli->query("SELECT * FROM projects ORDER BY id DESC");

            if ($projects && $projects->num_rows > 0):
                while ($p = $projects->fetch_assoc()):
            ?>
                <article class="project-card-centered">
                    <?php if (!empty($p['image']) && file_exists($p['image'])): ?>
                        <img src="<?php echo $p['image']; ?>" alt="<?php echo htmlspecialchars($p['title']); ?>">
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($p['title']); ?></h3>
                    <p><?php echo nl2br($p['description']); ?></p>
                </article>
            <?php 
                endwhile; 
            else: 
            ?>
                <div class="empty-state">
                    <p>No active projects to display at this time.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php include 'footer.php'; ?>