<?php
session_start();
$admin_password = "admin123"; // Your admin password

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_password = $_POST['admin_pass'];

    if ($entered_password === $admin_password) {
        $_SESSION['is_admin'] = true;
        header("Location: admin.php"); // Redirect to Admin Dashboard
        exit();
    } else {
        $error = "Incorrect Admin Password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - GridMasters</title>

    <!-- Only your custom style -->
    <link rel="stylesheet" href="style-admin-login.css">
</head>
<body class="admin-login-page">

<!-- Simple Navbar -->
<nav class="navbar">
    <div class="nav-brand">GridMasters</div>
    <div class="nav-links">
        <a href="home.html">Home</a>
        <a href="login.php">User Login</a>
        <a href="register.php">Register</a>
    </div>
</nav>

<!-- Admin Login Form -->
<div class="form-container">
    <h2>Admin Login</h2>

    <!-- Show error if wrong password -->
    <?php if (!empty($error)) { ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php } ?>

    <form method="post">
        <input type="password" name="admin_pass" placeholder="Enter Admin Password" required>
        <small style="color: grey; font-size: 14px;">Hint: admin123</small><br><br>
        <button type="submit" class="button">Login as Admin</button>
        
    </form>
</div>

</body>
</html>
