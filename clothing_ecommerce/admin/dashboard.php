<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db_connect.php'; // Database connection file

// Fetch all products
$products_result = $conn->query("SELECT * FROM products");

// Fetch all orders
$orders_query = "SELECT orders.id, users.username, products.name, orders.size, orders.quantity, orders.total_price, orders.order_date 
                 FROM orders 
                 JOIN users ON orders.user_id = users.id 
                 JOIN products ON orders.product_id = products.id";
$orders_result = $conn->query($orders_query);

if (!$orders_result) {
    echo "Error fetching orders: " . $conn->error;
    exit();
}

// Fetch total sales
$total_sales_result = $conn->query("SELECT SUM(total_price) as total_sales FROM orders");
$total_sales = $total_sales_result->fetch_assoc()['total_sales'];

// Fetch number of products
$num_products_result = $conn->query("SELECT COUNT(*) as num_products FROM products");
$num_products = $num_products_result->fetch_assoc()['num_products'];

// Fetch number of users
$num_users_result = $conn->query("SELECT COUNT(*) as num_users FROM users");
$num_users = $num_users_result->fetch_assoc()['num_users'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
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
        <h2>Welcome to Admin Dashboard</h2>
        <h3>Statistics</h3>
        <p>Total Sales: $<?php echo $total_sales; ?></p>
        <p>Number of Products: <?php echo $num_products; ?></p>
        <p>Number of Users: <?php echo $num_users; ?></p>
        <h3>Product List</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = $products_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo $product['description']; ?></td>
                        <td>$<?php echo $product['price']; ?></td>
                        <td><?php echo $product['size']; ?></td>
                        <td><?php echo $product['quantity']; ?></td>
                        <td><img src="../products/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="admin-product-img"></td>
                        <td class="action-btns">
                            <a class="btn" href="edit_product.php?id=<?php echo $product['id']; ?>">Edit</a>
                            <a class="btn" href="delete_product.php?id=<?php echo $product['id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <h3>Orders List</h3>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Username</th>
                    <th>Product Name</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = $orders_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $order['id']; ?></td>
                        <td><?php echo $order['username']; ?></td>
                        <td><?php echo $order['name']; ?></td>
                        <td><?php echo $order['size']; ?></td>
                        <td><?php echo $order['quantity']; ?></td>
                        <td>$<?php echo $order['total_price']; ?></td>
                        <td><?php echo $order['order_date']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>
</body>
</html>
<?php
$conn->close();
?>
