<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "produse_db";

// Crează conexiunea
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
    die("Failed to connect to the database: " . mysqli_connect_error());
}
?>
