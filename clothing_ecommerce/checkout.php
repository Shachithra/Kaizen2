<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db_connect.php';
$user_id = $_SESSION['user'];
$cart_products = [];
$total_price = 0;

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_column($_SESSION['cart'], 'product_id'));

    if (empty($ids)) {
        echo "No product IDs in the cart.";
        exit();
    }

    $query = "SELECT * FROM products WHERE id IN ($ids)";
    $result = $conn->query($query);

    if (!$result) {
        echo "Error: " . $conn->error;
        exit();
    }

    while ($row = $result->fetch_assoc()) {
        foreach ($_SESSION['cart'] as $cart_item) {
            if ($cart_item['product_id'] == $row['id']) {
                $row['size'] = $cart_item['size'];
                $row['quantity'] = $cart_item['quantity'];
                $row['total_price'] = $row['price'] * $cart_item['quantity']; // Calculate total price for the item
                $cart_products[] = $row;
                $total_price += $row['total_price'];
                break;
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($cart_products as $product) {
        $product_id = $product['id'];
        $quantity = $product['quantity'];
        $size = $product['size'];
        $total_product_price = $product['price'] * $quantity;
        $conn->query("INSERT INTO orders (user_id, product_id, quantity, size, total_price) VALUES ($user_id, $product_id, $quantity, '$size', $total_product_price)");
    }
    // Clear the cart
    unset($_SESSION['cart']);
    $_SESSION['total_price'] = $total_price; // Save the total price in the session
    header("Location: pay_now.php"); // Redirect to the pay now page
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/main.js" defer></script>
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <h2>Order Summary</h2>
        <div class="order-summary">
            <?php if (!empty($cart_products)) { ?>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Size</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_products as $product) { ?>
                            <tr>
                                <td><?php echo $product['name']; ?></td>
                                <td><?php echo $product['description']; ?></td>
                                <td>$<?php echo $product['price']; ?></td>
                                <td><?php echo $product['size']; ?></td>
                                <td><?php echo $product['quantity']; ?></td>
                                <td class="total-price">$<?php echo $product['total_price']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <form method="POST" action="checkout.php">
                    <button type="submit" class="btn">Confirm Order</button>
                </form>
            <?php } else { ?>
                <p>Your cart is empty.</p>
            <?php } ?>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
<?php
$conn->close();
?>
