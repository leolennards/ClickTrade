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
    <title>ClickTrade - home page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css">

    <!-- Boxicons CDN -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Font Awesome cdn link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- Umami Analytics Script -->
    <script defer src="https://cloud.umami.is/script.js" data-website-id="cacc1c7b-ae4e-4df8-9b14-b8b3481e45ca"></script>
</head>
<body>
    <!-- User header/navigation bar -->
    <?php include 'components/user_header.php'; ?>

    <!-- Hero section with overlay and call to action -->
    <div class="ice-container">
        <div class="overlay"></div>
        <div class="detail">
            <h1>Discover Great Deals <br> Every Day on ClickTrade</h1>
            <p>Find everything you need, from the latest gadgets to unique secondhand treasures—all in one place. Making shopping simple, secure, and fun. Start exploring our marketplace today!</p>
            <a href="menu.php" class="btn">shop now</a>
        </div>
    </div>
    
    <!-- Seller invitation section -->
    <div class="pride">
        <div class="detail">
            <h1>Start Selling <br> Anytime, Any Place, Anywhere. </h1>
            <p>Turn your unused items into cash or grow your business with ClickTrade. Our platform makes it easy to list products, connect with buyers, and manage your sales—all from the comfort of your home.</p>
            <a href="admin panel/dashboard.php" class="btn">Start Selling</a>
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