<?php 
include '../config.php';
session_start();

// Only admin can access
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}

// Get church ID safely
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$result = $mysqli->query("SELECT * FROM churches WHERE id=$id");
$church = $result->fetch_assoc();

if(!$church){ 
    echo "<h2>Church not found!</h2>"; 
    exit; 
}

// UPDATE
if(isset($_POST['update'])){
    $name = $_POST['name'];
    $mother_church = $_POST['mother_church'] ?? '';
    $location = $_POST['location'];
    $region = $_POST['region'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $pastor = $_POST['pastor'];
    $facebook = $_POST['facebook'];
    $instagram = $_POST['instagram'];
    $description = !empty($_POST['description']) ? $_POST['description'] : NULL;
    $date_organized = !empty($_POST['date_organized']) ? $_POST['date_organized'] : NULL;

    // Handle image upload
    $image = $church['image'];
    if(!empty($_FILES['image']['name'])){
        $targetDir = "../images/";
        $fileName = preg_replace('/[^A-Za-z0-9\.\-_]/', '_', basename($_FILES["image"]["name"]));
        if(move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir.$fileName)){
            $image = "images/".$fileName;
        }
    }

    // Update church info
    $stmt = $mysqli->prepare(
        "UPDATE churches 
         SET name=?, mother_church=?, location=?, region=?, contact=?, email=?, pastor=?, facebook=?, instagram=?, date_organized=?, description=?, image=? 
         WHERE id=?"
    );
    $stmt->bind_param(
        "ssssssssssssi",
        $name,
        $mother_church,
        $location,
        $region,
        $contact,
        $email,
        $pastor,
        $facebook,
        $instagram,
        $date_organized,
        $description,
        $image,
        $id
    );
    $stmt->execute();
    $stmt->close();

    // AUTO ADD PASTOR ONLY IF NEW
    if(!empty($pastor)){
        $check = $mysqli->prepare("SELECT id FROM pastors WHERE name=?");
        $check->bind_param("s", $pastor);
        $check->execute();
        $check->store_result();

        if($check->num_rows == 0){
            // Pastor doesn't exist → add new
            $defaultTitle = "Pastor";
            $defaultBio = "";
            $defaultImage = "";
            $defaultFacebook = "";
            $defaultSermon = "";

            $insertPastor = $mysqli->prepare(
                "INSERT INTO pastors (name, title, bio, image, facebook, sermon_link)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            $insertPastor->bind_param(
                "ssssss",
                $pastor,
                $defaultTitle,
                $defaultBio,
                $defaultImage,
                $defaultFacebook,
                $defaultSermon
            );
            $insertPastor->execute();
            $insertPastor->close();
        }
        $check->close();
    }

    echo "<script>alert('Church updated successfully');window.location='admin_churches.php';</script>";
}
?>

<link rel="stylesheet" href="../css/style.css">
<style>
.add-form input, .add-form textarea, .add-form select, .add-form button {
    display: block;
    width: 100%;
    margin-bottom: 12px;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
}
.add-form button {
    background-color: #1e3a8a;
    color: #fff;
    border: none;
    cursor: pointer;
    font-weight: bold;
    padding: 10px;
}
.add-form button:hover {
    background-color: #163070;
}
.back-btn {
    display: inline-block;
    text-decoration: none;
    background-color: #1e3a8a;
    color: #fff;
    padding: 8px 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    transition: background 0.3s ease;
}
.back-btn:hover {
    background-color: #163070;
}
img {
    border-radius: 6px;
    margin-bottom: 12px;
}
</style>

<section class="edit-church">
<a href="admin_churches.php" class="back-btn">⬅ Cancel</a>

<h2>Edit Church</h2>

<form method="POST" enctype="multipart/form-data" class="add-form">

<input type="text" name="name"
 value="<?= htmlspecialchars($church['name']); ?>" placeholder="Church Name" required>

<input type="text" name="mother_church"
 value="<?= htmlspecialchars($church['mother_church'] ?? ''); ?>" placeholder="Mother Church (if any)">

<input type="text" name="location"
 value="<?= htmlspecialchars($church['location']); ?>" placeholder="Location / Street" >

<select name="region" required>
  <option value="">-- Select Region --</option>
  <option value="Luzon" <?= $church['region']=='Luzon'?'selected':'' ?>>Luzon</option>
  <option value="Visayas" <?= $church['region']=='Visayas'?'selected':'' ?>>Visayas</option>
  <option value="Mindanao" <?= $church['region']=='Mindanao'?'selected':'' ?>>Mindanao</option>
</select>

<input type="text" name="contact"
 value="<?= htmlspecialchars($church['contact']); ?>" placeholder="Contact Number">

<input type="email" name="email"
 value="<?= htmlspecialchars($church['email']); ?>" placeholder="Email Address">

<!-- Pastor dropdown, pre-selected current pastor -->
<select name="pastor" required>
    <option value="">-- Select Pastor --</option>
    <?php
    $pastors = $mysqli->query("SELECT name FROM pastors ORDER BY name ASC");
    while($p = $pastors->fetch_assoc()):
    ?>
        <option value="<?= htmlspecialchars($p['name']); ?>"
            <?= $church['pastor']==$p['name'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($p['name']); ?>
        </option>
    <?php endwhile; ?>
</select>

<input type="url" name="facebook"
 value="<?= htmlspecialchars($church['facebook']); ?>" placeholder="Facebook Page URL">

<input type="url" name="instagram"
 value="<?= htmlspecialchars($church['instagram']); ?>" placeholder="Instagram URL">

<label for="date_organized">Date of Organize:</label>
<input type="date" name="date_organized" id="date_organized" 
 value="<?= htmlspecialchars($church['date_organized'] ?? '') ?>">

<p>Current Image:</p>
<?php if(!empty($church['image']) && file_exists("../".$church['image'])): ?>
<img src="../<?= $church['image']; ?>" width="150">
<?php else: ?>
<p>No image uploaded.</p>
<?php endif; ?>

<input type="file" name="image" accept="image/*">

<button type="submit" name="update">Update Church</button>

</form>
</section>

<?php include '../footer.php'; ?>
