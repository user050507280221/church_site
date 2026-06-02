<?php
include 'config.php';
include 'header.php';

// --- [KEEPING ALL YOUR ORIGINAL LOGIC] ---
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filterRegion = isset($_GET['region']) ? $_GET['region'] : 'All';
if ($search !== '') { $filterRegion = 'All'; }
$regions = ['All', 'Luzon', 'Visayas', 'Mindanao'];

$cityRegionMap = [
    'Metro Manila'=>'Luzon','Caloocan'=>'Luzon','Quezon City'=>'Luzon','Manila'=>'Luzon','Makati'=>'Luzon',
    'Pasig'=>'Luzon','Taguig'=>'Luzon','Mandaluyong'=>'Luzon','Pasay'=>'Luzon','Las Piñas'=>'Luzon','Parañaque'=>'Luzon',
    'Bulacan'=>'Luzon','Angeles'=>'Luzon','San Fernando'=>'Luzon','Cavite'=>'Luzon','Laguna'=>'Luzon','Cebu'=>'Visayas',
    'Iloilo'=>'Visayas','Bacolod'=>'Visayas','Davao'=>'Mindanao','Cagayan de Oro'=>'Mindanao','Zamboanga'=>'Mindanao'
    // ... (Keep your full map here)
];

if ($search !== '') {
    foreach ($cityRegionMap as $cityName => $regionName) {
        if (stripos($search, $cityName) !== false) {
            $filterRegion = $regionName;
            break;
        }
    }
}

$sql = "SELECT * FROM churches WHERE 1=1";
$params = [];
$types = "";

if ($search !== '') {
    $sql .= " AND (name LIKE ? OR location LIKE ? OR province LIKE ?)";
    $params[] = "%{$search}%"; $params[] = "%{$search}%"; $params[] = "%{$search}%";
    $types .= "sss";
}

if ($filterRegion !== 'All' && $search === '') {
    $sql .= " AND region = ?";
    $params[] = $filterRegion;
    $types .= "s";
}

$sql .= " ORDER BY id DESC";
$stmt = $mysqli->prepare($sql);
if (!empty($params)) { $stmt->bind_param($types, ...$params); }
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- ABA STYLE HERO -->
<section class="church-hero">
    <div class="hero-inner">
        <span class="category-eyebrow">DIRECTORY</span>
        <h1>Find Churches</h1>
        <p class="intro">Find Christian Church branches near you. Visit and be part of our growing family.</p>
        
        <!-- Region Filter Bar -->
        <div class="region-nav">
            <?php foreach ($regions as $region): ?>
                <a href="?region=<?php echo $region; ?>" 
                   class="region-link <?php echo $filterRegion === $region ? 'active' : ''; ?>">
                   <?php echo $region; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Professional Expanding Search -->
        <div class="search-wrapper">
            <form method="GET" class="aba-search-form">
                <div class="search-input-group">
                    <input type="text" name="search" id="aba-search-input" 
                           placeholder="Search by name, city, or province..." 
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

<!-- 4-COLUMN GRID -->
<section class="church-grid-container">
    <div class="church-grid">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <article class="church-card">
<div class="image-container">
    <?php if (!empty($row['image']) && file_exists($row['image'])): ?>
        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
    <?php else: ?>
        <!-- Professional Placeholder when no image exists -->
        <div class="aba-placeholder">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
                <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146ZM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5Z"/>
            </svg>
        </div>
    <?php endif; ?>
</div>
                    <div class="card-content">
                        <span class="region-tag"><?php echo htmlspecialchars($row['region'] ?? 'Philippines'); ?></span>
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p class="location-text"><?php echo htmlspecialchars($row['location']); ?></p>
                        <p class="province-text"><strong><?php echo htmlspecialchars($row['province']); ?></strong></p>
                        <a href="church_details.php?id=<?php echo $row['id']; ?>" class="view-btn">View Details</a>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <p>No churches found<?php echo $search ? " for \"" . htmlspecialchars($search) . "\"" : ""; ?>.</p>
                <a href="church_finder.php" class="reset-link">Clear all filters</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>