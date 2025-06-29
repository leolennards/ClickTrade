<?php 
    // Connect to the database
    include 'components/connect.php';

    // Check if the user is already logged in
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    } else {
        $user_id = '';
    }

    // Handle registration form submission
    if (isset($_POST['submit'])) {

        $id = unique_id();

        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);

        $pass = htmlspecialchars($_POST['pass']);
        $pass_hashed = sha1($pass); // hash after sanitizing

        $cpass = htmlspecialchars($_POST['cpass']);
        $cpass_hashed = sha1($cpass); // hash after sanitizing

        $image = htmlspecialchars($_FILES['image']['name']);
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = unique_id().'.'.$ext;
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'uploaded_files/'.$rename;

        // Check if email already exists
        $select_seller = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $select_seller->execute([$email]);

        if ($select_seller->rowCount() > 0) {
            $warning_msg[] = 'Email already exists!';
        } else {
            if ($pass_hashed != $cpass_hashed) {
                $warning_msg[] = 'Confirm password not matched!';
            } else {
                // Insert new user into users table
                $insert_seller = $conn->prepare("INSERT INTO `users`(id, name, email, password, image) VALUES(?,?,?,?,?)");
                $insert_seller->execute([$id, $name, $email, $cpass_hashed, $rename]);
                move_uploaded_file($image_tmp_name, $image_folder);

                // Automatically create a seller account for this user
                $insert_auto_seller = $conn->prepare("INSERT INTO `sellers`(id, user_id, name, email, password, image) VALUES(?,?,?,?,?,?)");
                $insert_auto_seller->execute([unique_id(), $id, $name, $email, $cpass_hashed, $rename]);

                $success_msg[] = 'New user registered! Please login now';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClickTrade - user registration page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time (); ?>">

    <!-- Boxicons CDN -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Font Awesome cdn link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
    <!-- User header/navigation bar -->
    <?php include 'components/user_header.php'; ?>
    <div class="form-container">
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