<?php
$host = 'localhost';
$db   = 'infurnest';
$user = 'root';
$pass = '';  // XAMPP default

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>