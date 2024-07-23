<!-- admin/delete_user.php -->
<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db_connect.php'; // Database connection file

$id = $_GET['id'];
$conn->query("DELETE FROM users WHERE id=$id");

header("Location: users.php");
$conn->close();
?>
