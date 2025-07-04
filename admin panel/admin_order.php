<?php
    // Connect to the database so we can fetch and update orders
    include '../components/connect.php';

    // Check if the seller is logged in by looking for their cookie
    // If not logged in, redirect them to the login page
    if (isset($_COOKIE['seller_id'])) {
        $seller_id = $_COOKIE['seller_id'];
    } else {
        $seller_id = '';
        header('location:login.php');
    }

    // If the seller submits the "update payment" form, update the payment status for that order
    if (isset($_POST['update_order'])) {
        // Get the order ID and sanitize it
        $order_id = $_POST['order_id'];
        $order_id = htmlspecialchars($order_id);

        // Get the new payment status and sanitize it
        $update_payment = $_POST['update_payment'];
        $update_payment = htmlspecialchars($update_payment);

        // Update the payment status in the database
        $update_pay = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
        $update_pay->execute([$update_payment, $order_id]);
        $success_msg[] = 'payment status updated successfully!';
    }

    // If the seller submits the "delete order" form, delete that order from the database
    if (isset($_POST['delete_order'])) {
        // Get the order ID and sanitize it
        $delete_id = $_POST['order_id'];
        $delete_id = htmlspecialchars($delete_id);

        // Check if the order actually exists before trying to delete it
        $verify_delete = $conn->prepare("SELECT * FROM `orders` WHERE id = ?");
        $verify_delete->execute([$delete_id]);

        if ($verify_delete->rowCount() > 0) {
            // If the order exists, delete it
            $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
            $delete_order->execute([$delete_id]);
            $success_msg[] = 'order deleted successfully';
        } else {
            // If the order was already deleted, show a warning
            $warning_msg[] = 'order already deleted';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClickTrade - seller registration page</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">

    <!-- Boxicons CDN for icons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Font Awesome CDN for more icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>

    <div class="main-container">
        <!-- This includes the admin header/navigation bar -->
        <?php include '../components/admin_header.php'; ?>
        <section class="order-container">
            <div class="heading">
                <h1>total orders placed</h1>
                <img src="../image/separator-img.png" alt="">
            </div>
            <div class="box-container">
                <?php
                // Get all orders for this seller from the database
                $select_order = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ?");
                $select_order->execute([$seller_id]);

                // If there are orders, loop through and display each one
                if ($select_order->rowCount() > 0) {
                    while ($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="box">
                    <!-- Show the order status, color-coded for easy viewing -->
                    <div class="status" style="color: <?php if($fetch_order['status']=='in progress') {echo "limegreen";}else{echo "red";} ?>"><?= $fetch_order['status']; ?></div>
                    <div class="details">
                        <!-- Show all the important details for this order -->
                        <p>user name : <span><?= $fetch_order['name']; ?></span></p>
                        <p>user id : <span><?= $fetch_order['user_id']; ?></span></p>
                        <p>placed on : <span><?= $fetch_order['date']; ?></span></p>
                        <p>user number : <span>$<?= $fetch_order['number']; ?></span></p>
                        <p>user email : <span><?= $fetch_order['email']; ?></span></p>
                        <p>total price : <span><?= $fetch_order['price']; ?></span></p>
                        <p>payment method : <span><?= $fetch_order['method']; ?></span></p>
                        <p>user address : <span><?= $fetch_order['address']; ?></span></p>
                    </div>
                    <!-- Form to update payment status or delete the order -->
                    <form action="" method="post">
                        <input type="hidden" name="order_id" value="<?= $fetch_order['id']; ?>">
                        <select name="update_payment" class="box" style="width: 90%;">
                            <!-- Show the current payment status as the default option -->
                            <option disabled selected><?= $fetch_order['payment_status']; ?></option>
                            <option value="pending">pending</option>
                            <option value="order delivered">order delivered</option>
                        </select>
                        <div class="flex-btn">
                            <!-- Button to update payment status -->
                            <input type="submit" name="update_order" value="update payment" class="btn">
                            <!-- Button to delete the order, with a confirmation popup -->
                            <input type="submit" name="delete_order" value="delete order" class="btn" onclick="return confirm('delete this order?');">
                        </div>
                    </form>
                </div>
                <?php 
                    }
                } else {
                    // If there are no orders, show a friendly message
                    echo '
                            <div class="empty">
                                <p>no order placed yet!</p>
                            </div>
                        ';
                }
                ?>
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