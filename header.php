<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="css/base.css">
  <link rel="stylesheet" href="css/components.css">
  <link rel="stylesheet" href="css/sections.css">
  <link rel="stylesheet" href="css/sermons.css">
  <link rel="stylesheet" href="css/church_finder.css">
  <link rel="stylesheet" href="css/pastors.css">
  <link rel="stylesheet" href="css/contact.css">
  <link rel="stylesheet" href="css/books.css">
  <link rel="stylesheet" href="css/beliefs.css">
  <link rel="stylesheet" href="css/project.css">
  <link rel="stylesheet" href="css/pastor_view.css">
  <link rel="stylesheet" href="css/church_view.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

<script>
  // Prevent FOUC
  document.documentElement.style.opacity = '0';
  window.addEventListener('DOMContentLoaded', () => {
    document.documentElement.style.opacity = '';
    document.documentElement.style.transition = 'opacity 0.35s ease';
  });
</script>

<header class="site-header">
    <!-- Top Utility Bar (Dark) -->
    <div class="top-bar">
        <div class="header-container">
            <ul class="top-links">
                <li><a href="books.php">Books</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="beliefs.php">Our Beliefs</a></li>
                <li><a href="project.php">Head Pastor</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Navigation Bar (Cream/White) -->
    <div class="main-nav">
        <div class="header-container">
            <div class="logo-section">
                <a href="index.php" class="logo-link">
                    <img src="images/logo.jpg" alt="Logo">
                    <div class="logo-text-wrapper">
                        <span class="logo-top">church</span>
                        <span class="logo-bottom">directory</span>
                    </div>
                </a>
            </div>

            <nav>
                <ul class="nav-menu">
                    <li><a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                    <li><a href="sermons.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'sermons.php' ? 'active' : ''; ?>">Sermons</a></li>
                    <li><a href="churches.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'churches.php' ? 'active' : ''; ?>">Churches</a></li>
                    <li><a href="pastor.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'pastor.php' ? 'active' : ''; ?>">Pastors</a></li>
                </ul>
            </nav>

            <div class="header-actions">
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSdn2C7awmJZge1M5BZ9q3Kj2SMHMgjH0Y1ASp9YeRrLSI0leQ/viewform" target="_blank" class="join-btn">
                    Get Listed
                </a>
            </div>
        </div>
    </div>
</header>