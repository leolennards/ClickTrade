<?php
    // Connect to the database
    include '../components/connect.php';

    // Check if the login form has been submitted
    if (isset($_POST['submit'])) {

        // Get the email and password from the form and sanitize them
        $email = htmlspecialchars($_POST['email']);
        $pass = htmlspecialchars($_POST['pass']);
        $hashed_pass = sha1($pass); // Hash the password after sanitizing

        // Check if there is a seller with this email and password
        $select_seller = $conn->prepare("SELECT * FROM `sellers` WHERE email = ? AND password = ?");
        $select_seller->execute([$email, $hashed_pass]);
        $row = $select_seller->fetch(PDO::FETCH_ASSOC);

        if ($select_seller->rowCount() > 0) {
            // If login is successful, set a cookie for the seller and redirect to dashboard
            setcookie('seller_id', $row['id'], time() + 60*60*24*30, '/');
            header('Location: dashboard.php');
            exit();
        } else {
            // If login fails, show a warning message
            $warning_msg[] = 'Incorrect email or password!';
        }
    } else {
        // If the form wasn't submitted, redirect to the main login page
        header('Location: ../login.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClickTrade - login page</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">

    <!-- Font Awesome cdn link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>

    <div class="form-container">
        <!-- Seller login form -->
        <form action="" method="post" enctype="multipart/form-data" class="login">
            <h3>Login now</h3>

            <div class="input-field">
                <p>your email <span>*</span></p>
                <input type="email" name="email" placeholder="enter your email" maxlength="50" required class="box">
            </div>

            <div class="input-field">
                <p>your password <span>*</span></p>
                <input type="password" name="pass" placeholder="enter your password" maxlength="50" required class="box">
            </div>
            
            <p class="link">do not have an account? <a href="register.php">register now</a> </p>
            <input type="submit" name="submit" value="login now" class="btn">
        </form>
    </div>

    <!-- SweetAlert for nice popup messages -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- Custom admin JS for UI interactions -->
    <script src="../js/script.js"></script>

    <!-- Show any alert messages (success, warning, etc.) -->
    <?php include '../components/alert.php'; ?>

</body>
</html>