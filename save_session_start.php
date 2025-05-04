<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in";
    exit();
}

$user_id = $_SESSION['user_id'];

// Insert new session
$stmt = $conn->prepare("INSERT INTO sessions (user_id, start_time) VALUES (?, NOW())");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    $_SESSION['session_id'] = $conn->insert_id; // Save session ID
    echo "Session started successfully";
} else {
    echo "Error: " . htmlspecialchars($stmt->error);
}

$stmt->close();
$conn->close();
?>
