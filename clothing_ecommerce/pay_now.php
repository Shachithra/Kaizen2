<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db_connect.php';

// Fetch the order details from the session or database
$user_id = $_SESSION['user'];
$total_price = $_SESSION['total_price'];

// Implement your payment processing logic here
// For example, you can integrate with a payment gateway like Stripe, PayPal, etc.

// If payment is successful, clear the order details and redirect to a success page
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Payment processing logic here (e.g., API call to payment gateway)
    $payment_successful = true; // Assume payment is successful for this example

    if ($payment_successful) {
        // Update the orders list in the database
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $cart_item) {
                $product_id = $cart_item['product_id'];
                $quantity = $cart_item['quantity'];
                $size = $cart_item['size'];
                $total_product_price = $cart_item['total_price'];

                $conn->query("INSERT INTO orders (user_id, product_id, quantity, size, total_price, order_date) VALUES ($user_id, $product_id, $quantity, '$size', $total_product_price, NOW())");
            }
        }

        // Clear the cart and total price
        unset($_SESSION['cart']);
        unset($_SESSION['total_price']);
        
        // Show success message
        $success_message = "Payment successful!";
    } else {
        // Handle payment failure
        header("Location: payment_failure.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Now</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <div class="payment-summary">
            <h2>Pay Now</h2>
            <table>
                <thead>
                    <tr>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>$<?php echo $total_price; ?></td>
                    </tr>
                </tbody>
            </table>
            <?php if (!isset($success_message)) { ?>
            <form method="POST" action="pay_now.php" class="payment-form">
                <h2>Payment Information</h2>
                <div class="card-icons">
                    <img src="path_to_your_image/Visa.png" alt="Visa">
                    <img src="path_to_your_image/MasterCard.png" alt="MasterCard">
                    <img src="path_to_your_image/AmericanExpress.png" alt="American Express">
                    <img src="path_to_your_image/Discover.png" alt="Discover">
                    <img src="path_to_your_image/Stripe.png" alt="Stripe">
                </div>
                <input type="text" name="card_number" placeholder="Card Number" required>
                <input type="text" name="expiry_date" placeholder="MM/YY" required>
                <input type="text" name="cvc" placeholder="CVC" required>
                <input type="text" name="billing_address" placeholder="Billing Address" required>
                <input type="text" name="city" placeholder="City" required>
                <input type="text" name="state" placeholder="State" required>
                <button type="submit" class="btn">Pay Now</button>
            </form>
            <?php } else { ?>
            <div class="success-message">
                <p><?php echo $success_message; ?></p>
                <button onclick="window.location.href='index.php';" class="btn">Go to Home Page</button>
            </div>
            <script>
                $(document).ready(function(){
                    alert("<?php echo $success_message; ?>");
                });
            </script>
            <?php } ?>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
<?php
$conn->close();
?>
