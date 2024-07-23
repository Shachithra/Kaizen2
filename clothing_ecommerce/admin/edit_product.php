<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db_connect.php'; // Database connection file

$id = $_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $size = $_POST['size'];
    $quantity = $_POST['quantity'];

    // Handle image upload
    if ($_FILES['image']['name']) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../products/' . $image_name;
        move_uploaded_file($image_tmp_name, $image_folder);
    } else {
        $image_name = $product['image'];
    }

    // Update database
    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, size=?, quantity=?, image=? WHERE id=?");
    $stmt->bind_param("ssdissi", $name, $description, $price, $size, $quantity, $image_name, $id);
    $stmt->execute();
    $stmt->close();

    // Redirect or display success message
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Edit Product</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="add_product.php">Add Product</a></li>
                <li><a href="manage_categories.php">Manage Categories</a></li>
                <li><a href="users.php">Manage Users</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main class="admin-container">
        <form method="POST" action="edit_product.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Product Name:</label>
                <input type="text" name="name" id="name" value="<?php echo $product['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Product Description:</label>
                <textarea name="description" id="description" required><?php echo $product['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="price">Product Price:</label>
                <input type="number" step="0.01" name="price" id="price" value="<?php echo $product['price']; ?>" required>
            </div>
            <div class="form-group">
                <label for="size">Size:</label>
                <select name="size" id="size" required>
                    <option value="S" <?php if ($product['size'] == 'S') echo 'selected'; ?>>S</option>
                    <option value="M" <?php if ($product['size'] == 'M') echo 'selected'; ?>>M</option>
                    <option value="L" <?php if ($product['size'] == 'L') echo 'selected'; ?>>L</option>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" id="quantity" min="1" value="<?php echo $product['quantity']; ?>" required>
            </div>
            <div class="form-group">
                <label for="image">Product Image:</label>
                <input type="file" name="image" id="image" accept="image/*">
            </div>
            <button type="submit" name="edit" class="btn">Update Product</button>
        </form>
    </main>
</body>
</html>
<?php
$conn->close();
?>
