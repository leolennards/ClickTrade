<?php
    // Connect to the database
    include '../components/connect.php';

    // Check if the seller is logged in, otherwise redirect to login page
    if (isset($_COOKIE['seller_id'])) {
        $seller_id = $_COOKIE['seller_id'];
    } else {
        $seller_id = '';
        header('location:login.php');
    }

    // If the update product form is submitted, update the product details
    if (isset($_POST['update'])) {
        // Get and sanitize all the product details from the form
        $product_id = htmlspecialchars($_POST['product_id']);
        $name = htmlspecialchars($_POST['name']);
        $price = htmlspecialchars($_POST['price']);
        $description = htmlspecialchars($_POST['description']);
        $stock = htmlspecialchars($_POST['stock']);
        $status = htmlspecialchars($_POST['status']);

        // Update product basic details in the database
        $update_product = $conn->prepare("UPDATE `products` SET name = ?, price = ?, product_detail = ?, stock = ?, status = ? WHERE id = ?");
        $update_product->execute([$name, $price, $description, $stock, $status, $product_id]);
        $success_msg[] = 'Product updated successfully';

        // Handle image upload if a new image is provided
        $old_image = $_POST['old_image'];
        $image_name = htmlspecialchars($_FILES['image']['name']); // Sanitize file name
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../uploaded_files/' . $image_name;

        // Check if the image already exists for the seller
        $select_image = $conn->prepare("SELECT * FROM `products` WHERE image = ? AND seller_id = ?");
        $select_image->execute([$image_name, $seller_id]);

        if (!empty($image_name)) {
            if ($image_size > 2000000) {
                // Warn if the image is too large
                $warning_msg[] = 'Image size is too large';
            } elseif ($select_image->rowCount() > 0) {
                // Warn if the image already exists for this seller
                $warning_msg[] = 'Product image already exists';
            } else {
                // Update the product image in the database and move the file
                $update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
                $update_image->execute([$image_name, $product_id]);
                move_uploaded_file($image_tmp_name, $image_folder);

                // Delete the old image file if it's different from the new one
                if ($old_image != $image_name && $old_image != '') {
                    unlink('../uploaded_files/' . $old_image);
                }
                $success_msg[] = 'Image updated successfully';
            }
        }
    }

    // If the delete image button is clicked, remove the image from the product
    if (isset($_POST['delete_image'])) {
        $empty_image = '';
        $product_id = htmlspecialchars($_POST['product_id']);

        // Fetch the product to get the image name
        $delete_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
        $delete_image->execute([$product_id]);
        $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);

        // Delete the image file if it exists
        if ($fetch_delete_image['image'] != '') {
            unlink('../uploaded_files/' . $fetch_delete_image['image']);
        }
        // Remove the image reference from the database
        $unset_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
        $unset_image->execute([$empty_image, $product_id]);
        $success_msg[] = 'Image deleted successfully';
    }

    // If the delete product button is clicked, delete the product and its image
    if (isset($_POST['delete_product'])) {
        $product_id = htmlspecialchars($_POST['product_id']);

        // Fetch the product to get the image name
        $delete_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
        $delete_image->execute([$product_id]);
        $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);

        // Delete the image file if it exists
        if ($fetch_delete_image['image'] != '') {
            unlink('../uploaded_files/' . $fetch_delete_image['image']);
        }
        // Delete the product from the database
        $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
        $delete_product->execute([$product_id]);
        $success_msg[] = 'Product deleted successfully';
        header('location:view_product.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClickTrade - seller registration page</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">

    <!-- Boxicons CDN -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Font Awesome cdn link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>

    <div class="main-container">
        <!-- Include the admin header/navigation -->
        <?php include '../components/admin_header.php'; ?>
        <section class="post-editor">
            <div class="heading">
                <h1>edit product</h1>
                <img src="../image/separator-img.png" alt="">
            </div>
            <div class="box-container">
                <?php 
                    // Get the product to edit, making sure it belongs to this seller
                    $product_id = $_GET['id'];
                    $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ? AND seller_id = ?");
                    $select_product->execute([$product_id, $seller_id]);
                    if ($select_product->rowCount() > 0) {
                        while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){
                ?>
                <div class="form-container">
                    <!-- Product edit form -->
                    <form action="" method="post" enctype="multipart/form-data" class="register">
                        <input type="hidden" name="old_image" value="<?= $fetch_product['image']; ?>">
                        <input type="hidden" name="product_id" value="<?= $fetch_product['id']; ?>">
                        <div class="input-field">
                            <p>product status <span>*</span></p>
                            <select name="status" class="box">
                                <option value="<?= $fetch_product['status']; ?>" selected><?= $fetch_product['status']; ?></option>
                                <option value="active">active</option>
                                <option value="deactive">deactive</option>
                            </select>
                        </div>
                        <div class="input-field">
                            <p>product name <span>*</span></p>
                            <input type="text" name="name" value="<?= $fetch_product['name']; ?>" class="box">
                        </div>
                        <div class="input-field">
                            <p>product price <span>*</span></p>
                            <input type="number" name="price" value="<?= $fetch_product['price']; ?>" class="box">
                        </div>
                        <div class="input-field">
                            <p>product description <span>*</span></p>
                            <textarea name="description" class="box"><?= $fetch_product['product_detail']; ?></textarea>
                        </div>
                        <div class="input-field">
                            <p>product stock <span>*</span></p>
                            <input type="number" name="stock" value="<?= $fetch_product['stock']; ?>" class="box" min="0" max="9999999999" maxlength="10">
                        </div>
                        <div class="input-field">
                            <p>product image <span>*</span></p>
                            <input type="file" name="image" accept="image/*" class="box">
                            <?php if($fetch_product['image'] != ''){?>
                                <img src="../uploaded_files/<?= $fetch_product['image']; ?>" class="image">
                                <div class="flex-btn">
                                    <input type="submit" name="delete_image" class="btn" value="delete image">
                                    <a href="view_product.php" class="btn" style="width: 49%; text-align: center; height: 3rem; margin-top: .7rem;">go back</a>
                                </div>
                            <?php } ?>    
                        </div>
                        <div class="flex-btn">
                            <input type="submit" name="update" value="update product" class="btn">
                            <input type="submit" name="delete_product" value="delete product" class="btn">
                        </div>
                    </form>
                </div>
                <?php 
                        }
                    } else {
                        // If no product found, show a friendly message and some navigation
                        echo '
                            <div class="empty">
                                <p>no products added yet!</p>
                            </div>
                        ';
                ?>
                <br><br>
                <div class="flex-btn">
                    <a href="view_product.php" class="btn">view product</a>
                    <a href="add_products.php" class="btn">add product</a>
                </div>
                <?php } ?>
            </div>
        </section>
    </div>

    <!-- SweetAlert for nice popup messages -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- Custom admin JS for UI interactions -->
    <script src="../js/admin_script.js"></script>

    <!-- Show any alert messages (success, warning, etc.) -->
    <?php include '../components/alert.php'; ?>

</body>
</html>