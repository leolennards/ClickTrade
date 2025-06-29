<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to the database
include '../components/connect.php';

// Check if the user is logged in
if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
    // Fetch user info
    $select_user = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $select_user->execute([$user_id]);
    $fetch_profile = $select_user->fetch(PDO::FETCH_ASSOC);

    // Check if user is admin
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

// Handle user deletion
if (isset($_POST['delete_user'])) {
    $delete_id = $_POST['delete_user_id'];
    // Prevent admin from deleting themselves
    if ($delete_id != $user_id) {
        $conn->prepare("DELETE FROM users WHERE id = ?")->execute([$delete_id]);
        // Optionally, delete user's posts, orders, etc. here
    }
    header("Location: admin_manage_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
    
    <div class="main-container">
        <?php include '../components/administrator_header.php'; ?>
        <section class="dashboard">
            <div class="heading">
                <h1>All Users</h1>
                <img src="../image/separator-img.png" alt="">
            </div>
            <!-- Move the table OUTSIDE the heading div -->
            <div class="user-table-container">
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $users = $conn->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($users as $user) {
                        echo "<tr>
                            <td>{$user['name']}</td>
                            <td>{$user['email']}</td>
                            <td>";
                        // Prevent admin from deleting themselves
                        if ($user['is_admin'] != 1) {
                            echo "<form method='post' style='display:inline;'>
                                <input type='hidden' name='delete_user_id' value='{$user['id']}'>
                                <button type='submit' name='delete_user' class='btn' style='background:#e74c3c;color:#fff;' onclick=\"return confirm('Delete this user?');\">Delete</button>
                            </form>";
                        } else {
                            echo "<span style='color:gray;'>Admin</span>";
                        }
                        echo "</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </section>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js"></script>
    <?php include '../components/alert.php'; ?>
</body>
</html>