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

// Handle product deletion
if (isset($_POST['delete_post'])) {
    $delete_post_id = $_POST['delete_post_id'];
    $conn->prepare("DELETE FROM products WHERE id = ?")->execute([$delete_post_id]);
    header("Location: admin_manage_posts.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Admin</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #eee;
        }
    </style>
</head>
<body>
    
    <div class="main-container">
        <?php include '../components/administrator_header.php'; ?>
        <section class="dashboard">
            <div class="heading">
                <h1>All Products</h1>
                <img src="../image/separator-img.png" alt="">
            </div>
            <div class="user-table-container">
                <table>
                    <tr>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Seller Name</th>
                        <th>Seller ID</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    // Join products with sellers to get seller name
                    $products = $conn->query("SELECT p.*, s.name AS seller_name FROM products p LEFT JOIN sellers s ON p.seller_id = s.id")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($products as $product) {
                        echo "<tr>
                            <td><img src=\"../uploaded_files/{$product['image']}\" class=\"product-img\" alt=\"Product Image\"></td>
                            <td>{$product['name']}</td>
                            <td>" . htmlspecialchars($product['seller_name'] ?? 'Unknown') . "</td>
                            <td>{$product['seller_id']}</td>
                            <td>R{$product['price']}</td>
                            <td>{$product['status']}</td>
                            <td>
                                <form method='post' style='display:inline;'>
                                    <input type='hidden' name='delete_post_id' value='{$product['id']}'>
                                    <button type='submit' name='delete_post' class='btn' style='background:#e74c3c;color:#fff;' onclick=\"return confirm('Delete this product?');\">Delete</button>
                                </form>
                            </td>
                        </tr>";
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