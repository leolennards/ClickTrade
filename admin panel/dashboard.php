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

    // (Optional) You might want to fetch the seller's profile here if you want to display their name
    // Example: $fetch_profile = ...;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClickTrade - seller page</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">

    <!-- Boxicons CDN for icons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Font Awesome CDN for more icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>

    <div class="main-container">
        <!-- Include the admin header/navigation bar -->
        <?php include '../components/admin_header.php'; ?>
        <section class="dashboard">
            <div class="heading">
                <h1>dashboard</h1>
                <img src="../image/separator-img.png" alt="">
            </div>
            <div class="box-container">
                <!-- Welcome box with seller's name -->
                <div class="box">
                    <h3>Welcome !</h3>
                    <p><?= $fetch_profile["name"]; ?></p>
                    <a href="update.php" class="btn">update profile</a>
                </div>
                <!-- Products added by this seller -->
                <div class="box">
                    <?php 
                        $select_products = $conn->prepare("SELECT * FROM `products` WHERE seller_id = ?");
                        $select_products->execute([$seller_id]);
                        $number_of_products = $select_products->rowCount();
                    ?>
                    <h3><?= $number_of_products; ?></h3>
                    <p>products added</p>
                    <a href="add_products.php" class="btn">add product</a>
                </div>
                <!-- Active products for this seller -->
                <div class="box">
                    <?php 
                        $select_active_products = $conn->prepare("SELECT * FROM `products` WHERE seller_id = ? AND status = ?");
                        $select_active_products->execute([$seller_id, "active"]);
                        $number_of_active_products = $select_active_products->rowCount();
                    ?>
                    <h3><?= $number_of_active_products; ?></h3>
                    <p>total active products</p>
                    <a href="view_product.php" class="btn">active product</a>
                </div>
                <!-- Deactive products for this seller -->
                <div class="box">
                    <?php 
                        $select_deactive_products = $conn->prepare("SELECT * FROM `products` WHERE seller_id = ? AND status = ?");
                        $select_deactive_products->execute([$seller_id, "deactive"]);
                        $number_of_deactive_products = $select_deactive_products->rowCount();
                    ?>
                    <h3><?= $number_of_deactive_products; ?></h3>
                    <p>total deactive products</p>
                    <a href="view_product.php" class="btn">deactive product</a>
                </div>
                <!-- Total orders placed for this seller -->
                <div class="box">
                    <?php 
                        $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ?");
                        $select_orders->execute([$seller_id]);
                        $number_of_orders = $select_orders->rowCount();
                    ?>
                    <h3><?= $number_of_orders; ?></h3>
                    <p>total orders placed</p>
                    <a href="admin_order.php" class="btn">total orders</a>
                </div>
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