<?php
include 'includes/db_connect.php';
include 'header.php';

// Fetch all men's products
$result = $conn->query("SELECT * FROM products WHERE category='Men'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Men's Clothing</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/main.js" defer></script>

</head>
<body>
    <main class="container">
        <div class="product-list">
            <?php while ($product = $result->fetch_assoc()) { ?>
                <div class="product">
                    <img src="products/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                    <div class="content">
                        <h3><?php echo $product['name']; ?></h3>
                        <p><?php echo $product['description']; ?></p>
                        <p>$<?php echo $product['price']; ?></p>
                        <a href="product.php?id=<?php echo $product['id']; ?>" class="btn">View Product</a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
<?php
$conn->close();
?>
