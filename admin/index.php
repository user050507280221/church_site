<?php
session_start();

// Check if user is logged in and is admin
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Church Admin Dashboard</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Arial, sans-serif;
    }

    body {
      display: flex;
      height: 100vh;
      background: #f6f7faff;
    }

    /* --- SIDEBAR --- */
    .sidebar {
      width: 250px;
      background: #313647;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-top: 30px;
      box-shadow: 2px 0 10px rgba(0,0,0,0.15);
    }

    .sidebar h2 {
      font-size: 22px;
      margin-bottom: 30px;
      letter-spacing: 1px;
    }

    .sidebar ul {
      list-style: none;
      width: 100%;
    }

    .sidebar ul li {
      width: 100%;
    }

    .sidebar ul li a {
      display: block;
      width: 100%;
      padding: 14px 25px;
      color: #fff;
      text-decoration: none;
      font-size: 16px;
      transition: 0.3s;
    }

    .sidebar ul li a:hover, .sidebar ul li a.active {
      background: #3749b5;
      padding-left: 35px;
    }

    .logout {
      color: #ffbaba !important;
    }

    /* --- MAIN CONTENT --- */
    .main-content {
      flex: 1;
      padding: 40px;
      overflow-y: auto;
      background: url('../images/family.jpg') no-repeat center center/cover; /* Add your background image here */
      position: relative;
    }

    /* Overlay for readability */
    .main-content::before {
      content: "";
      position: absolute;
      top:0; left:0;
      width:100%; height:100%;
      background: rgba(0,0,0,0.35);
      z-index: 1;
    }

    header, .dashboard-card {
      position: relative;
      z-index: 2;
    }

    header {
      background: rgba(255,255,255,0.9);
      padding: 15px 25px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
      margin-bottom: 30px;
      text-align: center;
    }

    header h1 {
      color: #000001ff;
      font-size: 24px;
    }

    .dashboard-card {
      background: rgba(255,255,255,0.85);
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.2);
      padding: 40px 30px;
      text-align: center;
      margin: auto;
      max-width: 700px;
      backdrop-filter: blur(5px);
    }

    .dashboard-card h2 {
      color: #1e3a8a;
      margin-bottom: 15px;
    }

    .dashboard-card p {
      font-size: 15px;
      color: #444;
    }
  </style>
</head>
<body>

  <div class="sidebar">
    <h2>Church Admin</h2>
    <ul>
      <li><a href="index.php" class="active">🏠 Dashboard</a></li>
      <li><a href="admin_churches.php">⛪ Manage Churches</a></li>
      <li><a href="admin_pastors.php">👨‍💼 Manage Pastors</a></li>
      <li><a href="admin_sermons.php">📖 Manage Sermons</a></li>
      <li><a href="admin_events.php">🎉 Manage Events</a></li>
      <li><a href="admin_books.php">📬 Manage Books</a></li> 
       <li><a href="admin_contacts.php">📬 Manage Contacts</a></li> 
     
    </ul>
  </div>

  <div class="main-content">
    <header>
      <h1>Welcome to Church Admin Dashboard</h1>
    </header>

    <div class="dashboard-card">
      <h2>📊 Overview</h2>
      <p>Use the sidebar to manage churches, pastors, sermons, and upcoming events.</p>
      <p>This panel helps administrators update content easily.</p>
    </div>
  </div>

</body>
</html>
