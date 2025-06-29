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
    <title>ClickTrade - Press & News</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css">

    <!-- Boxicons CDN -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Font Awesome cdn link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
    <!-- User header/navigation bar -->
    <?php include 'components/user_header.php'; ?>

    <div class="terms-container">
        <div class="heading">
            <h1>Press & News</h1>
            <img src="image/separator-img.png" alt="">
        </div>
        <p>Stay up to date with the latest news, updates, and announcements from ClickTrade.</p>
        <ul>
            <li><strong>June 2025:</strong> ClickTrade launches new secure payment system for safer transactions.</li>
            <li><strong>May 2025:</strong> ClickTrade reaches 10,000 registered users!</li>
            <!-- Add more updates as you grow -->
        </ul>
        <h2>Media Inquiries</h2>
        <p>For press or media inquiries, please contact us at <a href="mailto:ClickTradeMarketplace@gmail.com">ClickTradeMarketplace@gmail.com</a>.</p>
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