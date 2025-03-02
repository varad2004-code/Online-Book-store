<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}

if (isset($_GET['update'])) {
    $update_id = $_GET['update'];
    $select_product = $conn->prepare("SELECT * FROM products WHERE id = :id");
    $select_product->execute(['id' => $update_id]);
    $fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);
    if (!$fetch_product) {
        header('location:admin_products.php');
        exit();
    }
} else {
    header('location:admin_products.php');
    exit();
}

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
    <title>Update Product</title>
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="update-product">
    <h1 class="title">Update Product</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="update_p_id" value="<?php echo $fetch_product['id']; ?>">
        <input type="hidden" name="update_old_image" value="<?php echo $fetch_product['image']; ?>">
        <input type="text" name="update_name" class="box" value="<?php echo $fetch_product['name']; ?>" required>
        <input type="number" name="update_price" class="box" value="<?php echo $fetch_product['price']; ?>" required>
        <input type="file" name="update_image" class="box" accept="image/*">
        <input type="submit" value="Update Product" name="update_product" class="btn">
        <a href="admin_products.php" class="option-btn">Go Back</a>
    </form>
</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
