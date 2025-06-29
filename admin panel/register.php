<?php
    // Connect to the database
    include '../components/connect.php';

    // If the registration form is submitted, handle the registration logic
    if (isset($_POST['submit'])) {

        // Generate a unique ID for the new seller
        $id = unique_id();

        // Sanitize all the form inputs
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);

        $pass = htmlspecialchars($_POST['pass']);
        $pass_hashed = sha1($pass); // Hash the password after sanitizing

        $cpass = htmlspecialchars($_POST['cpass']);
        $cpass_hashed = sha1($cpass); // Hash the confirm password after sanitizing

        // Handle the profile image upload
        $image = htmlspecialchars($_FILES['image']['name']);
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = unique_id().'.'.$ext;
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../uploaded_files/'.$rename;

        // Check if the email is already registered
        $select_seller = $conn->prepare("SELECT * FROM `sellers` WHERE email = ?");
        $select_seller->execute([$email]);

        if ($select_seller->rowCount() > 0) {
            // Warn if the email already exists
            $warning_msg[] = 'Email already exists!';
        } else {
            // Check if passwords match
            if ($pass_hashed != $cpass_hashed) {
                $warning_msg[] = 'Confirm password not matched!';
            } else {
                // Insert the new seller into the database
                $insert_seller = $conn->prepare("INSERT INTO `sellers`(id, user_id, name, email, password, image) VALUES(?,?,?,?,?,?)");
                $insert_seller->execute([$id, $user_id, $name, $email, $cpass_hashed, $rename]);
                move_uploaded_file($image_tmp_name, $image_folder);

                $success_msg[] = 'New seller registered! Please login now';
            }
        }
    }

    // Check if the user is already logged in as a user (not a seller)
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    } else {
        $user_id = null;
    }

    // If the user is logged in, check if they already have a linked seller account
    if ($user_id) {
        $check_linked = $conn->prepare("SELECT * FROM `sellers` WHERE user_id = ?");
        $check_linked->execute([$user_id]);
        if ($check_linked->rowCount() > 0) {
            $warning_msg[] = 'You already have a seller account linked to your user account!';
            // Optionally redirect or block registration
        }
    }

    // After handling registration, redirect to the main register page (prevents form resubmission)
    header('Location: ../register.php');
    exit();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClickTrade - seller registration page</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">

    <!-- Font Awesome cdn link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>

    <div class="form-container">
        <!-- Seller registration form -->
        <form action="" method="post" enctype="multipart/form-data" class="register">
            <h3>Register now</h3>
            <div class="flex">
                <div class="col">
                    <div class="input-field">
                        <p>your name <span>*</span></p>
                        <input type="text" name="name" placeholder="enter your name" maxlength="50" required class="box">
                    </div>
                    <div class="input-field">
                        <p>your email <span>*</span></p>
                        <input type="email" name="email" placeholder="enter your email" maxlength="50" required class="box">
                    </div>
                </div>
                <div class="col">
                    <div class="input-field">
                        <p>your password <span>*</span></p>
                        <input type="password" name="pass" placeholder="enter your password" maxlength="50" required class="box">
                    </div>
                    <div class="input-field">
                        <p>confirm password <span>*</span></p>
                        <input type="password" name="cpass" placeholder="confirm your password" maxlength="50" required class="box">
                    </div>
                </div>
            </div>
            <div class="input-field">
                <p>your profile <span>*</span></p>
                <input type="file" name="image" accept="image/*" required class="box">
            </div>
            <p class="link">already have an account? <a href="login.php">login now</a> </p>
            <input type="submit" name="submit" value="register now" class="btn">
        </form>
    </div>
    






    <!-- sweetalert cdn link-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- custom js link-->
    <script src="../js/script.js"></script>

    <?php include '../components/alert.php'; ?>


</body>
</html>