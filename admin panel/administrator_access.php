<?php
    // Connect to the database
    include '../components/connect.php';

    // Check if the user is logged in
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
        // Fetch user info
        $select_user = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $select_user->execute([$user_id]);
        $fetch_profile = $select_user->fetch(PDO::FETCH_ASSOC);

        // Check if user is admin
        if ($fetch_profile['is_admin'] != 1) {
            header('location: ../login.php');
            exit();
        }

        // Fetch seller_id for this admin user
        $select_seller = $conn->prepare("SELECT * FROM sellers WHERE user_id = ?");
        $select_seller->execute([$user_id]);
        if ($select_seller->rowCount() > 0) {
            $fetch_seller = $select_seller->fetch(PDO::FETCH_ASSOC);
            $seller_id = $fetch_seller['id'];
        } else {
            $seller_id = null;
        }
    } else {
        header('location: ../login.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClickTrade - administrator access page</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">

    <!-- Boxicons CDN for icons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Font Awesome CDN for more icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>

    <div class="main-container">
        <!-- Include the admin header/navigation bar -->
        <?php include '../components/administrator_header.php'; ?>
        <section class="dashboard">
            <div class="heading">
                <h1>Administrator Dashboard</h1>
                <img src="../image/separator-img.png" alt="">
            </div>
            <div class="box-container">
                <!-- Welcome box with seller's name -->
                <div class="box">
                    <h3>Welcome !</h3>
                    <p><?= $fetch_profile["name"]; ?></p>
                    <a href="administrator_update.php" class="btn">update profile</a>
                </div>

                <!-- Manage Users box for admin -->
                <div class="box">
                    <h3>Manage Users</h3>
                    <p>View and delete user accounts</p>
                    <a href="admin_manage_users.php" class="btn">manage users</a>
                </div>


                <!-- Manage Users posts box for admin -->
                <div class="box">
                    <h3>Manage Users Posts</h3>
                    <p>View and delete user posts</p>
                    <a href="admin_manage_posts.php" class="btn">manage posts</a>
                </div>


                <!-- Unread messages box -->
                <div class="box">
                    <?php 
                        // Count all messages in the system
                        $select_message = $conn->prepare("SELECT * FROM `message`");
                        $select_message->execute();
                        $number_of_msg = $select_message->rowCount();
                    ?>
                    <h3><?= $number_of_msg; ?></h3>
                    <p>unread message</p>
                    <a href="admin_message.php" class="btn">see message</a>
                </div>
                
                <!-- Total user accounts -->
                <div class="box">
                    <?php 
                        $select_users = $conn->prepare("SELECT * FROM `users`");
                        $select_users->execute();
                        $number_of_users = $select_users->rowCount();
                    ?>
                    <h3><?= $number_of_users; ?></h3>
                    <p>users account</p>
                    <a href="administrator_user_accounts.php" class="btn">see users</a>
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