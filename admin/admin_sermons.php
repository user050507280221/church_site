<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}

include '../config.php';

// Ensure sermons folder exists
$sermonDir = __DIR__ . '/../sermons/';
if (!file_exists($sermonDir)) {
    mkdir($sermonDir, 0755, true);
}

// ADD SERMON
if(isset($_POST['add'])){
    $pastor_id = intval($_POST['pastor_id']);
    $title = $_POST['title'];
    $filePath = '';
    $sermonLink = '';

    // Check if a file is uploaded
    if(!empty($_FILES['file']['name'])){
        $fileName = basename($_FILES['file']['name']);
        $safeName = preg_replace('/[^A-Za-z0-9\.\-_]/', '_', $fileName);
        $targetPath = $sermonDir . $safeName;
        if(move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)){
            $filePath = 'sermons/' . $safeName;
        }
    } 

    // Check if a link is provided (only if no file uploaded)
    if(empty($filePath) && !empty($_POST['link'])){
        $sermonLink = trim($_POST['link']);
    }

    if($filePath != '' || $sermonLink != ''){
        $stmt = $mysqli->prepare("INSERT INTO sermons (pastor_id, title, file, link) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $pastor_id, $title, $filePath, $sermonLink);
        $stmt->execute();
        echo "<script>alert('Sermon added successfully!');window.location='admin_sermons.php';</script>";
        exit;
    } else {
        echo "<p style='color:red;'>Please upload a file or provide a link.</p>";
    }
}

// DELETE SERMON
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $sel = $mysqli->query("SELECT file FROM sermons WHERE id=$id");
    if($f = $sel->fetch_assoc()){
        $path = __DIR__ . '/../' . $f['file'];
        if(file_exists($path)) unlink($path);
    }
    $mysqli->query("DELETE FROM sermons WHERE id=$id");
    echo "<script>alert('Sermon deleted');window.location='admin_sermons.php';</script>";
    exit;
}

// Fetch pastors for Add Form
$pastorsResult = $mysqli->query("SELECT id, name FROM pastors ORDER BY name ASC");
$pastors = [];
while($p = $pastorsResult->fetch_assoc()){
    $pastors[] = $p;
}

// Fetch sermons for list
$sermonsResult = $mysqli->query("
    SELECT sermons.*, pastors.name AS pastor_name
    FROM sermons
    LEFT JOIN pastors ON sermons.pastor_id = pastors.id
    ORDER BY sermons.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Sermons</title>
<link rel="stylesheet" href="../css/style.css">
<style>
body { background:#f7f7f7; font-family:Arial,sans-serif; padding:20px; }
.admin-sermons { max-width:950px; margin:40px auto; background:#fff; padding:30px; border-radius:12px; box-shadow:0 3px 6px rgba(0,0,0,0.1);}
h2 { color:#1e3a8a; text-align:center; margin-bottom:20px;}
.add-form { background:#eef2ff; padding:20px; border-radius:8px; margin-bottom:30px;}
input, select { width:100%; padding:10px; margin:8px 0; border:1px solid #ccc; border-radius:6px; }
button { background:#1e3a8a; color:white; padding:10px 20px; border:none; border-radius:6px; cursor:pointer;}
button:hover{ background:#3749b5; }
table { width:100%; border-collapse:collapse; margin-top:15px;}
th, td { border:1px solid #ddd; padding:10px; text-align:center;}
th { background:#1e3a8a; color:white;}
tr:nth-child(even){ background:#f2f2f2;}
.btn-delete { background:#d32f2f; color:white; padding:5px 10px; border-radius:4px; text-decoration:none; }
.btn-delete:hover{ background:#b71c1c; }
.btn-edit { background:#4caf50; color:white; padding:5px 10px; border-radius:4px; text-decoration:none; }
.btn-edit:hover{ background:#357a38; }
.back-btn { display:inline-block; margin-bottom:20px; background:#4f46e5; color:white; padding:8px 14px; border-radius:6px; text-decoration:none;}
</style>
</head>
<body>

<div class="admin-sermons">
    <a href="index.php" class="back-btn">⬅ Back to Dashboard</a>
    <h2>Manage Sermons</h2>

    <!-- Add Sermon Form -->
    <form method="POST" enctype="multipart/form-data" class="add-form">
        <h3>Add New Sermon</h3>
        <label>Pastor:</label>
        <select name="pastor_id" required>
            <option value="">-- Select Pastor --</option>
            <?php foreach($pastors as $p): ?>
                <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['name']); ?></option>
            <?php endforeach; ?>
        </select>

        <label>Sermon Title:</label>
        <input type="text" name="title" placeholder="Enter sermon title" required>

        <label>Upload Sermon (PDF / PowerPoint):</label>
        <input type="file" name="file" accept=".pdf,.ppt,.pptx">

        <label>Or provide a link:</label>
        <input type="url" name="link" placeholder="Enter sermon URL">

        <button type="submit" name="add">Add Sermon</button>
    </form>

    <!-- Sermon List -->
    <h3>Existing Sermons</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Pastor</th>
            <th>Title</th>
            <th>File / Link</th>
            <th>Action</th>
        </tr>
        <?php while($row = $sermonsResult->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['pastor_name']); ?></td>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td>
                <?php 
                if($row['file'] != ''): ?>
                    <a href="../<?php echo $row['file']; ?>" target="_blank">View File</a>
                <?php elseif($row['link'] != ''): ?>
                    <a href="<?php echo htmlspecialchars($row['link']); ?>" target="_blank">View Link</a>
                <?php else: ?>
                    N/A
                <?php endif; ?>
            </td>
            <td>
                <a href="edit_sermon.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this sermon?')" class="btn-delete">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
