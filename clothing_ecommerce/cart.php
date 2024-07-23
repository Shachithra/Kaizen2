<?php
session_start();
include 'includes/db_connect.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Clean up the session cart to ensure all items have a consistent structure
$_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) {
    return is_array($item) && isset($item['product_id'], $item['size'], $item['quantity']);
});

// Add new item to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $size = $_POST['size'];
    $quantity = $_POST['quantity'];
    
    $item_found = false;

    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id && $item['size'] == $size) {
            $item['quantity'] += $quantity;
            $item_found = true;
            break;
        }
    }

    // If item is not found, add as a new item
    if (!$item_found) {
        $_SESSION['cart'][] = [
            'product_id' => $product_id,
            'size' => $size,
            'quantity' => $quantity
        ];
    }
}

$cart_products = [];
$total_price = 0;

if (!empty($_SESSION['cart'])) {
    // Collect product IDs from the cart
    $product_ids = array_column($_SESSION['cart'], 'product_id');
    $ids = implode(',', array_unique($product_ids));

    if (!empty($ids)) {
        $result = $conn->query("SELECT * FROM products WHERE id IN ($ids)");

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                foreach ($_SESSION['cart'] as $cart_item) {
                    if ($cart_item['product_id'] == $row['id']) {
                        $found = false;
                        // Check if the item with the same size is already in the cart products
                        foreach ($cart_products as &$product) {
                            if ($product['id'] == $row['id'] && $product['size'] == $cart_item['size']) {
                                $product['quantity'] += $cart_item['quantity'];
                                $found = true;
                                break;
                            }
                        }
                        // If not found, add new product with size and quantity
                        if (!$found) {
                            $cart_products[] = array_merge($row, [
                                'size' => $cart_item['size'],
                                'quantity' => $cart_item['quantity']
                            ]);
                        }
                        $total_price += $row['price'] * $cart_item['quantity'];
                    }
                }
            }
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <h2>Your Cart</h2>
        <div class="cart-list">
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
                                <td>$<?php echo $product['price'] * $product['quantity']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <a href="checkout.php" class="btn">Proceed to Checkout</a>
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
