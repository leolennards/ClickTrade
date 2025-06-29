<?php 
    // Connect to the database
    include 'components/connect.php';

    // Check if the user is logged in
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    } else {
        $user_id = '';
    }

    // Handle sending a message from the contact form
    if (isset($_POST['send_message'])) {
        if ($user_id != '') {
            $id = unique_id();
            $name = htmlspecialchars($_POST['name']);
            $email = htmlspecialchars($_POST['email']);
            $subject = htmlspecialchars($_POST['subject']);
            $message = htmlspecialchars($_POST['message']);

            // Check if this exact message already exists for this user
            $verify_message = $conn->prepare("SELECT * FROM `message` WHERE user_id = ? AND name = ? AND email = ? AND subject = ? AND message = ?");
            $verify_message->execute([$user_id, $name, $email, $subject, $message]);

            if ($verify_message->rowCount() > 0) {
                $warning_msg[] = 'message already exists';
            } else {
                // Insert the new message into the database
                $insert_message = $conn->prepare("INSERT INTO `message` (id, user_id, name, email, subject, message) VALUES(?, ?, ?, ?, ?, ?)");
                $insert_message->execute([$id, $user_id, $name, $email, $subject, $message]);
                $success_msg[] = 'comment sent successfully';
            }
        } else {
            $warning_msg[] = 'please login first';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClickTrade - contact us page</title>
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
        <div class="heading">
            <h1>contact us </h1>
            <p>Have questions or need assistance? Fill out the form below and our team will get back to you as soon as possible.</p>
            <img src="image/separator-img.png" alt="">
        </div>
        <!-- Contact form -->
        <form action="" method="post" class="register">
            <div class="input-field">
                <label>name <sup>*</sup></label>
                <input type="text" name="name" required placeholder="enter your name" class="box">
            </div>
            <div class="input-field">
                <label>email <sup>*</sup></label>
                <input type="email" name="email" required placeholder="enter your email" class="box">
            </div>
            <div class="input-field">
                <label>subject <sup>*</sup></label>
                <input type="text" name="subject" required placeholder="reason..." class="box">
            </div>
            <div class="input-field">
                <label>comment <sup>*</sup></label>
                <textarea name="message" cols="30" rows="10" required placeholder="" class="box"></textarea>
            </div>
            <button type="submit" name="send_message" class="btn">send message</button>
        </form>
    </div>

    <!-- Contact details section -->
    <div class="address">
        <div class="heading">
            <h1>our contact details</h1>
            <p>Get in touch with us using the details below. We're here to help with any inquiries or support you may need.</p>
            <img src="image/separator-img.png" alt="">
        </div>
        <div class="box-container">
            <div class="box">
                <i class="bx bxs-map-alt"></i>
                <div>
                    <h4>address</h4>
                    <p>21 Hartbees Street, Parow Valley <br> Cape Town, South Africa</p>
                </div>
            </div>
            <div class="box">
                <i class="bx bxs-phone-incoming"></i>
                <div>
                    <h4>phone number</h4>
                    <p>082 262 6130</p>
                    <p>082 484 0408</p>
                </div>
            </div>
            <div class="box">
                <i class="bx bxs-envelope"></i>
                <div>
                    <h4>email</h4>
                    <p>ClickTradeMarketplace@gmail.com</p>
                    <p>ClickTradeMarketplace1@gmail.com</p>
                </div>
            </div>
        </div>
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