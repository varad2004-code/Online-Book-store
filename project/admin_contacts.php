<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
   exit();
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $stmt = $conn->prepare("DELETE FROM message WHERE id = :id");
   $stmt->execute(['id' => $delete_id]);
   header('location:admin_contacts.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>messages</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="messages">

   <h1 class="title"> messages </h1>

   <div class="box-container">
   <?php
      $stmt = $conn->prepare("SELECT * FROM message");
      $stmt->execute();
      $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if(count($messages) > 0){
         foreach($messages as $message){
   ?>
   <div class="box">
      <p> user id : <span><?php echo $message['user_id']; ?></span> </p>
      <p> name : <span><?php echo $message['name']; ?></span> </p>
      <p> number : <span><?php echo $message['number']; ?></span> </p>
      <p> email : <span><?php echo $message['email']; ?></span> </p>
      <p> message : <span><?php echo $message['message']; ?></span> </p>
      <a href="admin_contacts.php?delete=<?php echo $message['id']; ?>" onclick="return confirm('delete this message?');" class="delete-btn">delete message</a>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty">you have no messages!</p>';
      }
   ?>
   </div>

</section>

<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>