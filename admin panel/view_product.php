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

    // (Debug) Show the product ID if posted (can be removed in production)
    echo "Product ID: " . $_POST['product_id'];

    // Handle product deletion if the delete button is clicked
    if (isset($_POST['delete'])) {
        // Sanitize the product ID from the form
        $p_id = preg_replace("/[^a-zA-Z0-9]/", "", $_POST['product_id']);
    
        // Delete the product from the database
        $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
        $delete_product->execute([$p_id]);
    
        $success_msg[] = 'Product deleted successfully';
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
        <section class="show-post">
            <div class="heading">
                <h1>your products</h1>
                <img src="../image/separator-img.png" alt="">
            </div>
            <div class="box-container">
                <?php 
                    // Fetch all products for this seller
                    $select_products = $conn->prepare("SELECT * FROM `products` WHERE seller_id = ?");
                    $select_products->execute([$seller_id]);
                    if ($select_products->rowCount() > 0) {
                        while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <form action="" method="post" class="box">
                    <!-- Hidden input to store the product ID -->
                    <input type="hidden" name="product_id" value="<?= $fetch_products['id']; ?>">
                    <!-- Show the product image if it exists -->
                    <?php if($fetch_products['image'] != ''){ ?>
                        <img src="../uploaded_files/<?= $fetch_products['image']; ?>" class="image" alt="Product Image">
                    <?php } ?>
                    <!-- Show the product status, color-coded -->
                    <div class="status" style="color: <?php if($fetch_products['status'] =='active'){echo "limegreen";}else{echo "coral";} ?>"><?= $fetch_products['status']; ?></div>
                    <!-- Show the product price -->
                    <div class="price">R<?= $fetch_products['price']; ?></div>
                    <div class="content">
                        <!-- Show the product name -->
                        <div class="title"><?= $fetch_products['name']; ?></div>
                        <div class="flex-btn">
                            <!-- Button to edit this product -->
                            <a href="edit_product.php?id=<?= $fetch_products['id']; ?>" class="btn">edit</a>
                            <!-- Button to delete this product, with confirmation -->
                            <button type="submit" name="delete" class="btn" onclick="return confirm('delete this product');">delete</button>
                            <!-- Button to view full details of this product -->
                            <a href="read_product.php?post_id=<?= $fetch_products['id']; ?>" class="btn">read</a>
                        </div>
                    </div>
                </form>
                <?php 
                        }
                    } else {
                        // If there are no products, show a friendly message and a link to add products
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