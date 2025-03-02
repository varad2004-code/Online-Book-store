<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
   header('location:login.php');
   exit();
}

if (isset($_POST['add_to_cart'])) {
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   // Use prepared statements to prevent SQL injection
   $check_cart_query = "SELECT * FROM cart WHERE name = :name AND user_id = :user_id";
   $stmt = $conn->prepare($check_cart_query);
   $stmt->execute(['name' => $product_name, 'user_id' => $user_id]);

   if ($stmt->rowCount() > 0) {
      $message[] = 'Already added to cart!';
   } else {
      $insert_cart_query = "INSERT INTO cart (user_id, name, price, quantity, image) VALUES (:user_id, :name, :price, :quantity, :image)";
      $stmt = $conn->prepare($insert_cart_query);
      $stmt->execute(['user_id' => $user_id, 'name' => $product_name, 'price' => $product_price, 'quantity' => $product_quantity, 'image' => $product_image]);
      $message[] = 'Product added to cart!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shop</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'header.php'; ?>

<div class="heading">
   <h3>Our Shop</h3>
   <p> <a href="home.php">Home</a> / Shop </p>
</div>

<section class="products">
   <h1 class="title">Latest Products</h1>
   <div class="box-container">
      <?php  
         $select_products_query = "SELECT * FROM products";
         $stmt = $conn->query($select_products_query);
         if ($stmt->rowCount() > 0) {
            while ($fetch_products = $stmt->fetch(PDO::FETCH_ASSOC)) {
      ?>
      <form action="" method="post" class="box">
         <div class="price">Rs <?php echo htmlspecialchars($fetch_products['price']); ?>/-</div>
      <img class="image" src="image/<?php echo htmlspecialchars($fetch_products['image']); ?>" alt="<?php echo htmlspecialchars($fetch_products['name']); ?>">
         <div class="name"><?php echo htmlspecialchars($fetch_products['name']); ?></div>
         <input type="number" min="1" name="product_quantity" value="1" class="qty">
         <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($fetch_products['name']); ?>">
         <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($fetch_products['price']); ?>">
         <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($fetch_products['image']); ?>">
         <input type="submit" value="Add to Cart" name="add_to_cart" class="btn">
      </form>
      <?php
         }
      } else {
         echo '<p class="empty">No products added yet!</p>';
      }
      ?>
   </div>
</section>

<?php include 'footer.php'; ?>

<!-- Custom JS File Link -->
<script src="js/script.js"></script>

</body>
</html>