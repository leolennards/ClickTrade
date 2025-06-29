<?php
    // Connect to the database
    include '../components/connect.php';

    // Check if the user is logged in and is admin
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
        $select_user = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $select_user->execute([$user_id]);
        $fetch_profile = $select_user->fetch(PDO::FETCH_ASSOC);
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
    <title>ClickTrade - Registered users page</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">

    <!-- Boxicons CDN for icons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Font Awesome cdn link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>

    <div class="main-container">
        <!-- Include the admin header/navigation -->
        <?php include '../components/administrator_header.php'; ?>
        <section class="user-container">
            <div class="heading">
                <h1>registered users</h1>
                <img src="../image/separator-img.png" alt="">
            </div>
            <div class="box-container">
                <?php 
                    // Fetch all registered users from the database
                    $select_users = $conn->prepare("SELECT * FROM `users`");
                    $select_users->execute();

                    // If there are users, display each one in a box
                    if ($select_users->rowCount() > 0) {
                        while ($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)) {
                            $user_id = $fetch_users['id'];
                ?>
                <div class="box">
                     <!-- Show the user's profile image -->
                     <img src="../uploaded_files/<?= $fetch_users['image']; ?>">
                     <!-- Show the user's ID -->
                     <p>user id : <span><?= $user_id; ?></span></p>
                     <!-- Show the user's name -->
                     <p>user name : <span><?= $fetch_users['name']; ?></span></p>
                     <!-- Show the user's email -->
                     <p>user email : <span><?= $fetch_users['email']; ?></span></p>
                </div>
                <?php 
                        }
                    } else {
                        // If there are no users, show a friendly message
                        echo '
                            <div class="empty">
                                <p>no user registered yet!</p>
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