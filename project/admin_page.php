<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}

// PostgreSQL connection
$conn = pg_connect("host=localhost dbname=shop_db user=postgres password=varad2004");

if (!$conn) {
    die("Database connection failed");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Panel</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<!-- Admin Dashboard Section -->
<section class="dashboard">

   <h1 class="title">Dashboard</h1>

   <div class="box-container">

      <div class="box">
         <?php
            $total_pendings = 0;
            $result = pg_query($conn, "SELECT total_price FROM orders WHERE payment_status = 'pending'");
            while ($row = pg_fetch_assoc($result)) {
                $total_pendings += (float) $row['total_price'];
            }
         ?>
         <h3>$<?php echo $total_pendings; ?>/-</h3>
         <p>Total Pendings</p>
      </div>

      <div class="box">
         <?php
            $total_completed = 0;
            $result = pg_query($conn, "SELECT total_price FROM orders WHERE payment_status = 'completed'");
            while ($row = pg_fetch_assoc($result)) {
                $total_completed += (float) $row['total_price'];
            }
         ?>
         <h3>$<?php echo $total_completed; ?>/-</h3>
         <p>Completed Payments</p>
      </div>

      <div class="box">
         <?php
            $result = pg_query($conn, "SELECT COUNT(*) AS count FROM orders");
            $row = pg_fetch_assoc($result);
         ?>
         <h3><?php echo $row['count']; ?></h3>
         <p>Orders Placed</p>
      </div>

      <div class="box">
         <?php
            $result = pg_query($conn, "SELECT COUNT(*) AS count FROM products");
            $row = pg_fetch_assoc($result);
         ?>
         <h3><?php echo $row['count']; ?></h3>
         <p>Products Added</p>
      </div>

      <div class="box">
         <?php
            $result = pg_query($conn, "SELECT COUNT(*) AS count FROM users WHERE user_type = 'user'");
            $row = pg_fetch_assoc($result);
         ?>
         <h3><?php echo $row['count']; ?></h3>
         <p>Normal Users</p>
      </div>

      <div class="box">
         <?php
            $result = pg_query($conn, "SELECT COUNT(*) AS count FROM users WHERE user_type = 'admin'");
            $row = pg_fetch_assoc($result);
         ?>
         <h3><?php echo $row['count']; ?></h3>
         <p>Admin Users</p>
      </div>

      <div class="box">
         <?php
            $result = pg_query($conn, "SELECT COUNT(*) AS count FROM users");
            $row = pg_fetch_assoc($result);
         ?>
         <h3><?php echo $row['count']; ?></h3>
         <p>Total Accounts</p>
      </div>

      <div class="box">
         <?php
            $result = pg_query($conn, "SELECT COUNT(*) AS count FROM message");
            $row = pg_fetch_assoc($result);
         ?>
         <h3><?php echo $row['count']; ?></h3>
         <p>New Messages</p>
      </div>

   </div>

</section>

<!-- JavaScript -->
<script src="js/admin_script.js"></script>

</body>
</html>

<?php
pg_close($conn);
?>
