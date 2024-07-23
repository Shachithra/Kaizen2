<?php
session_start();
include 'includes/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAIZEN Clothing E-commerce</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/main.js" defer></script>
</head>
<body>
<?php include 'header.php'; ?>
    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <h2>Welcome to KAIZEN</h2>
                <p>Discover the latest trends in fashion</p>
            </div>
        </section>

        <!-- Featured Categories -->
        <section class="featured-categories">
            <div class="category-item" id="category-men">
                <div class="category-overlay">
                    <div class="category-content">
                        <h3>SHOP MENS</h3>
                        <a href="men.php" class="btn">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="category-item" id="category-women">
                <div class="category-overlay">
                    <div class="category-content">
                        <h3>SHOP WOMENS</h3>
                        <a href="women.php" class="btn">Shop Now</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="featured-products">
            <div class="container">
                <h2>Featured Products</h2>
                <div class="product-list">
                    <?php
                    // Fetch featured products (for simplicity, we fetch the first 4 products)
                    $result = $conn->query("SELECT * FROM products LIMIT 4");
                    while ($product = $result->fetch_assoc()) { ?>
                        <div class="product">
                            <img src="products/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                            <div class="content">
                                <h3><?php echo $product['name']; ?></h3>
                                <p><?php echo $product['description']; ?></p>
                                <p>$<?php echo $product['price']; ?></p>
                                <a href="product.php?id=<?php echo $product['id']; ?>" class="btn">View Product</a>
                            </div>
                        </div>
                    <?php }
                    $conn->close();
                    ?>
                </div>
            </div>
        </section>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
