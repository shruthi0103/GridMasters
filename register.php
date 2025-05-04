<?php
include 'db.php'; // Connect to database

// Check if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL Insert Statement
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashed_password);

    if ($stmt->execute()) {
        $success = "✅ Registration successful! <a href='login.php'>Login here</a>";
    } else {
        $error = "❌ Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- Registration Page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - GridMasters</title>

    <!-- Only your custom CSS -->
    <link rel="stylesheet" href="style-register.css">
</head>

<body class="register-page">

<!-- Simple Navbar -->
<nav class="navbar">
    <div class="nav-brand">GridMasters</div>
    <div class="nav-links">
        <a href="home.html">Home</a>
        <a href="login.php">Login</a>
        <a href="admin_login.php">Admin</a>
    </div>
</nav>

<!-- Registration Form -->
<div class="form-container">
    <h2>Register for GridMasters</h2>

    <!-- Show Success/Error Messages -->
    <?php if (!empty($success)) { ?>
        <div class="success-message"><?php echo $success; ?></div>
    <?php } ?>
    <?php if (!empty($error)) { ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php } ?>

    <form method="post" action="register.php">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Create Password" required>
        <button type="submit" class="button">Register</button>
    </form>
</div>

</body>
</html>
