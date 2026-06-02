<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include '../config.php';

// Get pastor ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($id <= 0){
    echo "<script>alert('Invalid pastor ID');window.location='admin_pastors.php';</script>";
    exit;
}

// Fetch pastor data
$stmt = $mysqli->prepare("SELECT * FROM pastors WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$pastor = $res->fetch_assoc();
$stmt->close();

if(!$pastor){
    echo "<script>alert('Pastor not found');window.location='admin_pastors.php';</script>";
    exit;
}

$imagesDir = __DIR__ . '/../images/';

// Handle update
if(isset($_POST['update'])){
    $name = $_POST['name'] ?? '';
    $title = $_POST['title'] ?? '';
    $bio = $_POST['bio'] ?? '';
    $church_id = $_POST['church_id'] ?? 0;
    $email = $_POST['email'] ?? '';
    $facebook = $_POST['facebook'] ?? '';
    $sermon_link = $_POST['sermon_link'] ?? '';
    $image = $pastor['image'];

    // Handle image upload
    if(!empty($_FILES['image']['name'])){
        $safeName = preg_replace('/[^A-Za-z0-9\.\-_]/', '_', basename($_FILES["image"]["name"]));
        $targetFile = $imagesDir . $safeName;
        if(move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)){
            if(!empty($pastor['image']) && file_exists(__DIR__ . '/../' . $pastor['image'])){
                unlink(__DIR__ . '/../' . $pastor['image']);
            }
            $image = "images/" . $safeName;
        }
    }

    // Update DB including sermon_link
    $stmtUp = $mysqli->prepare("UPDATE pastors SET name=?, title=?, bio=?, church_id=?, email=?, facebook=?, sermon_link=?, image=? WHERE id=?");
    $stmtUp->bind_param("sssissssi", $name, $title, $bio, $church_id, $email, $facebook, $sermon_link, $image, $id);
    $stmtUp->execute();
    $stmtUp->close();

    echo "<script>alert('Pastor updated successfully');window.location='admin_pastors.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Pastor</title>
<link rel="stylesheet" href="../css/style.css">
<style>
body { font-family: 'Segoe UI', sans-serif; background: #f4f6fb; margin: 0; padding: 20px; }
.edit-container { max-width: 700px; margin: 30px auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 6px 16px rgba(0,0,0,0.1); }
.edit-container h2 { color: #1e3a8a; margin-bottom: 20px; font-size: 1.8rem; }
.back-btn { display: inline-block; margin-bottom: 20px; padding: 10px 20px; background: #1e3a8a; color: white; border-radius: 8px; text-decoration: none; font-weight: bold; transition: 0.3s; }
.back-btn:hover { background: #3749b5; }
.edit-container form { display: flex; flex-direction: column; gap: 15px; }
.edit-container input, .edit-container textarea, .edit-container select { padding: 10px; border-radius: 6px; border: 1px solid #ccc; width: 100%; font-size: 14px; }
.edit-container button { background: #1e3a8a; color: white; border: none; padding: 12px; border-radius: 6px; cursor: pointer; font-weight: bold; transition: 0.3s; }
.edit-container button:hover { background: #3749b5; }
img.preview { border-radius: 8px; width: 120px; height: 100px; object-fit: cover; margin-bottom: 10px; }
@media screen and (max-width: 600px) { .edit-container { padding: 20px; } }
</style>
</head>
<body>

<div class="edit-container">
    <a href="admin_pastors.php" class="back-btn">⬅ Cancel</a>
    <h2>Edit Pastor</h2>

    <form method="POST" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($pastor['name']); ?>" required>

        <label>Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($pastor['title']); ?>">

        <label>Bio:</label>
        <textarea name="bio" rows="3"><?php echo htmlspecialchars($pastor['bio']); ?></textarea>

        <label>Church:</label>
        <select name="church_id" required>
            <option value="">-- Select Church --</option>
            <?php
            $res = $mysqli->query("SELECT id, name FROM churches ORDER BY name ASC");
            while($ch = $res->fetch_assoc()){
                $selected = ($ch['id'] == $pastor['church_id']) ? 'selected' : '';
                echo "<option value='{$ch['id']}' $selected>{$ch['name']}</option>";
            }
            ?>
        </select>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($pastor['email']); ?>">

        <label>Facebook Link:</label>
        <input type="url" name="facebook" value="<?php echo htmlspecialchars($pastor['facebook']); ?>">

        <label>Sermon Link:</label>
        <input type="url" name="sermon_link" value="<?php echo htmlspecialchars($pastor['sermon_link']); ?>" placeholder="https://">

        <label>Current Image:</label><br>
        <?php if(!empty($pastor['image'])): ?>
            <img src="../<?php echo $pastor['image']; ?>" class="preview">
        <?php else: ?>
            <p>No Image</p>
        <?php endif; ?>
        <input type="file" name="image" accept="image/*">

        <button type="submit" name="update">Update Pastor</button>
    </form>
</div>

</body>
</html>
