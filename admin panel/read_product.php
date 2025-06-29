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

    // Get the product ID from the URL and sanitize it
    $get_id = preg_replace("/[^a-zA-Z0-9]/", "", $_GET['post_id']);

    // Handle product deletion if the delete button is clicked
    if (isset($_POST['delete'])) {
        // Get and sanitize the product ID from the form
        $p_id = $_POST['product_id'];
        $p_id = preg_replace("/[^a-zA-Z0-9]/", "", $_POST['product_id']);

        // Fetch the product to get the image name (if any)
        $delete_image = $conn->prepare("SELECT * FROM `products` WHERE id = ? AND seller_id = ?");
        $delete_image->execute([$p_id, $seller_id]);
        $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);

        // Delete the image file if it exists
        if (!empty($fetch_delete_image['image'])) {
            unlink('../uploaded_files/' . $fetch_delete_image['image']);
        }
        // Delete the product from the database
        $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ? AND seller_id = ?");
        $delete_product->execute([$p_id, $seller_id]);
        // Redirect back to the product list after deletion
        header("location:view_product.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClickTrade - Show Products page</title>
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
        <section class="read-post">
            <div class="heading">
                <h1>product detail</h1>
                <img src="../image/separator-img.png" alt="">
            </div>
            <div class="box-container">
                <?php 
                    // Fetch the product details for this seller and product ID
                    $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ? AND seller_id = ?");
                    $select_product->execute([$get_id, $seller_id]);
                    if($select_product->rowCount() > 0) {
                        while ($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <form action="" method="post" class="box">
                    <input type="hidden" name="product_id" value="<?= $fetch_product['id']; ?>">
                    <!-- Show the product status, color-coded -->
                    <div class="status" style="color: <?php if($fetch_product['status'] == 'acive'){echo "limegreen";}else{echo "coral";} ?>"><?= $fetch_product['status']; ?></div>
                    <!-- Show the product image if it exists -->
                    <?php if($fetch_product['image'] != ''){ ?>
                        <img src="../uploaded_files/<?= $fetch_product['image']; ?>" class="image">
                    <?php } ?>
                    <!-- Show the product price -->
                    <div class="price">R<?= $fetch_product['price']; ?></div>
                    <!-- Show the product name -->
                    <div class="title"><?= $fetch_product['name']; ?></div>
                    <!-- Show the product description -->
                    <div class="copntent"><?= $fetch_product['product_detail']; ?></div>
                    <div class="flex-btn">
                        <!-- Button to edit this product -->
                        <a href="edit_product.php?id=<?= $fetch_product['status']; ?>" class="btn">edit</a>
                        <!-- Button to delete this product, with confirmation -->
                        <button type="submit" name="delete" class="btn" onclick="return confirm('delete this product');">delete</button>
                        <!-- Button to go back to the product list -->
                        <a href="view_product.php?post_id=<?= $fetch_product['status']; ?>" class="btn">go back</a>
                    </div>
                </form>
                <?php 
                        }
                    } else {
                        // If no product found, show a friendly message and a link to add products
                        echo '
                            <div class="empty">
                                <p>no products added yet! <br> <a href="add_products.php" class="btn" style="margin-top: 1.5rem;">add products</a> </p>
                            </div>
                        ';
                    }
                ?>
            </div>
        </section>
    </div>

    <!-- sweetalert cdn link-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- custom js link-->
    <script src="../js/admin_script.js"></script>

    <!-- Show any alert messages (success, warning, etc.) -->
    <?php include '../components/alert.php'; ?>

</body>
</html>