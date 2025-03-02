<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
   header('location:login.php');
   exit();
}

if (isset($_POST['send'])) {

   $name = $_POST['name'];
   $email = $_POST['email'];
   $number = $_POST['number'];
   $msg = $_POST['message'];

   // Check if message already exists
   $check_query = "SELECT * FROM message WHERE name = :name AND email = :email AND number = :number AND message = :message";
   $stmt = $conn->prepare($check_query);
   $stmt->execute(['name' => $name, 'email' => $email, 'number' => $number, 'message' => $msg]);

   if ($stmt->rowCount() > 0) {
      $message[] = 'Message sent already!';
   } else {
      // Insert new message
      $insert_query = "INSERT INTO message (user_id, name, email, number, message) VALUES (:user_id, :name, :email, :number, :message)";
      $stmt = $conn->prepare($insert_query);
      $result = $stmt->execute(['user_id' => $user_id, 'name' => $name, 'email' => $email, 'number' => $number, 'message' => $msg]);

      if ($result) {
         $message[] = 'Message sent successfully!';
      } else {
         $message[] = 'Failed to send message!';
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'header.php'; ?>

<div class="heading">
   <h3>Contact Us</h3>
   <p><a href="home.php">Home</a> / Contact</p>
</div>

<section class="contact">
   <form action="" method="post">
      <h3>Say something!</h3>
      <input type="text" name="name" required placeholder="Enter your name" class="box">
      <input type="email" name="email" required placeholder="Enter your email" class="box">
      <input type="number" name="number" required placeholder="Enter your number" class="box">
      <textarea name="message" class="box" placeholder="Enter your message" cols="30" rows="10"></textarea>
      <input type="submit" value="Send Message" name="send" class="btn">
   </form>
</section>

<?php include 'footer.php'; ?>

<!-- Custom JS -->
<script src="js/script.js"></script>

</body>
</html>
