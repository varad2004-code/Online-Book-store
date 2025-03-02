<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'] ?? null;

if (!$admin_id) {
   header('location:login.php');
   exit();
}

// Delete user securely using prepared statements
if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];

   if (!is_numeric($delete_id)) {
      die("Invalid user ID");
   }

   $query = "DELETE FROM users WHERE id = :id";
   $stmt = $conn->prepare($query);
   
   if ($stmt) {
      $result = $stmt->execute(['id' => $delete_id]);
      if ($result) {
         header('location:admin_users.php');
         exit();
      } else {
         die("Error deleting user");
      }
   } else {
      die("Query preparation failed");
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Users</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom Admin CSS -->
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="users">
   <h1 class="title">User Accounts</h1>
   <div class="box-container">
      <?php
         $query = "SELECT * FROM users";
         $stmt = $conn->query($query);

         if ($stmt) {
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($users) {
               foreach ($users as $fetch_users) {
      ?>
      <div class="box">
         <p> User ID: <span><?php echo htmlspecialchars($fetch_users['id']); ?></span> </p>
         <p> Username: <span><?php echo htmlspecialchars($fetch_users['name']); ?></span> </p>
         <p> Email: <span><?php echo htmlspecialchars($fetch_users['email']); ?></span> </p>
         <p> User Type: <span style="color:<?php echo ($fetch_users['user_type'] == 'admin') ? 'var(--orange)' : 'inherit'; ?>"><?php echo htmlspecialchars($fetch_users['user_type']); ?></span> </p>
         <a href="admin_users.php?delete=<?php echo htmlspecialchars($fetch_users['id']); ?>" onclick="return confirm('Delete this user?');" class="delete-btn">Delete User</a>
      </div>
      <?php
               }
            } else {
               echo "<p class='empty'>No users found!</p>";
            }
         } else {
            echo "<p class='error'>Failed to retrieve users.</p>";
         }
      ?>
   </div>
</section>

<!-- Custom Admin JS -->
<script src="js/admin_script.js"></script>
</body>
</html>