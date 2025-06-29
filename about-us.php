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
    <title>ClickTrade - about us page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css">

    <!-- Boxicons CDN -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Font Awesome cdn link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
    <!-- User header/navigation bar -->
    <?php include 'components/user_header.php'; ?>

    <!-- Mission section -->
    <div class="chef">
        <div class="box-container">
            <div class="box">
                <div class="heading">
                    <span>Our</span>
                    <h1>Mission</h1>
                    <img src="image/separator-img.png" alt="">
                </div>
                <p>Our mission is to empower individuals and small businesses by providing a reliable and secure platform to buy, sell, and trade goods online. We believe in accessible commerce for everyone.</p>
            </div>
            <div class="box">
                <img src="image/mission.png" class="img" alt="">
            </div>
        </div>
    </div>

    <!-- Values section -->
    <div class="story">
        <div class="heading">
            <h1>our values</h1>
            <img src="image/separator-img.png" alt="">
        </div>
        <p>At ClickTrade, we value transparency, safety, and community. <br> We are committed to maintaining a fair environment where buyers and sellers can trust each other. <br> We believe that every successful trade starts with honesty and clarity, which is why we ensure that all listings, <br> user profiles, and transactions are easy to understand and straightforward.  </p>
        <a href="menu.php" class="btn">start browsing</a>
    </div>

    <!-- What makes us different section -->
    <div class="standers">
        <div class="detail">
            <div class="heading">
                <h1>what makes us different</h1>
                <img src="image/separator-img.png" alt="">
            </div>
            <p><i class="bx bx-desktop"></i> Easy-to-use interface</p>
            <p><i class="bx bx-shield"></i> Safety-first features to protect users</p>
            <p><i class="bx bx-bar-chart-alt"></i> Seller tools to help you grow</p>
            <p><i class="bx bx-group"></i> Community-driven and people-focused</p>
        </div>
    </div>
 
    <!-- Footer section -->
    <?php include 'components/footer.php'; ?>

    <!-- SweetAlert for nice popup messages -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- Custom JS for user interactions -->
    <script src="js/user_script.js"></script>

    <!-- Show any alert messages (success, warning, etc.) -->
    <?php include 'components/alert.php'; ?>
    
</body>
</html>