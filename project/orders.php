<?php

include 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('location: login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'header.php'; ?>

<div class="heading">
   <h3>Your Orders</h3>
   <p> <a href="home.php">Home</a> / Orders </p>
</div>

<section class="placed-orders">

   <h1 class="title">Placed Orders</h1>

   <div class="box-container">

      <?php
      try {
         $order_query = $conn->prepare("SELECT * FROM orders WHERE user_id = :user_id");
         $order_query->execute(['user_id' => $user_id]);
         $orders = $order_query->fetchAll(PDO::FETCH_ASSOC);

         if ($orders) {
            foreach ($orders as $order) {
      ?>
      <div class="box">
         <p> Placed On: <span><?php echo htmlspecialchars($order['placed_on']); ?></span> </p>
         <p> Name: <span><?php echo htmlspecialchars($order['name']); ?></span> </p>
         <p> Number: <span><?php echo htmlspecialchars($order['number']); ?></span> </p>
         <p> Email: <span><?php echo htmlspecialchars($order['email']); ?></span> </p>
         <p> Address: <span><?php echo htmlspecialchars($order['address']); ?></span> </p>
         <p> Payment Method: <span><?php echo htmlspecialchars($order['method']); ?></span> </p>
         <p> Your Orders: <span><?php echo htmlspecialchars($order['total_products']); ?></span> </p>
         <p> Total Price: <span>$<?php echo htmlspecialchars($order['total_price']); ?>/-</span> </p>
         <p> Payment Status: 
            <span style="color:<?php echo ($order['payment_status'] == 'pending') ? 'red' : 'green'; ?>;">
               <?php echo htmlspecialchars($order['payment_status']); ?>
            </span>
         </p>
      </div>
      <?php
            }
         } else {
            echo '<p class="empty">No orders placed yet!</p>';
         }
      } catch (PDOException $e) {
         echo '<p class="error">Error fetching orders!</p>';
         error_log("Error fetching orders: " . $e->getMessage());
      }
      ?>
   </div>

</section>

<?php include 'footer.php'; ?>

<!-- Custom JS -->
<script src="js/script.js"></script>

</body>
</html>
