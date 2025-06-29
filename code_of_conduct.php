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
    <title>ClickTrade - Code of Conduct</title>
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
            <h1>Code of Conduct</h1>
            <img src="image/separator-img.png" alt="">
        </div>
        <p>At ClickTrade, we are committed to providing a safe, respectful, and trustworthy marketplace for all users. By using our platform, you agree to:</p>
        <ul>
            <li>• Communicate honestly and respectfully with other users.</li>
            <li>• Only list items that you legally own and accurately describe them.</li>
            <li>• Not engage in fraud, scams, or misleading practices.</li>
            <li>• Respect the privacy and personal information of others.</li>
            <li>• Report suspicious or inappropriate behavior to our support team.</li>
        </ul>
        <p>Violations of this Code of Conduct may result in account suspension or removal from ClickTrade.</p>
        <h2>Contact Us</h2>
        <p>If you have questions or need to report a concern, please contact us at <a href="mailto:ClickTradeMarketplace@gmail.com">ClickTradeMarketplace@gmail.com</a>.</p>
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