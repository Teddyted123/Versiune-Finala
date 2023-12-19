<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "produse_db";

if(!$conn = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname))
{

    die("failed to connect!");
}

?>