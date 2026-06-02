<?php
// Database configuration
$host = "localhost";
$user = "root";       // default XAMPP user
$pass = "";           // default XAMPP password (usually blank)
$db   = "church_site";

// Create connection
$mysqli = new mysqli($host, $user, $pass, $db);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
