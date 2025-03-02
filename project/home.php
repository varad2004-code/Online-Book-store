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

   try {
      $query = "SELECT * FROM cart WHERE name = :product_name AND user_id = :user_id";
      $stmt = $conn->prepare($query);
      $stmt->bindParam(':product_name', $product_name, PDO::PARAM_STR);
      $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
         $message[] = 'Already added to cart!';
      } else {
         $query = "INSERT INTO cart (user_id, name, price, quantity, image) VALUES (:user_id, :name, :price, :quantity, :image)";
         $stmt = $conn->prepare($query);
         $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
         $stmt->bindParam(':name', $product_name, PDO::PARAM_STR);
         $stmt->bindParam(':price', $product_price, PDO::PARAM_STR);
         $stmt->bindParam(':quantity', $product_quantity, PDO::PARAM_INT);
         $stmt->bindParam(':image', $product_image, PDO::PARAM_STR);
         $stmt->execute();

         $message[] = 'Product added to cart!';
      }
   } catch (PDOException $e) {
      echo "Query failed: " . $e->getMessage();
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="home">
   <div class="content">
      <h3>Hand Picked Books Delivered to Your Door</h3>
      <p>Discover a selection of high-quality books curated just for you.</p>
      <a href="about.php" class="white-btn">Discover More</a>
   </div>
</section>

<section class="products">
   <h1 class="title">Latest Products</h1>
   <div class="box-container">
      <?php  
         try {
            $query = "SELECT * FROM products LIMIT 6";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($products) {
               foreach ($products as $product) {
      ?>
      <form action="" method="post" class="box">
         <img src="image/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
         <div class="name"><?php echo htmlspecialchars($product['name']); ?></div>
         <div class="price">Rs <?php echo htmlspecialchars($product['price']); ?>/-</div>
         <input type="number" min="1" name="product_quantity" value="1" class="qty">
         <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
         <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($product['price']); ?>">
         <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($product['image']); ?>">
         <input type="submit" value="Add to Cart" name="add_to_cart" class="btn">
      </form>
      <?php
               }
            } else {
               echo '<p class="empty">No products available yet!</p>';
            }
         } catch (PDOException $e) {
            echo "Query failed: " . $e->getMessage();
         }
      ?>
   </div>
   <div class="load-more" style="margin-top: 2rem; text-align:center">
      <a href="shop.php" class="option-btn">Load More</a>
   </div>
</section>

<section class="about">
   <div class="flex">
      <div class="image">
         <img src="images/about-img.jpg" alt="About Us">
      </div>
      <div class="content">
         <h3>About Us</h3>
         <p>Learn more about our mission to provide high-quality books to our customers.</p>
         <a href="about.php" class="btn">Read More</a>
      </div>
   </div>
</section>

<section class="home-contact">
   <div class="content">
      <h3>Have any questions?</h3>
      <p>Contact us for more information about our books and services.</p>
      <a href="contact.php" class="white-btn">Contact Us</a>
   </div>
</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>
</body>
</html>
