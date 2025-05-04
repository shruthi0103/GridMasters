<?php
include 'db.php';
session_start();
if (isset($_SESSION['message'])) {
    echo "<div style='color: green; font-weight: bold; text-align: center; margin: 10px;'>" . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']);
}
// Simple hardcoded Admin Check
$admin_password = "admin123";

if (!isset($_SESSION['is_admin'])) {
    if (isset($_POST['admin_pass'])) {
        if ($_POST['admin_pass'] === $admin_password) {
            $_SESSION['is_admin'] = true;
        } else {
            $error = "Incorrect Admin Password.";
        }
    } else {
        // Show Admin Login Form
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Admin Login - GridMasters</title>
            <link rel="stylesheet" href="style-admin.css">
        </head>
        <body>
            <h2>Admin Login</h2>
            <?php if (!empty($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
            <form method="post">
                <label>Admin Password:</label><br>
                <input type="password" name="admin_pass" required><br>

                <!-- Password Hint -->
                

                <button type="submit">Login as Admin</button>
            </form>

        </body>
        </html>
        <?php
        exit();
    }
}

// If already logged in as Admin, show dashboard
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - GridMasters</title>
    <link rel="stylesheet" href="style-admin.css">
</head>
<body>

    <h2>Registered Users</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        <?php
        $result = $conn->query("SELECT id, name, email FROM users");
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['name']}</td>";
                echo "<td>{$row['email']}</td>";
                echo "<td><a href='edit_user.php?id={$row['id']}'>Edit</a></td>";
                echo "<td><a href='delete_user.php?id={$row['id']}' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No users found</td></tr>";
        }
        ?>
    </table>

    <h2>Game Sessions</h2>
    <table>
        <tr>
            <th>Session ID</th>
            <th>User ID</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Generations Played</th>
        </tr>
        <?php
        $result = $conn->query("SELECT id, user_id, start_time, end_time, generations_played FROM sessions");
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['user_id']}</td>";
                echo "<td>{$row['start_time']}</td>";
                echo "<td>{$row['end_time']}</td>";
                echo "<td>{$row['generations_played']}</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No sessions found</td></tr>";
        }
        ?>
    </table>

</body>
</html>

<?php
$conn->close();
?>
