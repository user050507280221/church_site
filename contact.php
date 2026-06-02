<?php
include 'config.php';
include 'header.php';

$success = '';
$error = '';

if(isset($_POST['send_message'])){
    $name = $mysqli->real_escape_string($_POST['name']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $message = $mysqli->real_escape_string($_POST['message']);

    // File upload handling (Original Logic Maintained)
    $file_name = '';
    if(isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0){
        $uploadDir = 'uploads/';
        if(!is_dir($uploadDir)){
            mkdir($uploadDir, 0777, true);
        }

        $fileTmp = $_FILES['attachment']['tmp_name'];
        $fileName = basename($_FILES['attachment']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExt = ['jpg','jpeg','png','pdf','doc','docx'];
        if(in_array($fileExt, $allowedExt)){
            $newFileName = time() . '_' . $fileName; 
            if(move_uploaded_file($fileTmp, $uploadDir . $newFileName)){
                $file_name = $mysqli->real_escape_string($newFileName);
            } else {
                $error = "Failed to upload file.";
            }
        } else {
            $error = "File type not allowed. Allowed types: jpg, jpeg, png, pdf, doc, docx";
        }
    }

    if(!$error){
        $mysqli->query("INSERT INTO contact_messages (name,email,message,file_name) VALUES ('$name','$email','$message','$file_name')");
        $success = "Your message has been sent successfully!";
    }
}
?>

<!-- ABA STYLE HERO -->
<section class="contact-hero">
    <div class="hero-inner">
        <span class="category-eyebrow">GET IN TOUCH</span>
        <h1>Contact Us</h1>
        <p class="intro">Have questions or want to learn more? Send us a message and our team will get back to you shortly.</p>
    </div>
</section>

<!-- CONTACT FORM SECTION -->
<section class="contact-form-section">
    <div class="contact-container">
        <?php if($success): ?>
            <div class="status-msg success-msg">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="status-msg error-msg">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                </svg>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="aba-form">
            <div class="form-group">
                <label>Your Name</label>
                <input type="text" name="name" placeholder="Full Name" required>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="email@example.com" required>
            </div>

            <div class="form-group">
                <label>Message</label>
                <textarea name="message" placeholder="How can we help you?" rows="6" required></textarea>
            </div>

            <div class="form-group">
                <label class="file-label">Attachment (Optional)</label>
                <input type="file" name="attachment" class="file-input">
                <span class="file-hint">Allowed: JPG, PNG, PDF, DOC (Max 5MB)</span>
            </div>

            <button type="submit" name="send_message" class="aba-btn-submit">
                Send Message
            </button>
        </form>
    </div>
</section>

<?php include 'footer.php'; ?>