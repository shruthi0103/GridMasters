<?php
include 'db.php';
session_start();

// Check Admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin.php");
    exit();
}

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Save a success message into session
        $_SESSION['message'] = "User deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting user: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();

// Always redirect back to admin dashboard
header("Location: admin.php");
exit();
?>
