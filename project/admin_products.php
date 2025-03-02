<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}

if (isset($_POST['add_product'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $price = (float) $_POST['price'];
    
    // Image handling
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_extension = pathinfo($image, PATHINFO_EXTENSION);
    $new_image_name = uniqid() . '.' . $image_extension;
    $image_folder = 'image/' . $new_image_name;

    // Check if product name already exists
    $select_product = $conn->prepare("SELECT name FROM products WHERE name = :name");
    $select_product->execute(['name' => $name]);

    if ($select_product->rowCount() > 0) {
        $message[] = 'Product name already exists';
    } else {
        // Insert product
        $add_product = $conn->prepare("INSERT INTO products (name, price, image) VALUES (:name, :price, :image)");
        $success = $add_product->execute([
            'name' => $name,
            'price' => $price,
            'image' => $new_image_name
        ]);

        if ($success) {
            if ($image_size > 2000000) {
                $message[] = 'Image size is too large';
            } else {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'Product added successfully!';
            }
        } else {
            $message[] = 'Product could not be added!';
        }
    }
}

// Delete product
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    // Get image name before deleting product
    $delete_image_query = $conn->prepare("SELECT image FROM products WHERE id = :id");
    $delete_image_query->execute(['id' => $delete_id]);
    $fetch_delete_image = $delete_image_query->fetch(PDO::FETCH_ASSOC);

    if ($fetch_delete_image) {
        $image_path = 'image/' . $fetch_delete_image['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    $delete_product_query = $conn->prepare("DELETE FROM products WHERE id = :id");
    $delete_product_query->execute(['id' => $delete_id]);

    header('location:admin_products.php');
    exit();
}

// Update product
if (isset($_POST['update_product'])) {
    $update_p_id = $_POST['update_p_id'];
    $update_name = htmlspecialchars(trim($_POST['update_name']));
    $update_price = (float) $_POST['update_price'];

    $update_query = $conn->prepare("UPDATE products SET name = :name, price = :price WHERE id = :id");
    $update_query->execute([
        'name' => $update_name,
        'price' => $update_price,
        'id' => $update_p_id
    ]);

    // Image update
    $update_image = $_FILES['update_image']['name'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_old_image = $_POST['update_old_image'];

    if (!empty($update_image)) {
        $update_image_extension = pathinfo($update_image, PATHINFO_EXTENSION);
        $new_update_image_name = uniqid() . '.' . $update_image_extension;
        $update_folder = 'image/' . $new_update_image_name;

        if ($update_image_size > 2000000) {
            $message[] = 'Image file size is too large';
        } else {
            move_uploaded_file($update_image_tmp_name, $update_folder);

            $update_image_query = $conn->prepare("UPDATE products SET image = :image WHERE id = :id");
            $update_image_query->execute([
                'image' => $new_update_image_name,
                'id' => $update_p_id
            ]);

            if (file_exists('image/' . $update_old_image)) {
                unlink('image/' . $update_old_image);
            }
        }
    }

    header('location:admin_products.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Products</title>
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="add-products">
    <h1 class="title">Add New Product</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="text" name="name" class="box" placeholder="Enter product name" required>
        <input type="number" name="price" class="box" placeholder="Enter product price" required>
        <input type="file" name="image" class="box" accept="image/*" required>
        <input type="submit" value="Add Product" name="add_product" class="btn">
    </form>
</section>

<section class="show-products">
    <h1 class="title">Your Products</h1>
    <div class="box-container">
        <?php
        $select_products = $conn->prepare("SELECT * FROM products");
        $select_products->execute();
        if ($select_products->rowCount() > 0) {
            while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="box">
            <img src="image/<?php echo $fetch_product['image']; ?>" alt="">
            <div class="name"><?php echo $fetch_product['name']; ?></div>
            <div class="price">₹<?php echo $fetch_product['price']; ?></div>
            <a href="admin_products.php?delete=<?php echo $fetch_product['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
            <a href="admin_update_product.php?update=<?php echo $fetch_product['id']; ?>" class="option-btn">Update</a>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">No products added yet!</p>';
        }
        ?>
    </div>
</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
