<?php 
    // Connect to the database
    include 'components/connect.php';

    // Check if the user is logged in, otherwise redirect to login page
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    } else {
        $user_id = '';
        header('location:login.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClickTrade - user order page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time (); ?>">

    <!-- Boxicons CDN -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Font Awesome cdn link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
    <!-- User header/navigation bar -->
    <?php include 'components/user_header.php'; ?>
    <div class="orders">
        <div class="heading">
            <h1>my orders</h1>
            <img src="image/separator-img.png" alt="">
        </div>
        <div class="box-container">
            <?php
                // Fetch all orders for this user, most recent first
                $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? ORDER BY date DESC");
                $select_orders->execute([$user_id]);

                if ($select_orders->rowCount() > 0) {
                    while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                        $product_id = $fetch_orders['product_id'];

                        // Fetch product details for each order
                        $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                        $select_products->execute([$product_id]);

                        if ($select_products->rowCount() > 0) {
                            while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <div class="box" <?php if($fetch_orders['status'] == 'cancelled'){echo 'style = "border:2px solid red"';} ?>>
                <a href="view_order.php?get_id=<?= $fetch_orders['id']; ?>">
                    <img src="uploaded_files/<?= $fetch_products['image'] ?>" class="image" alt="">
                    <p class="date"> <i class="bx bxs-calender-alt"></i> <?= $fetch_orders['date']; ?></p>
                    <div class="content">
                        <img src="image/shape-19.png" class="shap" alt="">
                        <div class="row">
                            <h3 class="name"><?= $fetch_products['name'] ?></h3>
                            <p class="price">Price : R<?= $fetch_products['price'] ?></p>
                            <p class="status" style="color:<?php if($fetch_orders['status'] == 'delivered'){echo "green";}elseif($fetch_orders['status'] == 'cancelled'){echo "red";}else{echo "orange";} ?>"><?= $fetch_orders['status']; ?></p>
                        </div>
                    </div>
                </a>
            </div>
            <?php
                            }
                        }
                    }
                } else {
                    // If no orders, show a friendly message
                    echo '<p class="empty">no order placed yet</p>';
                }
            ?>
        </div>
    </div>

    <!-- Footer section -->
    <?php include 'components/footer.php'; ?>

    <!-- sweetalert cdn link-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- custom js link-->
    <script src="js/user_script.js"></script>

    <!-- Show any alert messages (success, warning, etc.) -->
    <?php include 'components/alert.php'; ?>
</body>
</html>