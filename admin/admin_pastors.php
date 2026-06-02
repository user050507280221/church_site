<?php
session_start();

// Only admin can access
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}

include '../config.php';

// Ensure images folder exists
$imagesDir = __DIR__ . '/../images/';
if (!file_exists($imagesDir)) mkdir($imagesDir, 0755, true);

// Handle Add Pastor
if(isset($_POST['add'])){
    $name = $_POST['name'] ?? '';
    $bio = $_POST['bio'] ?? '';
    $facebook = $_POST['facebook'] ?? '';
    $sermon_link = $_POST['sermon_link'] ?? '';
    $image = "";

    // Upload image
    if(!empty($_FILES['image']['name'])){
        $basename = basename($_FILES["image"]["name"]);
        $safeName = preg_replace('/[^A-Za-z0-9\.\-_]/', '_', $basename);
        $targetFile = $imagesDir . $safeName;
        if(move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)){
            $image = "images/" . $safeName;
        }
    }

    // Insert pastor (no title column anymore)
    $stmt = $mysqli->prepare("INSERT INTO pastors (name, bio, image, facebook, sermon_link) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
    $stmt->bind_param("sssss", $name, $bio, $image, $facebook, $sermon_link);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Pastor added successfully!');window.location='admin_pastors.php';</script>";
    exit;
}

// Handle Delete Pastor
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);

    // Delete image if exists
    $stmtSelect = $mysqli->prepare("SELECT image FROM pastors WHERE id=?");
    $stmtSelect->bind_param("i", $id);
    $stmtSelect->execute();
    $res = $stmtSelect->get_result();
    if($row = $res->fetch_assoc()){
        if(!empty($row['image']) && file_exists(__DIR__.'/../'.$row['image'])){
            unlink(__DIR__.'/../'.$row['image']);
        }
    }
    $stmtSelect->close();

    // Delete pastor
    $stmt = $mysqli->prepare("DELETE FROM pastors WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Pastor deleted');window.location='admin_pastors.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Pastors</title>
<link rel="stylesheet" href="../css/style.css">

<style>
body { font-family: 'Segoe UI', sans-serif; background: #f4f6fb; margin: 0; padding: 20px; }
.admin-pastors { background: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 6px 16px rgba(0,0,0,0.1); max-width: 1200px; margin: 0 auto; }
h2 { color: #1e3a8a; margin-bottom: 20px; font-size: 1.8rem; }
.back-btn { display: inline-block; margin-bottom: 25px; background: #1e3a8a; color: white; text-decoration: none; padding: 10px 22px; border-radius: 8px; transition: 0.3s; font-weight: bold; }
.back-btn:hover { background: #3749b5; }

form.add-form { margin-bottom: 35px; display: flex; flex-direction: column; gap: 12px; background:#f9f9f9; padding:20px; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.05); }
form input, form textarea, form button, form label { font-size: 14px; }
form input, form textarea { padding: 10px; border-radius: 6px; border: 1px solid #ccc; }
form button { background: #1e3a8a; color: white; border: none; cursor: pointer; border-radius: 6px; padding: 10px; transition: 0.3s; font-weight: bold; }
form button:hover { background: #3749b5; }

table { width: 100%; border-collapse: collapse; margin-top: 15px; }
th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; vertical-align: middle; }
th { background: #1e3a8a; color: white; }

img { border-radius: 8px; object-fit: cover; }

.action-buttons { display: flex; gap: 10px; }
.btn-edit, .btn-delete {
    padding: 8px 14px;
    text-decoration: none;
    color: white;
    border-radius: 6px;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: 0.3s;
}
.btn-edit { background:#2563eb; }
.btn-edit:hover { background:#3b82f6; }
.btn-delete { background:#dc2626; }
.btn-delete:hover { background:#ef4444; }
</style>

</head>
<body>

<section class="admin-pastors">
  <a href="index.php" class="back-btn">⬅ Back to Dashboard</a>
  <h2>Manage Pastors</h2>

  <!-- Add Form -->
  <form method="POST" enctype="multipart/form-data" class="add-form">
    <h3 style="margin-bottom:10px;">Add New Pastor</h3>
    <input type="text" name="name" placeholder="Pastor Name" required>
    <textarea name="bio" placeholder="Short Bio" rows="3"></textarea>
    <input type="url" name="facebook" placeholder="Facebook Profile URL">
    <input type="url" name="sermon_link" placeholder="Sermon Link (e.g. YouTube)">
    <label>Upload Image:</label>
    <input type="file" name="image" accept="image/*">
    <button type="submit" name="add">Add Pastor</button>
  </form>

  <!-- Pastor List -->
  <h3>Existing Pastors</h3>
  <table class="pastor-table">
    <tr>
      <th>#</th>
      <th>Image</th>
      <th>Name</th>
      <th>Church</th>
      <th>Location</th>
      <th>Facebook</th>
      <th>Sermon</th>
      <th>Actions</th>
    </tr>
    <?php
      $result = $mysqli->query("
        SELECT p.*, c.name AS church_name, c.location AS church_location
        FROM pastors p
        LEFT JOIN churches c ON p.name = c.pastor
        ORDER BY p.id DESC
      ");

      $counter = 1; // Sequential number for table
      while($row = $result->fetch_assoc()):
        $imgSrc = !empty($row['image']) ? '../' . $row['image'] : '../images/placeholder.png';
    ?>
    <tr>
      <td><?php echo $counter++; ?></td> <!-- sequential ID -->
      <td><img src="<?php echo $imgSrc; ?>" width="80" height="80"></td>
      <td><?php echo htmlspecialchars($row['name']); ?></td>
      <td><?php echo htmlspecialchars($row['church_name'] ?? '—'); ?></td>
      <td><?php echo htmlspecialchars($row['church_location'] ?? '—'); ?></td>
      <td>
        <?php if(!empty($row['facebook'])): ?>
          <a href="<?php echo htmlspecialchars($row['facebook']); ?>" target="_blank">FB Link</a>
        <?php endif; ?>
      </td>
      <td>
        <?php if(!empty($row['sermon_link'])): ?>
          <a href="<?php echo htmlspecialchars($row['sermon_link']); ?>" target="_blank">Watch Sermon</a>
        <?php endif; ?>
      </td>
      <td class="action-buttons">
        <a href="edit_pastor.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
        <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this pastor?')" class="btn-delete">Delete</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</section>

</body>
</html>
