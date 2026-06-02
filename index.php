<?php 
include 'header.php'; 
include 'config.php'; 
?>

<main>
    <!-- Featured Circle Link -->
    <section class="featured-section">
        <a href="#latest-articles" class="featured-circle">
            <h2>Latest Events</h2>
            <div class="arrow"></div>
        </a>
    </section>
<section class="hero">
    <div class="hero-content">
        <!-- Added "Welcome to" here for a clean, professional introduction -->
        <span class="hero-subtitle">Welcome to the Philippine Missionary Baptist Directory</span>
        
        <h1>Directory of Missionary Baptist <br> Churches and Missions</h1>
        <div class="hero-divider"></div>
        
        <p class="hero-description">
            Discover places of Worship with Regular Missionary Baptist Churches and Missions in the Philippines 
            and other countries who uphold the ABA Doctrinal Statement of Faith.
        </p>
        
        <div class="hero-verse-box">
            <p class="bible-verse">
                “For as we have many members in one body, and all members have not the same office: 
                So we, being many, are one body in Christ, and every one members one of another.”
            </p>
            <span class="verse-reference">— Romans 12:4-5</span>
        </div>
    </div>
</section>
<!-- Events Section -->
<section id="latest-articles" class="articles fade-section">
    <h2>Latest Events</h2>
    <div class="carousel-track">
        <?php
        $events = $mysqli->query("SELECT * FROM events ORDER BY date DESC LIMIT 10");
        if($events && $events->num_rows > 0):
            while($e = $events->fetch_assoc()):
        ?>
        <article class="carousel-item">
            <?php if (!empty($e['image']) && file_exists($e['image'])): ?>
                <img src="<?php echo $e['image']; ?>" class="article-img" alt="<?php echo htmlspecialchars($e['title']); ?>">
            <?php else: ?>
                <img src="images/book.png" class="article-img" alt="No Image">
            <?php endif; ?>
            <h3><?php echo htmlspecialchars($e['title']); ?></h3>
            <p><?php echo mb_substr(strip_tags($e['description']), 0, 120); ?>...</p>
            <a href="article.php?id=<?php echo $e['id']; ?>" class="read-more">Read more</a>
        </article>
        <?php endwhile; else: ?>
            <p style="color:var(--aba-navy)">No articles/events found.</p>
        <?php endif; ?>
    </div>
</section>

<!-- About Section -->
<section id="about-us" class="about fade-section">
    <h2 class="about-heading">About This Website</h2>
    <div class="about-columns">
        <div class="about-card stagger-child">
            <h3>Developer / Head</h3>
            <p><strong>Head:</strong> <a href="project.php">Bishop Shinji Amari</a></p>
            <p><strong>Main Developer:</strong> Lance de Leon</p>
            <p><strong>Main Designer:</strong> Angel Benitez</p>
        </div>

        <div class="about-card purpose-card stagger-child">
            <h3>Our Purpose</h3>
            <p>
                Abiko Baptist Church and Pastor Shinji Amari see the <strong>need</strong> for creating and maintaining a Philippine Missionary Baptist directory...
                <br/><br/>
                This tool supports the Great Commission (Matthew 28:19-20) through modern Evangelism & Outreach.
            </p>
        </div>

        <div class="about-card stagger-child">
            <h3>Website Goal</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="margin-bottom: 10px;">• Guide users to nearby churches</li>
                <li style="margin-bottom: 10px;">• Share the latest events</li>
                <li>• Help spiritually nourish visitors</li>
            </ul>
        </div>
    </div>
</section>

<!-- Scripts -->
<script>
// Read More Logic
document.querySelectorAll('.read-more').forEach(btn => {
  btn.addEventListener('click', function(e) {
    const moreText = this.previousElementSibling;
    if (moreText.style.display === "none") {
      moreText.style.display = "inline";
      this.textContent = "Show less";
    } else {
      moreText.style.display = "none";
      this.textContent = "Read more";
    }
  });
});

// Carousel Logic
const track = document.querySelector('.carousel-track');
const markersContainer = document.querySelector('.scroll-markers');
// ... rest of your carousel script ...

// Scroll Fade Logic
document.addEventListener('DOMContentLoaded', function() {
    const fadeSections = document.querySelectorAll('.fade-section');
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('visible');
        });
    }, { threshold: 0.2 });
    fadeSections.forEach(section => observer.observe(section));
});
</script>

<?php include 'footer.php'; ?>