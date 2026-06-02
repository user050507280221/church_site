<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}

include '../config.php';

$sermonDir = __DIR__ . '/../sermons/';
if (!file_exists($sermonDir)) mkdir($sermonDir, 0755, true);

if(!isset($_GET['id'])){
    header("Location: admin_sermons.php");
    exit;
}

$id = intval($_GET['id']);
$sermon = $mysqli->query("SELECT * FROM sermons WHERE id=$id")->fetch_assoc();
if(!$sermon){
    echo "<p style='text-align:center;color:red;'>Sermon not found!</p>";
    exit;
}

if(isset($_POST['update'])){
    $title = $_POST['title'];
    $pastor_id = intval($_POST['pastor_id']);
    $filePath = $sermon['file'];
    $sermonLink = $sermon['link'];

    // Check if a new file is uploaded
    if(!empty($_FILES['file']['name'])){
        $fileName = basename($_FILES['file']['name']);
        $safeName = preg_replace('/[^A-Za-z0-9\.\-_]/', '_', $fileName);
        $targetPath = $sermonDir . $safeName;
        if(move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)){
            // Delete old file if exists
            if(!empty($filePath)){
                $oldPath = __DIR__ . '/../' . $filePath;
                if(file_exists($oldPath)) unlink($oldPath);
            }
            $filePath = 'sermons/' . $safeName;
            $sermonLink = ''; // clear link if file is uploaded
        }
    } elseif(!empty($_POST['link'])) {
        // Use link only if no new file uploaded
        $sermonLink = trim($_POST['link']);
        $filePath = ''; // clear file if link is used
    }

    $stmt = $mysqli->prepare("UPDATE sermons SET title=?, pastor_id=?, file=?, link=? WHERE id=?");
    $stmt->bind_param("sissi", $title, $pastor_id, $filePath, $sermonLink, $id);
    $stmt->execute();

    echo "<script>alert('Sermon updated successfully!');window.location='admin_sermons.php';</script>";
    exit;
}

$pastorsResult = $mysqli->query("SELECT id, name FROM pastors ORDER BY name ASC");
$pastors = [];
while($p = $pastorsResult->fetch_assoc()){
    $pastors[] = $p;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Sermon</title>
<link rel="stylesheet" href="../css/style.css">
<style>
body { font-family: 'Arial', sans-serif; background: #f4f6fb; padding: 20px; }
.admin-sermons { max-width: 700px; margin: 50px auto; background: #ffffff; padding: 35px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08);}
h2 { text-align: center; color: #1e3a8a; margin-bottom: 30px; font-size: 26px;}
.edit-form { display: flex; flex-direction: column; gap: 16px;}
.edit-form label { font-weight: bold; margin-bottom: 6px; color: #333;}
input, select { width: 100%; padding: 12px 14px; border-radius: 8px; border: 1px solid #ccc; font-size: 15px;}
input:focus, select:focus { border-color: #1e3a8a; box-shadow: 0 0 5px rgba(30,58,138,0.2); outline: none;}
button { background: #1e3a8a; color: #fff; padding: 12px 20px; border: none; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer;}
button:hover { background: #3749b5; transform: translateY(-2px);}
.back-btn { display: inline-block; margin-bottom: 25px; background: #4f46e5; color: white; padding: 10px 16px; border-radius: 8px; text-decoration: none; font-weight: bold;}
.back-btn:hover { background: #312e81; }
</style>
</head>
<body>

<div class="admin-sermons">
    <a href="admin_sermons.php" class="back-btn">⬅ Cancel</a>
    <h2>Edit Sermon</h2>

    <form method="POST" enctype="multipart/form-data" class="edit-form">
        <label>Pastor:</label>
        <select name="pastor_id" required>
            <?php foreach($pastors as $p): 
                $selected = ($p['id'] == $sermon['pastor_id']) ? 'selected' : '';
            ?>
                <option value="<?php echo $p['id']; ?>" <?php echo $selected; ?>>
                    <?php echo htmlspecialchars($p['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Sermon Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($sermon['title']); ?>" required>

        <label>Replace File (optional):</label>
        <input type="file" name="file" accept=".pdf,.ppt,.pptx">

        <label>Or provide a link (optional):</label>
        <input type="url" name="link" value="<?php echo htmlspecialchars($sermon['link']); ?>" placeholder="Enter sermon URL">

        <button type="submit" name="update">Update Sermon</button>
    </form>
</div>

</body>
</html>
