<!-- product.php -->
<?php
include 'includes/db_connect.php';

$id = $_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'header.php'; ?>
<main>
    <div class="product-detail">
        <div class="product-images">
            <img src="products/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
        </div>
        <div class="product-info">
            <h2><?php echo $product['name']; ?></h2>
            <p><?php echo $product['description']; ?></p>
            <p class="price">$<?php echo $product['price']; ?></p>
            <form method="POST" action="cart.php" class="product-form">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <label for="size">Size:</label>
                <select name="size" id="size">
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                </select>
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" id="quantity" value="1" min="1">
                <button type="submit" name="add" class="btn">Add to Cart</button>
            </form>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
<?php
$conn->close();
?>
