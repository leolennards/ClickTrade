<?php 
    // Connect to the database
    include 'components/connect.php';

    // Check if the user is logged in
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    }else {
        $user_id = '';
    }

    // Get the order ID from the URL, or redirect if not set
    if (isset($_GET['get_id'])) {
        $get_id = $_GET['get_id'];
    }else {
        $get_id = '';
        header('location:order.php');
    }

    // Handle order cancellation
    if (isset($_POST['cancel'])) {
        $update_order = $conn->prepare("UPDATE `orders` SET status = ? WHERE id = ?");
        $update_order->execute(['cancelled', $get_id]);
        header('location:order.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClickTrade - order detail page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time (); ?>">

    <!-- Boxicons CDN -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Font Awesome cdn link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- Bootstrap Icons link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <!-- User header/navigation bar -->
    <?php include 'components/user_header.php'; ?>
    <div class="order-detail">
        <div class="heading">
            <h1>my order detail</h1>
            <p>Here you can view the details of your order, including product information, billing address, and order status. If you need to make changes or reorder, use the options below.</p>
            <img src="image/separator-img.png" alt="">
        </div>
        <div class="box-container">
            <?php
            $grand_total = 0;
            // Fetch the order details for the given order ID
            $select_order = $conn->prepare("SELECT * FROM `orders` WHERE id = ? LIMIT 1");
            $select_order->execute([$get_id]);

            if ($select_order->rowCount() > 0) {
                while ($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)) {
                    // Fetch the product details for this order
                    $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
                    $select_product->execute([$fetch_order['product_id']]);
                    if ($select_product->rowCount() > 0) {
                        while ($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)) {
                            $sub_total = ($fetch_order['price'] * $fetch_order['qty']);
                            $grand_total += $sub_total;
            ?>
            <div class="box">
                <div class="col">
                    <p class="title"> <i class="bx bxs-calendar-alt"></i><?= $fetch_order['date']; ?> </p>
                    <img src="uploaded_files/<?= $fetch_product['image']; ?>" class="image" alt="">
                    <p class="price">R<?= $fetch_product['price']; ?></p>
                    <h3 class="name"><?= $fetch_product['name']; ?></h3>
                    <p class="grand-total">total amount payable : <span>R<?= $grand_total; ?></span></p>
                </div>
                <div class="col">
                    <p class="title">billing address</p>
                    <p class="user"><i class="bi bi-person-bounding-box"></i><?= $fetch_order['name'] ?></p>
                    <p class="user"><i class="bi bi-phone"></i><?= $fetch_order['number'] ?></p>
                    <p class="user"><i class="bi bi-envelope"></i><?= $fetch_order['email'] ?></p>
                    <p class="user">
                        <i class="bi bi-pin-map-fill"></i>
                        <?= $fetch_order['street'] ?>, <?= $fetch_order['suburb'] ?>, <?= $fetch_order['city'] ?>, <?= $fetch_order['province'] ?>, <?= $fetch_order['pin'] ?>
                    </p>

                    <p class="status" style="color:<?php if($fetch_order['status'] == 'delivered'){echo "green";}elseif($fetch_order['status'] == 'cancelled'){echo "red";}else{echo "orange";} ?>"><?= $fetch_order['status']; ?></p>

                    <?php if ($fetch_order['status'] == 'cancelled') { ?>
                        <!-- If cancelled, show order again button -->
                        <a href="checkout.php?get_id=<?= $fetch_product['id']; ?>" class="btn" style="line-height: 3">order again</a>
                    <?php }else { ?>
                        <!-- If not cancelled, allow cancellation -->
                        <form action="" method="post">
                            <button type="submit" name="cancel" class="btn" onclick="return confirm('do you want to cancel this product?');">cancel</button>
                        </form>
                    <?php } ?>
                </div>
            </div>
            <?php
                        }
                    }
                }
            } else {
                // If no order found, show a friendly message
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