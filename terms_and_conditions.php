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
    <title>ClickTrade - Terms & Conditions</title>
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
            <h1>Terms & Conditions</h1>
            <img src="image/separator-img.png" alt="">
        </div>
        <p>Welcome to ClickTrade. By accessing or using our website, you agree to be bound by the following terms and conditions. Please read them carefully.</p>
        
        <h2>1. Use of the Website</h2>
        <p>
            You agree to use this website only for lawful purposes and in a way that does not infringe the rights of, restrict, or inhibit anyone else's use of the website.
        </p>
        
        <h2>2. Account Registration</h2>
        <p>
            To access certain features, you may need to register an account. You are responsible for maintaining the confidentiality of your account information and for all activities that occur under your account.
        </p>
        
        <h2>3. Orders and Payments</h2>
        <p>
            All orders placed through ClickTrade are subject to acceptance and availability. We reserve the right to refuse or cancel any order. Payment must be made in full before goods are dispatched.
        </p>
        
        <h2>4. Intellectual Property</h2>
        <p>
            All content on this website, including text, graphics, logos, and images, is the property of ClickTrade or its content suppliers and is protected by copyright laws.
        </p>
        
        <h2>5. Limitation of Liability</h2>
        <p>
            ClickTrade will not be liable for any damages arising from the use or inability to use this website or from any information, content, materials, or products included on or otherwise made available to you through this site.
        </p>
        
        <h2>6. Changes to Terms</h2>
        <p>
            We reserve the right to update or modify these terms at any time without prior notice. Your continued use of the website constitutes your acceptance of any changes.
        </p>
        
        <h2>7. Governing Law</h2>
        <p>
            These terms are governed by the laws of South Africa. Any disputes will be subject to the exclusive jurisdiction of the courts of South Africa.
        </p>
        
        <h2>8. Contact Us</h2>
        <p>
            If you have any questions about these Terms & Conditions, please contact us at <a href="mailto:ClickTradeMarketplace@gmail.com">ClickTradeMarketplace@gmail.com</a>.
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