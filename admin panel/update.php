<?php
    // Connect to the database
    include '../components/connect.php';

    // Check if the seller is logged in, otherwise redirect to login page
    if (isset($_COOKIE['seller_id'])) {
        $seller_id = $_COOKIE['seller_id'];
    } else {
        $seller_id = '';
        header('location:login.php');
    }

    // If the update profile form is submitted, handle all updates
    if (isset($_POST['submit'])) {

        // Fetch the current seller's details for comparison
        $select_seller = $conn->prepare("SELECT * FROM `sellers` WHERE id = ? LIMIT 1");
        $select_seller->execute([$seller_id]);
        $fetch_seller = $select_seller->fetch(PDO::FETCH_ASSOC);

        $prev_pass = $fetch_seller['password'];
        $prev_image = $fetch_seller['image'];

        // Sanitize the new name and email from the form
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);

        // Update name if provided
        if (!empty($name)) {
            $update_name = $conn->prepare("UPDATE `sellers` SET name = ? WHERE id = ?");
            $update_name->execute([$name, $seller_id]);
            $success_msg[] = 'username updated successfully!';
        }

        // Update email if provided and not already taken
        if (!empty($email)) {
            $select_email = $conn->prepare("SELECT * FROM `sellers` WHERE id = ? AND email = ?");
            $select_email->execute([$seller_id, $email]);
            
            if ($select_email->rowCount() > 0) {
                $warning_msg[] = 'email already taken!';
            } else {
                $update_email = $conn->prepare("UPDATE `sellers` SET email = ? WHERE id = ?");
                $update_email->execute([$email, $seller_id]);
                $success_msg[] = 'email updated successfully!';
            }
        }

        // Handle profile image update
        $image = htmlspecialchars($_FILES['image']['name']);
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = unique_id().'.'.$ext;
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../uploaded_files/'.$rename;

        if (!empty($image)) {
            if ($image_size > 2000000) {
                $warning_msg[] = 'image size is too large';
            } else {
                $update_image = $conn->prepare("UPDATE `sellers` SET `image` = ? WHERE id = ?");
                $update_image->execute([$rename, $seller_id]);
                move_uploaded_file($image_tmp_name, $image_folder);

                // Delete the old image if it's different from the new one
                if ($prev_image != '' && $prev_image != $rename) {
                    unlink('../uploaded_files/'.$prev_image);
                }
                $success_msg[] = 'image updated successfully!';
            }
        }

        // Handle password update
        $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709'; // SHA1 of empty string

        $old_pass = htmlspecialchars($_POST['old_pass']);
        $old_pass = sha1($old_pass);

        $new_pass = htmlspecialchars($_POST['new_pass']);
        $new_pass = sha1($new_pass);   

        $cpass = htmlspecialchars($_POST['cpass']);
        $cpass = sha1($cpass);

        if ($old_pass != $empty_pass) {
            if ($old_pass != $prev_pass) {
                $warning_msg[] = 'old password not matched!';
            } elseif ($new_pass != $cpass) {
                $warning_msg[] = 'password not matched!';
            } else {
                if ($new_pass != $empty_pass) {
                    $update_pass = $conn->prepare("UPDATE `sellers` SET password = ? WHERE id = ?");
                    $update_pass->execute([$cpass, $seller_id]);
                    $success_msg[] = 'password updated successfully!';
                } else {
                    $warning_msg[] = 'please enter a new password!';
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClickTrade - update profile page</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">

    <!-- Boxicons CDN -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Font Awesome cdn link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>

    <div class="main-container">
        <!-- Include the admin header/navigation -->
        <?php include '../components/admin_header.php'; ?>
        
        <section class="form-container">
            <div class="heading">
                <h1>update profile details</h1>
                <img src="../image/separator-img.png" alt="">
            </div>
            <!-- Profile update form -->
            <form action="" method="post" enctype="multipart/form-data" class="register">
                <div class="img-box">
                    <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
                </div>
                <div class="flex">
                    <div class="col">
                        <div class="input-field">
                            <p>your name <span>*</span> </p>
                            <input type="text" name="name" placeholder="<?= $fetch_profile['name']; ?>" class="box">
                        </div>
                        <div class="input-field">
                            <p>your email <span>*</span> </p>
                            <input type="email" name="email" placeholder="<?= $fetch_profile['email']; ?>" class="box">
                        </div>
                        <div class="input-field">
                            <p>select pic <span>*</span> </p>
                            <input type="file" name="image" accept="image/*" class="box">
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-field">
                            <p>old password <span>*</span> </p>
                            <input type="password" name="old_pass" placeholder="enter your old password" class="box">
                        </div>
                        <div class="input-field">
                            <p>new password <span>*</span> </p>
                            <input type="password" name="new_pass" placeholder="enter your new password" class="box">
                        </div>
                        <div class="input-field">
                            <p>confirm password <span>*</span> </p>
                            <input type="password" name="cpass" placeholder="confirm your password" class="box">
                        </div>
                    </div>                     
                </div>
                <input type="submit" name="submit" value="update profile" class="btn">
            </form>
        </section>
    </div>

    <!-- sweetalert cdn link-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- custom js link-->
    <script src="../js/admin_script.js"></script>

    <?php include '../components/alert.php'; ?>


</body>
</html>