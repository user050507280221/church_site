<?php
include '../config.php';
include 'header.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}
?>

<section style="padding:40px 20px; font-family:Arial, sans-serif;">
    <h2 style="text-align:center; color:#1e3a8a; margin-bottom:30px;">📬 Contact Messages</h2>

    <div style="max-width:1000px; margin:0 auto 50px auto; background:#fff; padding:30px; border-radius:12px; box-shadow:0 8px 20px rgba(0,0,0,0.2);">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f0f0f0;">
                    <th style="border:1px solid #ccc; padding:12px 10px;">ID</th>
                    <th style="border:1px solid #ccc; padding:12px 10px;">Name</th>
                    <th style="border:1px solid #ccc; padding:12px 10px;">Email</th>
                    <th style="border:1px solid #ccc; padding:12px 10px;">Message</th>
                    <th style="border:1px solid #ccc; padding:12px 10px;">Sent At</th>
                    <th style="border:1px solid #ccc; padding:12px 10px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $res = $mysqli->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
                if($res->num_rows > 0){
                    while($row = $res->fetch_assoc()){
                        echo "<tr style='background:#f9f9f9;'>
                                <td style='border:1px solid #ccc; padding:12px 10px;'>{$row['id']}</td>
                                <td style='border:1px solid #ccc; padding:12px 10px;'>{$row['name']}</td>
                                <td style='border:1px solid #ccc; padding:12px 10px;'>{$row['email']}</td>
                                <td style='border:1px solid #ccc; padding:12px 10px;'>{$row['message']}</td>
                                <td style='border:1px solid #ccc; padding:12px 10px;'>{$row['created_at']}</td>
                                <td style='border:1px solid #ccc; padding:12px 10px; text-align:center;'>
                                    <a href='delete_contact.php?id={$row['id']}' style='color:white; background:#d32f2f; padding:6px 12px; border-radius:6px; text-decoration:none; transition:0.3s;' onclick=\"return confirm('Are you sure you want to delete this message?');\">Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center; padding:20px;'>No contact messages yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<?php include 'footer.php'; ?>
