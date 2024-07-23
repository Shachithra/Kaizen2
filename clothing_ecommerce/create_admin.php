<!-- create_admin.php -->
<?php
include 'includes/db_connect.php';

$username = 'admin';
$password = 'password'; // Replace with the desired password

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert the admin user into the database
$sql = "INSERT INTO admin (username, password) VALUES ('$username', '$hashed_password')";

if ($conn->query($sql) === TRUE) {
    echo "Admin user created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
