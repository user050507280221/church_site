<?php
if (isset($_POST['upload'])) {
  $targetDir = "uploads/";
  $fileName = basename($_FILES["sermon"]["name"]);
  $targetFile = $targetDir . $fileName;

  if (move_uploaded_file($_FILES["sermon"]["tmp_name"], $targetFile)) {
    echo "<script>alert('Sermon uploaded successfully!'); window.history.back();</script>";
  } else {
    echo "<script>alert('Upload failed. Try again.'); window.history.back();</script>";
  }
}
?>
