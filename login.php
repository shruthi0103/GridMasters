<?php
session_start();
include 'db.php'; // Connect to database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $email;

            header("Location: index.html");
            exit();
        } else {
            $error = "❌ Incorrect password.";
        }
    } else {
        $error = "❌ No account found with that email.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- Login Page (NO Bootstrap) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - GridMasters</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Custom Login Page CSS -->
    <link rel="stylesheet" href="style-login.css">
</head>
<body class="login-page">

<!-- Simple Navbar -->
<nav class="navbar">
    <div class="nav-brand">GridMasters</div>
    <div class="nav-links">
        <a href="home.html">Home</a>
        <a href="register.php">Register</a>
        <a href="admin_login.php">Admin</a>
    </div>
</nav>

<!-- Login Card -->
<div class="login-container">
    <h2>Login to GridMasters</h2>

    <!-- Show Error Message -->
    <?php if (!empty($error)) { ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php } ?>

    <form method="post" action="login.php">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <small style="color: grey; font-size: 14px;">Hint: Email-john12@gmail.com</small>
        <small style="color: grey; font-size: 14px;">Password-john@1996</small><br><br>
        <button type="submit" class="button">Login</button>
    </form>
</div>

</body>
</html>
