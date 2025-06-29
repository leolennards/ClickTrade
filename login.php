<?php 
    // Connect to the database
    include 'components/connect.php';

    // Check if the user is already logged in
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    } else {
        $user_id = '';
    }

    // Handle login form submission
    if (isset($_POST['submit'])) {
        $email = htmlspecialchars($_POST['email']);
        $pass = htmlspecialchars($_POST['pass']);
        $hashed_pass = sha1($pass); // hash after sanitizing

        // Check if user exists with this email and password
        $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
        $select_user->execute([$email, $hashed_pass]);
        $row = $select_user->fetch(PDO::FETCH_ASSOC);

        if ($select_user->rowCount() > 0) {
            // Set user cookie for 30 days
            setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
            // Find the seller account linked to this user
            $find_seller = $conn->prepare("SELECT * FROM `sellers` WHERE user_id = ?");
            $find_seller->execute([$row['id']]);
            if ($find_seller->rowCount() > 0) {
                $seller_row = $find_seller->fetch(PDO::FETCH_ASSOC);
                setcookie('seller_id', $seller_row['id'], time() + 60*60*24*30, '/');
            }
            // Check if admin and redirect accordingly
            if (isset($row['is_admin']) && $row['is_admin'] == 1) {
                header('Location: admin panel/administrator_access.php');
            } else {
                header('Location: home.php');
            }
            exit();
        } else {
            $warning_msg[] = 'Incorrect email or password!';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClickTrade - user login page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time (); ?>">

    <!-- Boxicons CDN -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Font Awesome cdn link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
    <!-- User header/navigation bar -->
    <?php include 'components/user_header.php'; ?>
    <div class="banner">
        <div class="detail">
            <h1>login</h1>
            <p>Access your ClickTrade account to manage your profile, track your activity, and stay updated with the latest offers and news.</p>
            <span> <a href="home.php">home</a><i class="bx bx-right-arrow-alt"></i>login </span>
        </div>
    </div>
    <div class="form-container">
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