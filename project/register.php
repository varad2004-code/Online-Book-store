<?php

include 'config.php';
session_start();

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $email = $_POST['email'];
   $pass = md5((string)$_POST['password']); // Ensuring password is treated as string
   $user_type = (string)$_POST['user_type']; // Explicitly cast user_type to string

   try {
      $query = "INSERT INTO users (name, email, password, user_type) VALUES (:name, :email, :password, :user_type)";
      $stmt = $conn->prepare($query);
      $stmt->bindParam(':name', $name, PDO::PARAM_STR);
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
      $stmt->bindParam(':password', $pass, PDO::PARAM_STR);
      $stmt->bindParam(':user_type', $user_type, PDO::PARAM_STR);
      $stmt->execute();
      
      $_SESSION['success_message'] = 'Registration successful! You can now login.';
      header('location:login.php');
   } catch (PDOException $e) {
      echo "Query failed: " . $e->getMessage();
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

   <!-- custom css file link -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
   <div class="form-container">

   <form action="" method="post">
      <h3>Register Now</h3>
      <input type="text" name="name" placeholder="Enter your name" required class="box">
      <input type="email" name="email" placeholder="Enter your email" required class="box">
      <input type="password" name="password" placeholder="Enter your password" required class="box">
      <select name="user_type" required class="box">
         <option value="user">User</option>
         <option value="admin">Admin</option>
      </select>
      <input type="submit" name="submit" value="Register Now" class="btn">
      <p>Already have an account? <a href="login.php">Login now</a></p>
   </form>

   </div>

</body>
</html>
