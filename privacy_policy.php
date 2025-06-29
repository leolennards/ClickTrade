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
    <title>ClickTrade - Privacy Policy</title>
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
            <h1>Privacy Policy</h1>
            <img src="image/separator-img.png" alt="">
        </div>
        <p>Your privacy is important to us. This Privacy Policy explains how ClickTrade collects, uses, and protects your personal information when you use our website.</p>
        
        <h2>1. Information We Collect</h2>
        <p>
            We may collect personal information such as your name, email address, contact details, shipping address, and payment information when you register, place an order, or contact us.
        </p>
        
        <h2>2. How We Use Your Information</h2>
        <p>
            Your information is used to process orders, provide customer support, improve our services, send updates or marketing communications, and comply with legal obligations.
        </p>
        
        <h2>3. Sharing Your Information</h2>
        <p>
            We do not sell or rent your personal information. We may share your data with trusted third parties (such as payment processors or delivery services) only as necessary to fulfill your orders or comply with the law.
        </p>
        
        <h2>4. Data Security</h2>
        <p>
            We implement appropriate security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction.
        </p>
        
        <h2>5. Cookies</h2>
        <p>
            Our website uses cookies to enhance your browsing experience. You can choose to disable cookies in your browser settings, but this may affect site functionality.
        </p>
        
        <h2>6. Your Rights</h2>
        <p>
            You have the right to access, update, or request deletion of your personal information. Please contact us if you wish to exercise these rights.
        </p>
        
        <h2>7. Changes to This Policy</h2>
        <p>
            We may update this Privacy Policy from time to time. Changes will be posted on this page with an updated effective date.
        </p>
        
        <h2>8. Contact Us</h2>
        <p>
            If you have any questions about this Privacy Policy, please contact us at <a href="mailto:ClickTradeMarketplace@gmail.com">ClickTradeMarketplace@gmail.com</a>.
        </p>
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