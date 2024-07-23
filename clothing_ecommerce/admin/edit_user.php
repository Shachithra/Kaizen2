<!-- admin/edit_user.php -->
<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db_connect.php'; // Database connection file

$id = $_GET['id'];
$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET username='$username', password='$hashed_password' WHERE id=$id");
    } else {
        $conn->query("UPDATE users SET username='$username' WHERE id=$id");
    }
    header("Location: users.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <main class="admin-container">
        <form method="POST" action="edit_user.php?id=<?php echo $id; ?>">
            <h2>Edit User</h2>
            <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
            <input type="password" name="password" placeholder="New Password (optional)">
            <button type="submit">Update User</button>
        </form>
    </main>
</body>
</html>
<?php
$conn->close();
?>
