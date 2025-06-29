<?php
    // Connecting to the database
    include '../components/connect.php';

    // Checking if the seller is logged in, otherwise redirecting them to the login page.
    if (isset($_COOKIE['seller_id'])) {
        $seller_id = $_COOKIE['seller_id'];
    } else {
        $seller_id = '';
        header('location:login.php');
    }

    // Handling the "add product" (publish) form submission.
    if (isset($_POST['publish'])) {

        // Generating a unique ID for the new product.
        $id = unique_id();

        // Sanitizing all the form inputs. (Had to use another way because of deprecation).
        $name = htmlspecialchars($_POST['name']);
        $price = htmlspecialchars($_POST['price']);
        $description = htmlspecialchars($_POST['description']);
        $stock = htmlspecialchars($_POST['stock']);
        $status = 'active'; // Setting product status to active since its in the publishing.

        // Handling the image upload.
        $image_name = $_FILES['image']['name'];
        $image_name = htmlspecialchars($image_name); 
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../uploaded_files/' . $image_name;

        // Checking if the image already exists for this seller.
        $select_image = $conn->prepare("SELECT * FROM `products` WHERE image = ? AND seller_id = ?");
        $select_image->execute([$image_name, $seller_id]);

        if (!empty($image_name)) {
            // Double-check for duplicate images in the database.
            $select_image = $conn->prepare("SELECT * FROM `products` WHERE image = ? AND seller_id = ?");
            $select_image->execute([$image_name, $seller_id]);
        
            // If the image already exists, a warning will then show.
            if ($select_image->rowCount() > 0) {
                $warning_msg[] = 'Product image already exists';
            } 
            // Checking if the image is too large (over 2MB).
            elseif ($image_size > 2000000) {
                $warning_msg[] = 'Image size is too large';
            } else {
                // If everything is fine, the uploaded image will be moved to the folder.
                move_uploaded_file($image_tmp_name, $image_folder);
            }
        } else {
            // If no image is uploaded, the image name will then be set to an empty string.
            $image_name = ''; 
        }
        
        // If the image is unique and not empty, then the product will be inserted into the database.
        if ($select_image->rowCount() == 0 && $image_name != '') {
            $insert_product = $conn->prepare("INSERT INTO `products`(id, seller_id, name, price, image, stock, product_detail, status) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_product->execute([$id, $seller_id, $name, $price, $image_name, $stock, $description, $status]);
            $success_msg[] = 'Product added successfully';
        }
    }

    // Handling the "save as draft" form submission.
    if (isset($_POST['draft'])) {

        // Generating a unique ID for the draft product.
        $id = unique_id();

        // Sanitizing all the form inputs.
        $name = htmlspecialchars($_POST['name']);
        $price = htmlspecialchars($_POST['price']);
        $description = htmlspecialchars($_POST['description']);
        $stock = htmlspecialchars($_POST['stock']);
        $status = 'deactive'; // Since this is a draft, the status will then be set to deactive.

        // Handling the image upload for the draft.
        $image_name = $_FILES['image']['name'];
        $image_name = htmlspecialchars($image_name);
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../uploaded_files/' . $image_name;

        // Checking if the image already exists for this seller.
        $select_image = $conn->prepare("SELECT * FROM `products` WHERE image = ? AND seller_id = ?");
        $select_image->execute([$image_name, $seller_id]);

        if (!empty($image_name)) {
            // Double-check for duplicate image in the database
            $select_image = $conn->prepare("SELECT * FROM `products` WHERE image = ? AND seller_id = ?");
            $select_image->execute([$image_name, $seller_id]);
        
            // If the image already exists, a warning will then show.
            if ($select_image->rowCount() > 0) {
                $warning_msg[] = 'Product image already exists';
            } 
            // Check if the image is too large (over 2MB)
            elseif ($image_size > 2000000) {
                $warning_msg[] = 'Image size is too large';
            } else {
                // If everything is fine, the uploaded image will be moved to the folder.
                move_uploaded_file($image_tmp_name, $image_folder);
            }
        } else {
            // If no image is uploaded, the image name will then be set to an empty string.
            $image_name = '';
        }
        
        // If the image is unique and not empty, then the product will be inserted into the database.
        if ($select_image->rowCount() == 0 && $image_name != '') {
            $insert_product = $conn->prepare("INSERT INTO `products`(id, seller_id, name, price, image, stock, product_detail, status) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_product->execute([$id, $seller_id, $name, $price, $image_name, $stock, $description, $status]);
            $success_msg[] = 'Product added as draft successfully';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClickTrade - Admin Add Products page</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">

    <!-- Boxicons CDN -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Font Awesome cdn link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>

    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
        <section class="post-editor">
            <div class="heading">
                <h1>add product</h1>
                <img src="../image/separator-img.png" alt="">
            </div>
            <div class="form-container">
                <!-- Product form for adding or saving as draft -->
                <form action="" method="post" enctype="multipart/form-data" class="register">
                    <div class="input-field">
                        <p>product name <span>*</span> </p>
                        <input type="text" name="name" maxlength="100" placeholder="add product name" required class="box">
                    </div>
                    <div class="input-field">
                        <p>product price <span>*</span> </p>
                        <input type="number" name="price" maxlength="100" placeholder="add product price" required class="box">
                    </div>
                    <div class="input-field">
                        <p>product detail <span>*</span> </p>
                        <textarea name="description" required maxlength="1000" placeholder="add product detail" class="box"></textarea>
                    </div>
                    <div class="input-field">
                        <p>product stock <span>*</span> </p>
                        <input type="number" name="stock" maxlength="10" min="0" max="9999999999" placeholder="add product stock" required class="box">
                    </div>
                    <div class="input-field">
                        <p>product image <span>*</span> </p>
                        <input type="file" name="image" accept="image/*" required class="box">
                    </div>
                    <div class="flex-btn">
                        <!-- Button to publish the product -->
                        <input type="submit" name="publish" value="add product" class="btn">
                        <!-- Button to save the product as a draft -->
                        <input type="submit" name="draft" value="save as draft" class="btn">
                    </div>
                </form>
            </div>
        </section>
    </div>

    <!-- sweetalert cdn link-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- custom js link-->
    <script src="../js/admin_script.js"></script>

    <?php include '../components/alert.php'; ?>

</body>
</html>