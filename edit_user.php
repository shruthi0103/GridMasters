<?php
include 'db.php';
session_start();

// Admin check
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

$error = '';

// Fetch user info
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($name, $email);
    $stmt->fetch();
    $stmt->close();
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $id = intval($_POST['id']);
    $new_name = trim($_POST['name']);
    $new_email = trim($_POST['email']);

    if (!empty($new_name) && !empty($new_email)) {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $new_name, $new_email, $id);
        if ($stmt->execute()) {
            header("Location: admin.php");
            exit();
        } else {
            $error = "❌ Failed to update user.";
        }
        $stmt->close();
    } else {
        $error = "❌ Fields cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User - GridMasters Admin</title>
    <link rel="stylesheet" href="style-admin.css">
</head>
<body class="admin-page">

<!-- Simple Navbar -->
<nav class="navbar">
    <div class="nav-brand">GridMasters Admin</div>
    <div class="nav-links">
        <a href="home.html">Home</a>
        <a href="index.html">Play Game</a>
        <a href="admin.php">Dashboard</a>
        <a href="admin_logout.php">Logout</a>
    </div>
</nav>

<div class="form-container">
    <h2>Edit User</h2>

    <?php if (!empty($error)) { ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php } ?>

    <form method="POST" action="edit_user.php">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        
        <label>Name:</label><br>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br><br>

        <button type="submit" name="update_user" class="button">Update User</button>
    </form>
</div>

</body>
</html>

<?php
$conn->close();
?>
