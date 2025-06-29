<?php
    // Connect to the database
    include '../components/connect.php';

    // Check if the user is logged in and is admin
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
        $select_user = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $select_user->execute([$user_id]);
        $fetch_profile = $select_user->fetch(PDO::FETCH_ASSOC);
        if ($fetch_profile['is_admin'] != 1) {
            header('location: ../login.php');
            exit();
        }
        // Fetch seller_id for this admin user
        $select_seller = $conn->prepare("SELECT * FROM sellers WHERE user_id = ?");
        $select_seller->execute([$user_id]);
        if ($select_seller->rowCount() > 0) {
            $fetch_seller = $select_seller->fetch(PDO::FETCH_ASSOC);
            $seller_id = $fetch_seller['id'];
        } else {
            $seller_id = null;
        }
    } else {
        header('location: ../login.php');
        exit();
    }

    // Handle deleting a message if the delete button was clicked
    if (isset($_POST['delete_msg'])) {
        // Get the ID of the message to delete and sanitize it
        $delete_id = htmlspecialchars($_POST['delete_id'], ENT_QUOTES, 'UTF-8');

        // Check if the message actually exists before trying to delete it
        $verify_delete = $conn->prepare("SELECT * FROM `message` WHERE id = ?");
        $verify_delete->execute([$delete_id]);

        if ($verify_delete->rowCount() > 0) {
            // If the message exists, delete it from the database
            $delete_msg = $conn->prepare("DELETE FROM `message` WHERE id = ?");
            $delete_msg->execute([$delete_id]);

            // Let the user know the message was deleted
            $_SESSION['success_msg'] = 'Message has been successfully deleted';

            // Redirect to avoid form resubmission and refresh the list
            header("Location: admin_message.php");
            exit();
        } else {
            // If the message was already deleted, show a warning
            $warning_msg[] = 'message already deleted';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClickTrade - admin message page</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">

    <!-- Boxicons for icons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Font Awesome for more icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
<div class="main-container">
        <!-- Include the admin header/navigation -->
        <?php include '../components/administrator_header.php'; ?>
        <section class="message-container">
            <div class="heading">
                <h1>unread messages</h1>
                <img src="../image/separator-img.png" alt="">
            </div>
            <div class="box-container">
                <?php  
                    // Fetch all messages from the database
                    $select_message = $conn->prepare("SELECT * FROM `message`");
                    $select_message->execute();
                    if ($select_message->rowCount() > 0) {
                        // Loop through each message and display it in a box
                        while ($fetch_mesage = $select_message->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="box">
                    <!-- Show the sender's name -->
                    <h3 class="name"><?= $fetch_mesage['name']; ?></h3>
                    <!-- Show the subject of the message -->
                    <h4><?= $fetch_mesage['subject']; ?></h4>
                    <!-- Show the actual message content -->
                    <p><?= $fetch_mesage['message']; ?></p>
                    <!-- Form to delete this message -->
                    <form action="" method="post" onsubmit="return confirm('delete this message?');">
                        <input type="hidden" name="delete_id" value="<?= $fetch_mesage['id']; ?>">
                        <input type="submit" name="delete_msg" value="delete message" class="btn">
                    </form>
                </div>
                <?php  
                        }
                    } else {
                        // If there are no messages, show a friendly message
                        echo '
                            <div class="empty">
                                <p>no unread message yet!</p>
                            </div>
                        ';
                    }
                ?>
            </div>
        </section>
    </div>

    <!-- SweetAlert for nice popup messages -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- Custom admin JS for UI interactions -->
    <script src="../js/admin_script.js"></script>

    <!-- Show any alert messages (success, warning, etc.) -->
    <?php include '../components/alert.php'; ?>
</body>
</html>