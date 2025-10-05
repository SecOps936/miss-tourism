<?php
$servername = "localhost";
$username = "heri"; 
$password = "1234"; 
$dbname = "miss_tourism";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
