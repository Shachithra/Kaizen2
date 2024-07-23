<!-- admin/delete_product.php -->
<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db_connect.php'; // Database connection file

$id = $_GET['id'];
$conn->query("DELETE FROM products WHERE id=$id");

header("Location: dashboard.php");
$conn->close();
?>
