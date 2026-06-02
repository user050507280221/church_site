<?php
include 'config.php';
include 'header.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT sermons.*, pastors.name AS pastor_name
        FROM sermons
        LEFT JOIN pastors ON sermons.pastor_id = pastors.id";

if ($search !== '') {
    $sql .= " WHERE sermons.title LIKE ? OR pastors.name LIKE ?";
    $stmt = $mysqli->prepare($sql);
    $param = "%{$search}%";
    $stmt->bind_param("ss", $param, $param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $mysqli->query($sql);
}
?>

<!-- ABA Style Hero Section -->
<section class="sermons-hero">
    <div class="hero-content">
        <h1>Pastor Sermons</h1>
        <p class="intro">Browse and download sermons from each pastor. Stay spiritually nourished!</p>
        
        <!-- Expanding Search Bar Below Header -->
        <div class="search-wrapper">
            <form method="GET" class="aba-search-form">
                <div class="search-input-group">
                    <input 
                        type="text" 
                        name="search" 
                        id="aba-search-input" 
                        placeholder="Search sermons or pastors..." 
                        value="<?php echo htmlspecialchars($search); ?>"
                    >
                    <button type="submit" class="search-submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="sermons-grid-container">
    <!-- CAROUSEL/GRID -->
    <div class="carousel" data-scroll-initial-target="0">
        <div class="carousel-track">

            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <article class="carousel-item">
                        <div class="item-inner">
                            <span class="category-label">SERMON</span>
                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                            <p class="pastor-tag">By: <strong><?php echo htmlspecialchars($row['pastor_name']); ?></strong></p>

                            <div class="action-area">
                            <?php
                                $file_path = trim($row['file']);
                                $sermon_link = trim($row['link']);

                                if($file_path && file_exists($file_path)):
                                    $ext = pathinfo($file_path, PATHINFO_EXTENSION);
                                    $label = strtoupper($ext) == 'PDF' ? 'View PDF' : 'Download ' . strtoupper($ext);
                            ?>
                                <a href="<?php echo htmlspecialchars($file_path); ?>" target="_blank" class="read-more">
                                    <?php echo $label; ?>
                                </a>
                            <?php elseif($sermon_link): ?>
                                <a href="<?php echo htmlspecialchars($sermon_link); ?>" target="_blank" class="read-more">
                                    View Link
                                </a>
                            <?php else: ?>
                                <span class="file-missing">Content Unavailable</span>
                            <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-result-box">
                    <p>No sermons found<?php echo $search ? ' for "' . htmlspecialchars($search) . '"' : ""; ?>.</p>
                    <a href="sermon.php" class="btn-clear">View All Sermons</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>