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

    // Count how many products this seller has added
    $select_products = $conn->prepare("SELECT * FROM `products` WHERE seller_id = ?");
    $select_products->execute([$seller_id]);
    $total_products = $select_products->rowCount();

    // Count how many orders this seller has received
    $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ?");
    $select_orders->execute([$seller_id]);
    $total_orders = $select_orders->rowCount();

    // Fetch the seller's profile details
    $select_seller = $conn->prepare("SELECT * FROM `sellers` WHERE id = ?");
    $select_seller->execute([$seller_id]);
    if ($select_seller->rowCount() > 0) {
        $fetch_seller = $select_seller->fetch(PDO::FETCH_ASSOC);
        // If this seller is also a user, show a button to switch to the user dashboard
        if (!empty($fetch_seller['user_id'])) {
            echo '<a href="../profile.php" class="btn" style="margin:1rem;">Switch to User Dashboard</a>';
        }
    }

    // Fetch the seller's profile for display (used below)
    $fetch_profile = $fetch_seller;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClickTrade - seller profile page</title>
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
        <section class="seller-profile">
            <div class="heading">
                <h1>profile details</h1>
                <img src="../image/separator-img.png" alt="">
            </div>
            <div class="details">
                <div class="seller">
                    <!-- Seller's profile image -->
                    <img src="../uploaded_files/<?= $fetch_profile['image']; ?>">
                    <!-- Seller's name -->
                    <h3 class="name"><?= $fetch_profile['name']; ?></h3>
                    <span>seller</span>
                    <!-- Button to update profile -->
                    <a href="update.php" class="btn">update profile</a>
                </div>
                <div class="flex">
                    <!-- Show total products for this seller -->
                    <div class="box">
                        <span><?= $total_products; ?></span>
                        <p>total products</p>
                        <a href="view_product.php" class="btn">view products</a>
                    </div>
                    <!-- Show total orders for this seller -->
                    <div class="box">
                        <span><?= $total_orders; ?></span>
                        <p>total orders placed</p>
                        <a href="admin_order.php" class="btn">view orders</a>
                    </div>
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