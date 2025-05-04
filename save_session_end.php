<?php
session_start();
include 'db.php';

// Check if session ID exists
if (!isset($_SESSION['session_id'])) {
    echo "Session not started";
    exit();
}

$session_id = $_SESSION['session_id'];
$generations = isset($_POST['generations']) ? intval($_POST['generations']) : 0;

// Update the session end time and generations played
$stmt = $conn->prepare("UPDATE sessions SET end_time = NOW(), generations_played = ? WHERE id = ?");
$stmt->bind_param("ii", $generations, $session_id);

if ($stmt->execute()) {
    echo "Session ended successfully";
    unset($_SESSION['session_id']); // Clear session_id
} else {
    echo "Error: " . htmlspecialchars($stmt->error);
}

$stmt->close();
$conn->close();
?>
