<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

// Show errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../config.php'; // Make sure this connects to your DB

// --- ADD BOOK ---
if(isset($_POST['add_book'])){
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $description = trim($_POST['description']);

    $image = "";
    if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){
        $target_dir = "../images/books/";
        if(!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $image = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image);
    }

    $stmt = $mysqli->prepare("INSERT INTO books (title, author, description, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $author, $description, $image);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_books.php");
    exit;
}

// --- EDIT BOOK ---
if(isset($_POST['edit_book'])){
    $id = intval($_POST['id']);
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $description = trim($_POST['description']);

    // Get old image
    $res = $mysqli->query("SELECT image FROM books WHERE id=$id");
    if($res->num_rows == 0){
        die("Book not found.");
    }
    $old = $res->fetch_assoc();
    $image = $old['image'];

    if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){
        if($image && file_exists("../images/books/".$image)){
            unlink("../images/books/".$image);
        }
        $target_dir = "../images/books/";
        $image = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image);
    }

    $stmt = $mysqli->prepare("UPDATE books SET title=?, author=?, description=?, image=? WHERE id=?");
    $stmt->bind_param("ssssi", $title, $author, $description, $image, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_books.php");
    exit;
}

// --- DELETE BOOK ---
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $res = $mysqli->query("SELECT image FROM books WHERE id=$id");
    if($res->num_rows > 0){
        $row = $res->fetch_assoc();
        if($row['image'] && file_exists("../images/books/".$row['image'])){
            unlink("../images/books/".$row['image']);
        }
    }
    $mysqli->query("DELETE FROM books WHERE id=$id");
    header("Location: admin_books.php");
    exit;
}

// --- FETCH BOOKS ---
$books = $mysqli->query("SELECT * FROM books ORDER BY id DESC");

// --- FETCH BOOK TO EDIT ---
$edit_book = null;
if(isset($_GET['edit'])){
    $id = intval($_GET['edit']);
    $res = $mysqli->query("SELECT * FROM books WHERE id=$id");
    if($res && $res->num_rows > 0){
        $edit_book = $res->fetch_assoc();
    }
}
?>

<?php include 'header.php'; ?>

<section style="padding:40px 20px; font-family:Arial, sans-serif;">
    <h2 style="text-align:center; color:#1e3a8a; margin-bottom:30px;">📚 Manage Books</h2>

    <!-- ADD / EDIT FORM -->
    <div style="max-width:600px; margin:0 auto 50px auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 8px 20px rgba(0,0,0,0.1);">
        <h3 style="color:#1e3a8a; margin-bottom:15px;"><?= $edit_book ? "Edit Book" : "Add New Book" ?></h3>
        <form action="" method="POST" enctype="multipart/form-data">
            <?php if($edit_book): ?>
                <input type="hidden" name="id" value="<?= $edit_book['id'] ?>">
            <?php endif; ?>

            <label>Book Title</label>
            <input type="text" name="title" required value="<?= $edit_book ? htmlspecialchars($edit_book['title']) : "" ?>" style="width:100%; padding:10px; margin-bottom:10px; border-radius:5px; border:1px solid #ccc;">

            <label>Author</label>
            <input type="text" name="author" required value="<?= $edit_book ? htmlspecialchars($edit_book['author']) : "" ?>" style="width:100%; padding:10px; margin-bottom:10px; border-radius:5px; border:1px solid #ccc;">

            <label>Description</label>
            <textarea name="description" rows="4" style="width:100%; padding:10px; margin-bottom:10px; border-radius:5px; border:1px solid #ccc;"><?= $edit_book ? htmlspecialchars($edit_book['description']) : "" ?></textarea>

            <label>Book Cover Image</label>
            <input type="file" name="image" accept="image/*" style="margin-bottom:15px;">
            <?php if($edit_book && $edit_book['image'] && file_exists("../images/books/".$edit_book['image'])): ?>
                <p>Current Image:</p>
                <img src="../images/books/<?= $edit_book['image'] ?>" alt="Book Image" style="width:120px; height:160px; object-fit:cover; margin-bottom:10px; border-radius:5px;">
            <?php endif; ?>

            <button type="submit" name="<?= $edit_book ? "edit_book" : "add_book" ?>" style="padding:10px 20px; background:#1e3a8a; color:white; border:none; border-radius:5px; cursor:pointer;"><?= $edit_book ? "Update Book" : "Add Book" ?></button>
            <?php if($edit_book): ?>
                <a href="admin_books.php" style="padding:10px 20px; background:#e53e3e; color:white; border-radius:5px; text-decoration:none; margin-left:10px;">Cancel</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Existing Books -->
    <div style="display:flex; flex-wrap:wrap; gap:20px; justify-content:center;">
        <?php while($book = $books->fetch_assoc()): ?>
            <div style="background:#fff; border-radius:10px; padding:15px; width:220px; box-shadow:0 8px 20px rgba(0,0,0,0.1); text-align:center;">
                <?php if($book['image'] && file_exists("../images/books/".$book['image'])): ?>
                    <img src="../images/books/<?= $book['image'] ?>" alt="<?= htmlspecialchars($book['title']) ?>" style="width:150px; height:200px; object-fit:cover; border-radius:5px; margin-bottom:10px;">
                <?php else: ?>
                    <img src="../images/book.png" alt="No Image" style="width:150px; height:200px; object-fit:cover; border-radius:5px; margin-bottom:10px;">
                <?php endif; ?>
                <h4 style="color:#1e3a8a; font-size:1rem; margin-bottom:5px;"><?= htmlspecialchars($book['title']) ?></h4>
<p style="color:#555; margin-bottom:10px; font-size:0.9rem;"><strong>By:</strong> <?= htmlspecialchars($book['author']) ?></p>
                <div style="display:flex; justify-content:center; gap:10px;">
                    <a href="?edit=<?= $book['id'] ?>" style="color:white; background:#3182ce; padding:5px 10px; border-radius:5px; text-decoration:none;">Edit</a>
                    <a href="?delete=<?= $book['id'] ?>" onclick="return confirm('Are you sure you want to delete this book?');" style="color:white; background:#e53e3e; padding:5px 10px; border-radius:5px; text-decoration:none;">Delete</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<?php include 'footer.php'; ?>
