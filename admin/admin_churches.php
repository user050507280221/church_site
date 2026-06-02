<?php
// Start session safely
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Admin check
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

// Include database connection
include '../config.php';

// Ensure images folder exists
$imagesDir = __DIR__ . '/../images/';
if (!file_exists($imagesDir)) {
    mkdir($imagesDir, 0755, true);
}

// Handle Add Church
if(isset($_POST['add'])){
    $name = $mysqli->real_escape_string($_POST['name'] ?? '');
    $location = $mysqli->real_escape_string($_POST['location'] ?? '');
    $address_full = $mysqli->real_escape_string($_POST['address_full'] ?? '');
    $city = $mysqli->real_escape_string($_POST['city'] ?? '');
    $province = $mysqli->real_escape_string($_POST['province'] ?? '');
    $region = $mysqli->real_escape_string($_POST['region'] ?? '');
    $contact = $mysqli->real_escape_string($_POST['contact'] ?? '');
    $pastor = $mysqli->real_escape_string($_POST['pastor'] ?? '');
    $email = $mysqli->real_escape_string($_POST['email'] ?? '');
    $date_organized = $mysqli->real_escape_string($_POST['date_organized'] ?? '');
    $facebook = $mysqli->real_escape_string($_POST['facebook'] ?? '');
    $mother_church = $mysqli->real_escape_string($_POST['mother_church'] ?? '');

    // Upload image
    $image = "";
    if(!empty($_FILES['image']['name'])){
        $basename = basename($_FILES['image']['name']);
        $safeName = preg_replace('/[^A-Za-z0-9\.\-_]/', '_', $basename);
        if(move_uploaded_file($_FILES['image']['tmp_name'], $imagesDir.$safeName)){
            $image = "images/".$safeName;
        }
    }

    // Insert into DB
    $stmt = $mysqli->prepare(
        "INSERT INTO churches 
        (name, location, address_full, city, province, region, contact, pastor, email, image, date_organized, facebook, mother_church)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param(
        "sssssssssssss",
        $name,
        $location,
        $address_full,
        $city,
        $province,
        $region,
        $contact,
        $pastor,
        $email,
        $image,
        $date_organized,
        $facebook,
        $mother_church
    );
   $stmt->execute();
$stmt->close();

/* ===============================
   AUTO ADD TO PASTORS TABLE
================================= */
if(!empty($pastor)){

    // Check muna kung existing na pastor para hindi ma-duplicate
    $check = $mysqli->prepare("SELECT id FROM pastors WHERE name=?");
    $check->bind_param("s", $pastor);
    $check->execute();
    $check->store_result();

    if($check->num_rows == 0){

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


    echo "<script>alert('Church added successfully');window.location='admin_churches.php';</script>";
    exit;
}

// Handle Delete Church
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);

    // 1. Delete the image
    $stmtSelect = $mysqli->prepare("SELECT image FROM churches WHERE id=?");
    $stmtSelect->bind_param("i", $id);
    $stmtSelect->execute();
    $res = $stmtSelect->get_result();
    if($row = $res->fetch_assoc()){
        if(!empty($row['image']) && file_exists(__DIR__.'/../'.$row['image'])){
            unlink(__DIR__.'/../'.$row['image']);
        }
    }
    $stmtSelect->close();

    // 2. Delete the church
    $stmt = $mysqli->prepare("DELETE FROM churches WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // 3. Reorder IDs sequentially
    $mysqli->query("SET @count = 0");
    $mysqli->query("UPDATE churches SET id = (@count:=@count+1) ORDER BY id ASC");
    $mysqli->query("ALTER TABLE churches AUTO_INCREMENT = 1");

    echo '<script>alert("Church deleted and IDs updated");window.location="admin_churches.php";</script>';
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Manage Churches</title>
<link rel="stylesheet" href="../css/style.css">
<style>
/* Buttons & table styling */
button, .btn { padding: 6px 12px; border-radius: 6px; border: none; cursor: pointer; font-weight: bold; transition: all 0.3s ease; }
.back-btn { display: inline-block; text-decoration: none; background-color: #1e3a8a; color: #fff; padding: 8px 15px; border-radius: 8px; margin-bottom: 20px; transition: background 0.3s ease; }
.back-btn:hover { background-color: #163070; }
.btn-edit, .btn-delete { background-color: #1e40af; color: #fff; padding: 6px 12px; border-radius: 5px; text-decoration: none; font-size: 0.9rem; margin-right: 8px; margin-top: 4px; margin-bottom: 4px; display: inline-block; transition: background 0.3s ease; }
.btn-edit:hover, .btn-delete:hover { background-color: #1e3a8a; color: #fff; }
.church-table { width: 100%; border-collapse: collapse; margin-top: 20px; overflow-x: auto; display: block; }
.church-table th, .church-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
.church-table th { background-color: #1e3a8a; color: #fff; }
.add-form input, .add-form textarea, .add-form select, .add-form button { display: block; width: 100%; margin-bottom: 12px; padding: 8px; border-radius: 6px; border: 1px solid #ccc; }
.add-form button { background-color: #1e3a8a; color: #fff; border: none; cursor: pointer; font-weight: bold; }
.add-form button:hover { background-color: #163070; }
.admin-churches { max-width: 1100px; margin: 30px auto; padding: 20px 30px; background-color: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px; }
.church-table img { max-height: 70px; border-radius: 6px; }
.add-form input, .add-form textarea, .add-form select, .add-form button { box-sizing: border-box; }
@media screen and (max-width: 768px) {
    .admin-churches { padding: 15px; margin: 15px; }
    .church-table td, .church-table th { font-size: 0.85rem; padding: 6px; }
}
</style>
</head>
<body>

<section class="admin-churches">
<a href="index.php" class="back-btn">⬅ Back Dashboard</a>
<h2>Manage Churches</h2>

<form method="POST" enctype="multipart/form-data" class="add-form">
<h3>Add New Church</h3>

<input type="text" name="name" placeholder="Church Name" required>
<input type="text" name="mother_church" placeholder="Mother Church (if any)">
<input type="hidden" name="city" id="city">
<input type="hidden" name="province" id="province">
<select name="region" id="region-select" >
    <option value="">Select Region</option>
    <option value="Luzon">Luzon</option>
    <option value="Visayas">Visayas</option>
    <option value="Mindanao">Mindanao</option>
</select>
<input type="text" name="location" placeholder="Location / Street">
<input type="text" name="contact" placeholder="Contact Number">
<input type="text" name="pastor" placeholder="Pastor / Leader">
<input type="email" name="email" placeholder="Email Address" >
<label for="date_organized">Date of Organize:</label>
<input type="date" name="date_organized" id="date_organized" >
<input type="url" name="facebook" placeholder="Facebook Page URL (optional)">
<input type="file" name="image" accept="image/*">
<button type="submit" name="add">Add Church</button>
</form>

<h3>Existing Churches</h3>
<table class="church-table">
<tr>
<th>ID</th>
<th>Image</th>
<th>Name</th>
<th>Mother Church</th>
<th>Location</th>
<th>Region</th>
<th>Pastor</th>
<th>Email</th>
<th>Date of Organize</th>
<th>FB Link</th>
<th>Actions</th> 
</tr>

<?php
$result = $mysqli->query("SELECT * FROM churches ORDER BY id ASC"); // ASC so numbering is sequential
$counter = 1;
while($row = $result->fetch_assoc()):
$img = !empty($row['image']) ? '../'.$row['image'] : '../images/placeholder.png';
$fbLink = !empty($row['facebook']) ? '<a href="'.$row['facebook'].'" target="_blank">FB Page</a>' : '';
?>
<tr>
<td><?= $counter++ ?></td>
<td><img src="<?= $img ?>" width="70"></td>
<td><?= htmlspecialchars($row['name']) ?></td>
<td><?= htmlspecialchars($row['mother_church']) ?></td>
<td><?= htmlspecialchars($row['location']) ?></td>
<td><strong><?= htmlspecialchars($row['region']) ?></strong></td>
<td><?= htmlspecialchars($row['pastor']) ?></td>
<td><?= htmlspecialchars($row['email']) ?></td>
<td><?= htmlspecialchars($row['date_organized']) ?></td>
<td><?= $fbLink ?></td>
<td>
<a href="edit_church.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
<a href="?delete=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Delete this church?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</table>
</section>

<!-- Google Maps Autocomplete -->
<input id="autocomplete" name="location" placeholder="">
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places"></script>
<script>
// Your existing Google Maps autocomplete JS remains unchanged
</script>

</body>
</html>
