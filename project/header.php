<?php
if(isset($message)){
   foreach($message as $msg){
      echo '<div class="message">
               <span>'.htmlspecialchars($msg).'</span>
               <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>';
   }
}
?>

<header class="header">
   <div class="header-1">
      <div class="flex">
         <div class="share">
            <a href="https://www.facebook.com/people/Varad-Khadabadi/pfbid02v63VMwXKdFqrjbQNJgHZRJxWW9qB8HxGSgvbNEVoekthRyVyMRcrncPgHLW5fxX1l/?sk=about" class="fab fa-facebook-f"></a>
            <a href="https://www.linkedin.com/in/varadkhadbadi23/" class="fab fa-linkedin"></a>
            <a href="https://x.com/VKhadbadi"> <i class="fab fa-twitter"></i></a>
         </div>
         <p> New <a href="login.php">Login</a> | <a href="register.php">Register</a> </p>
      </div>
   </div>
   <div class="header-2">
      <div class="flex">
         <a href="home.php" class="logo">Booktopia</a>
         <nav class="navbar">
            <a href="home.php">Home</a>
            <a href="about.php">About</a>
            <a href="shop.php">Shop</a>
            <a href="contact.php">Contact</a>
            <a href="orders.php">Orders</a>
         </nav>
         <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php" class="fas fa-search"></a>
            <div id="user-btn" class="fas fa-user"></div>
            <?php
               try {
                  $query = "SELECT COUNT(*) FROM cart WHERE user_id = :user_id";
                  $stmt = $conn->prepare($query);
                  $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                  $stmt->execute();
                  $cart_rows_number = $stmt->fetchColumn();
               } catch (PDOException $e) {
                  $cart_rows_number = 0;
               }
            ?>
            <a href="cart.php"> <i class="fas fa-shopping-cart"></i> <span>(<?php echo $cart_rows_number; ?>)</span> </a>
         </div>
         <div class="user-box">
            <p>Username: <span><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Guest'); ?></span></p>
            <p>Email: <span><?php echo htmlspecialchars($_SESSION['user_email'] ?? 'Not Available'); ?></span></p>
            <a href="logout.php" class="delete-btn">Logout</a>
         </div>
      </div>
   </div>
</header>
