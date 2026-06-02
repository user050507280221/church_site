<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Church Admin</title>
  <link rel="stylesheet" href="style.css"> <!-- or ../css/style.css kung nasa labas -->
</head>
<body>
  <div class="sidebar">
    <h2>Church Admin</h2>
    <ul>
      <li><a href="index.php">🏠 Dashboard</a></li>
      <li><a href="admin_churches.php">⛪ Manage Churches</a></li>
      <li><a href="admin_pastors.php">👨‍💼 Manage Pastors</a></li>
      <li><a href="admin_sermons.php">📖 Manage Sermons</a></li>
      <li><a href="admin_events.php">🎉 Manage Events</a></li>
      <li><a href="admin_books.php">📬 Manage Books</a></li> 
      <li><a href="admin_contacts.php">📬 Manage Contacts</a></li> 
  
    </ul>
  </div>


