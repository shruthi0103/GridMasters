<?php
$servername = "localhost";         // Always localhost on CODD
$username = "sledalla1";            // Your CODD username
$password = "sledalla1";  // Your MySQL password
$database = "sledalla1";            // Your database name (same as username)

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully"; // (You can uncomment this for testing)
?>
