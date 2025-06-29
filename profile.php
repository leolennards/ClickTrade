<?php 
    // Connect to the database
    include 'components/connect.php';

    // Check if the user is logged in
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    } else {
        $user_id = 'location:login.php';
    }

    // Get total orders for this user
    $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
    $select_orders->execute([$user_id]);
    $total_orders = $select_orders->rowCount();

    // Get total messages for this user
    $select_message = $conn->prepare("SELECT * FROM `message` WHERE user_id = ?");
    $select_message->execute([$user_id]);
    $total_message = $select_message->rowCount();

    // Fetch user profile details
    $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
    $select_profile->execute([$user_id]);
    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClickTrade - user profile page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time (); ?>">

    <!-- Boxicons CDN -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Font Awesome cdn link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
    <!-- User header/navigation bar -->
    <?php include 'components/user_header.php'; ?>
    <section class="profile">
        <div class="heading">
            <h1>profile detail</h1>
            <img src="image/separator-img.png" alt="">
        </div>
        <div class="details">
            <div class="user">
                <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
                <h3><?= $fetch_profile['name']; ?></h3>
                <p>user</p>
                <a href="update.php" class="btn">update profile</a>
            </div>
            <div class="box-container">
                <div class="box">
                    <div class="flex">
                        <i class="bx bxs-folder-minus"></i>
                        <h3><?= $total_orders; ?></h3>
                    </div>
                    <a href="order.php" class="btn">view orders</a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Optionally, show a button to switch to seller dashboard if user is a seller -->
    <?php
    // Get the seller's linked user_id (if any)
    if (isset($seller_id)) {
        $select_seller = $conn->prepare("SELECT * FROM `sellers` WHERE id = ?");
        $select_seller->execute([$seller_id]);
        if ($select_seller->rowCount() > 0) {
            $fetch_seller = $select_seller->fetch(PDO::FETCH_ASSOC);
            if (!empty($fetch_seller['user_id'])) {
                echo '<a href="../profile.php" class="btn">Switch to User Dashboard</a>';
            }
        }
    }
    ?>

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