<?php
session_start();
session_destroy();
header("Location: /church_site/index.php");

exit;
?>
