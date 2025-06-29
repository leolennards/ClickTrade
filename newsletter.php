<?php 
    // Connect to the database
    include 'components/connect.php';

    // Check if the user is logged in
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    } else {
        $user_id = '';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClickTrade - newsletter page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css">

    <!-- Boxicons CDN -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Font Awesome cdn link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
    <!-- User header/navigation bar -->
    <?php include 'components/user_header.php'; ?>

    <div class="newsletter">
        <div class="content">
            <span>get latest ClickTrade updates</span>
            <h1>subscribe to our newsletter</h1>
            <p>Stay updated with the latest news, and exclusive offers from ClickTrade. Join our community and never miss an important update!</p>
            <div class="input-field">
                <input type="email" name="" placeholder="Enter your E-Mail">
                <button class="btn">subscribe</button>
            </div>
            <p>No ads, No trials, No commitment</p>
            <div class="box-container">
                <div class="box">
                    <div class="box-counter"><p class="counter">5000</p><i class="bx bx-plus"></i></div>
                    <h3>Active Subscribers</h3>
                    <p>Join a growing community</p>
                </div>
                <div class="box">
                    <div class="box-counter"><p class="counter">10000</p><i class="bx bx-plus"></i></div>
                    <h3>Certification seller</h3>
                    <p>Our readers love the updates</p>
                </div>
            </div>
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