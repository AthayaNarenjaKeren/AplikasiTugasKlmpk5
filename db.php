<?php
// Start session di baris paling atas sebelum apapun
session_start();

// Database connection settings
$servername = "localhost";
$username = "root"; 
$password = "";    
$dbname = "aplikasitugas";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
