<?php
session_start();
if(!isset($_SESSION['admin']) || $_SESSION['admin'] !== 'admin'){
    header("Location: login.php");
    exit;
}

include '../config.php';

// Ensure events folder exists
$eventDir = __DIR__ . '/../events/';
if (!file_exists($eventDir)) mkdir($eventDir, 0755, true);

// ADD EVENT
if(isset($_POST['add'])){
    $title = $_POST['title'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    $imagePath = '';

    if(!empty($_FILES['image']['name'])){
        $fileName = basename($_FILES['image']['name']);
        $safeName = preg_replace('/[^A-Za-z0-9\.\-_]/', '_', $fileName);
        $targetPath = $eventDir . $safeName;
        if(move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)){
            $imagePath = 'events/' . $safeName;
        }
    }

    $stmt = $mysqli->prepare("INSERT INTO events (title, date, description, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $date, $description, $imagePath);
    $stmt->execute();

    echo "<script>alert('Event added successfully!');window.location='admin_events.php';</script>";
}

// EDIT EVENT
if(isset($_POST['update'])){
    $id = intval($_POST['id']);
    $title = $_POST['title'];
    $date = $_POST['date'];
    $description = $_POST['description'];

    if(!empty($_FILES['image']['name'])){
        $fileName = basename($_FILES['image']['name']);
        $safeName = preg_replace('/[^A-Za-z0-9\.\-_]/', '_', $fileName);
        $targetPath = $eventDir . $safeName;
        if(move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)){
            $imagePath = 'events/' . $safeName;
            $mysqli->query("UPDATE events SET image='$imagePath' WHERE id=$id");
        }
    }

    $stmt = $mysqli->prepare("UPDATE events SET title=?, date=?, description=? WHERE id=?");
    $stmt->bind_param("sssi", $title, $date, $description, $id);
    $stmt->execute();

    echo "<script>alert('Event updated successfully!');window.location='admin_events.php';</script>";
}

// DELETE EVENT
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $event = $mysqli->query("SELECT image FROM events WHERE id=$id")->fetch_assoc();
    if($event['image'] && file_exists('../'.$event['image'])){
        unlink('../'.$event['image']);
    }
    $mysqli->query("DELETE FROM events WHERE id=$id");
    echo "<script>alert('Event deleted successfully!');window.location='admin_events.php';</script>";
}

// Fetch events for listing
$events = $mysqli->query("SELECT * FROM events ORDER BY date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Events</title>
<link rel="stylesheet" href="../css/style.css">
<style>
body { font-family: Arial; background:#f7f7f7; margin:0; padding:0; }
.container { max-width:1000px; margin:40px auto; background:#fff; padding:30px; border-radius:10px; }
h2 { text-align:center; color:#1e3a8a; margin-bottom:20px; }
form input, form textarea { width:100%; padding:10px; margin:8px 0; border-radius:6px; border:1px solid #ccc; }
form button { padding:10px 20px; background:#1e3a8a; color:white; border:none; border-radius:6px; cursor:pointer; }
form button:hover { background:#162d6a; }
table { width:100%; border-collapse:collapse; margin-top:20px; }
th, td { border:1px solid #ddd; padding:10px; text-align:center; }
th { background:#1e3a8a; color:white; }
.btn-delete { background:#d32f2f; color:white; padding:5px 10px; border-radius:4px; text-decoration:none; }
.btn-edit { background:#4caf50; color:white; padding:5px 10px; border-radius:4px; text-decoration:none; }
.back-btn { display:inline-block; margin-bottom:15px; padding:8px 14px; background:#4f46e5; color:white; border-radius:6px; text-decoration:none; }
img { max-width:100px; border-radius:4px; }
</style>
</head>
<body>

<div class="container">
<h2>Manage Events</h2>

<a href="index.php" class="back-btn">⬅ Back to Dashboard</a>

<!-- Add Event Form -->
<form method="POST" enctype="multipart/form-data">
    <h3>Add New Event</h3>
    <input type="text" name="title" placeholder="Event Title" required>
    <input type="date" name="date" required>
    <textarea name="description" placeholder="Event Description" rows="4" required></textarea>
    <input type="file" name="image" accept=".jpg,.jpeg,.png">
    <button type="submit" name="add">Add Event</button>
</form>

<!-- Event List -->
<h3>Existing Events</h3>
<table>
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Date</th>
    <th>Description</th>
    <th>Image</th>
    <th>Action</th>
</tr>
<?php while($row = $events->fetch_assoc()): ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo htmlspecialchars($row['title']); ?></td>
    <td><?php echo $row['date']; ?></td>
    <td><?php echo htmlspecialchars($row['description']); ?></td>
    <td>
        <?php if($row['image'] && file_exists('../'.$row['image'])): ?>
            <img src="../<?php echo $row['image']; ?>">
        <?php else: ?>
            N/A
        <?php endif; ?>
    </td>
    <td>
        <a href="?edit=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
        <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this event?')" class="btn-delete">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>

<?php
// Edit form
if(isset($_GET['edit'])){
    $id = intval($_GET['edit']);
    $edit = $mysqli->query("SELECT * FROM events WHERE id=$id")->fetch_assoc();
    if($edit):
?>
<div style="margin-top:20px; background:#fef9c3; padding:20px; border-radius:8px;">
<a href="admin_events.php" class="back-btn">⬅ Cancel</a>

<h3>Edit Event (ID: <?php echo $id; ?>)</h3>
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <input type="text" name="title" value="<?php echo htmlspecialchars($edit['title']); ?>" required>
    <input type="date" name="date" value="<?php echo $edit['date']; ?>" required>
    <textarea name="description" rows="4" required><?php echo htmlspecialchars($edit['description']); ?></textarea>
    <input type="file" name="image" accept=".jpg,.jpeg,.png">
    <button type="submit" name="update">Update Event</button>
</form>
</div>
<?php endif; } ?>

</div>

</body>
</html>
