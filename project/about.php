<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>about us</h3>
   <p> <a href="home.php">home</a> / about </p>
</div>

<section class="about">

   <div class="flex">

      <div class="image">
         <img src="images/about-img.jpg" alt="">
      </div>

      <div class="content">
         <h3>why choose us?</h3>
         <p>At Bookly, we believe in making reading more accessible, affordable, and enjoyable for everyone. Our vast collection features books across all genres, including fiction, non-fiction, self-help, academic resources, and more, ensuring that there's something for every reader. We offer fast and hassle-free delivery, with free shipping on orders above ₹500, so you can receive your favorite books right at your doorstep without any delays.</p>
         <p>We provide the best prices and exclusive discounts, making reading more budget-friendly. Our secure and seamless checkout process supports multiple payment options, including Credit/Debit Cards, UPI, and Cash on Delivery, ensuring a smooth shopping experience. At Bookly, we are also committed to sustainability, using eco-friendly packaging and promoting e-books to help reduce paper waste.</p>
         <a href="contact.php" class="btn">contact us</a>
      </div>

   </div>

</section>

<section class="reviews">

   <h1 class="title">client's reviews</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/pic-1.png" alt="">
         <p>Bookly has an amazing collection of books. I found all the books I was looking for and the delivery was super fast. Highly recommend!</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Jane Smith</h3>
      </div>

      <div class="box">
         <img src="images/pic-2.png" alt="">
         <p>Great prices and excellent customer service. I had an issue with my order and it was resolved quickly. Will definitely shop here again.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
         </div>
         <h3>Michael Johnson</h3>
      </div>

      <div class="box">
         <img src="images/pic-3.png" alt="">
         <p>I love the variety of books available on Bookly. The website is easy to navigate and the checkout process is seamless. Highly recommend!</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Emily Davis</h3>
      </div>

      <div class="box">
         <img src="images/pic-4.png" alt="">
         <p>Bookly is my go-to online bookstore. They have a fantastic selection and the prices are unbeatable. Plus, the free shipping is a great bonus!</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>David Brown</h3>
      </div>

   </div>

</section>

<section class="authors">

   <h1 class="title">greate authors</h1>

   <div class="box-container">

      <div class="box">
         <img src="image/george.jpg" alt="">
         <h3>George McKay</h3>
      </div>

      <div class="box">
         <img src="image/Agatha Christie.jpg" alt="">
         <h3>Agatha Christie</h3>
      </div>

      <div class="box">
         <img src="image/Philip Ziegler.jpg" alt="">
         <h3>Philip Ziegler</h3>
      </div>

      <div class="box">
         <img src="image/Matthew Mather.jpg" alt="">
         <h3> Matthew Mather</h3>
      </div>

   </div>

</section>







<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>