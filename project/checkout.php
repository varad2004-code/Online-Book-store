<?php

include 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('location:login.php');
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_btn'])) {
    try {
        // Sanitize input values
        $name = htmlspecialchars(trim($_POST['name']));
        $number = htmlspecialchars(trim($_POST['number']));
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $method = htmlspecialchars(trim($_POST['method']));
        $flat = htmlspecialchars(trim($_POST['flat']));
        $street = htmlspecialchars(trim($_POST['street']));
        $city = htmlspecialchars(trim($_POST['city']));
        $country = htmlspecialchars(trim($_POST['country']));
        $pin_code = htmlspecialchars(trim($_POST['pin_code']));
        $address = "Flat No. $flat, $street, $city, $country - $pin_code";
        $placed_on = date('Y-m-d');

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = 'Invalid email format!';
        } else {
            $cart_total = 0;
            $cart_products = [];

            // Fetch cart items
            $cart_query = $conn->prepare("SELECT * FROM cart WHERE user_id = :user_id");
            $cart_query->execute(['user_id' => $user_id]);
            $cart_items = $cart_query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($cart_items as $cart_item) {
                $cart_products[] = "{$cart_item['name']} ({$cart_item['quantity']})";
                $cart_total += ($cart_item['price'] * $cart_item['quantity']);
            }

            $total_products = implode(', ', $cart_products);

            if ($cart_total == 0) {
                $message = 'Your cart is empty!';
            } else {
                // Check if the exact same order exists
                $order_query = $conn->prepare("
                    SELECT 1 FROM orders 
                    WHERE user_id = :user_id 
                    AND name = :name 
                    AND number = :number 
                    AND email = :email 
                    AND method = :method 
                    AND address = :address 
                    AND total_products = :total_products 
                    AND total_price = :total_price
                ");
                $order_query->execute([
                    'user_id' => $user_id,
                    'name' => $name,
                    'number' => $number,
                    'email' => $email,
                    'method' => $method,
                    'address' => $address,
                    'total_products' => $total_products,
                    'total_price' => $cart_total
                ]);

                if ($order_query->rowCount() > 0) {
                    $message = 'Order already placed!';
                } else {
                    // Insert order
                    $insert_order = $conn->prepare("
                        INSERT INTO orders (user_id, name, number, email, method, address, total_products, total_price, placed_on) 
                        VALUES (:user_id, :name, :number, :email, :method, :address, :total_products, :total_price, :placed_on)
                    ");
                    $insert_order->execute([
                        'user_id' => $user_id,
                        'name' => $name,
                        'number' => $number,
                        'email' => $email,
                        'method' => $method,
                        'address' => $address,
                        'total_products' => $total_products,
                        'total_price' => $cart_total,
                        'placed_on' => $placed_on
                    ]);

                    // Clear cart after successful order
                    $delete_cart = $conn->prepare("DELETE FROM cart WHERE user_id = :user_id");
                    $delete_cart->execute(['user_id' => $user_id]);

                    $message = 'Order placed successfully!';
                }
            }
        }
    } catch (PDOException $e) {
        error_log("Checkout Error: " . $e->getMessage());
        $message = 'Something went wrong! Please try again later.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="checkout">
    <form action="" method="POST">
        <h3>Checkout</h3>

        <div class="flex">
            <div class="inputBox">
                <span>Full Name:</span>
                <input type="text" name="name" placeholder="Enter your name" required>
            </div>
            <div class="inputBox">
                <span>Phone Number:</span>
                <input type="text" name="number" placeholder="Enter your phone number" required>
            </div>
            <div class="inputBox">
                <span>Email:</span>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="inputBox">
                <span>Payment Method:</span>
                <select name="method" required>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                    <option value="PayPal">PayPal</option>
                </select>
            </div>
        </div>

        <h3>Shipping Address</h3>

        <div class="flex">
            <div class="inputBox">
                <span>Flat No:</span>
                <input type="text" name="flat" placeholder="Enter flat number" required>
            </div>
            <div class="inputBox">
                <span>Street:</span>
                <input type="text" name="street" placeholder="Enter street name" required>
            </div>
            <div class="inputBox">
                <span>City:</span>
                <input type="text" name="city" placeholder="Enter city" required>
            </div>
            <div class="inputBox">
                <span>Country:</span>
                <input type="text" name="country" placeholder="Enter country" required>
            </div>
            <div class="inputBox">
                <span>Pin Code:</span>
                <input type="text" name="pin_code" placeholder="Enter pin code" required>
            </div>
        </div>

        <button type="submit" name="order_btn">Place Order</button>
    </form>
</section>


<?php include 'footer.php'; ?>

</body>
</html>
