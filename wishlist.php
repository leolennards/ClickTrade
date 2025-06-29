<?php 
    // Connect to the database
    include 'components/connect.php';

    // Check if the user is logged in
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    } else {
        $user_id = 'location:login.php';
    }

    // Allow adding wishlist items to cart
    include 'components/add_cart.php';

    // Remove product from wishlist if requested
    if (isset($_POST['delete_item'])) {
        $wishlist_id = htmlspecialchars(trim($_POST['wishlist_id']), ENT_QUOTES, 'UTF-8');

        $verify_delete = $conn->prepare("SELECT * FROM `wishlist` WHERE id = ?");
        $verify_delete->execute([$wishlist_id]);

        if ($verify_delete->rowCount() > 0) {
            $delete_wishlist_id = $conn->prepare("DELETE FROM `wishlist` WHERE id = ?");
            $delete_wishlist_id->execute([$wishlist_id]);
            $success_msg[] = 'product removed from wishlist!';
        } else {
            $warning_msg[] = 'product already removed from wishlist!';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClickTrade - my wishlist page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time (); ?>">

    <!-- Boxicons CDN -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Font Awesome cdn link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
    <!-- User header/navigation bar -->
    <?php include 'components/user_header.php'; ?>
    <div class="products">
        <div class="heading">
            <h1>my wishlist</h1>
            <img src="image/separator-img.png" alt="">
        </div>
        <div class="box-container">
            <?php
                $grand_total = 0;

                // Fetch all wishlist items for this user
                $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
                $select_wishlist->execute([$user_id]);

                if ($select_wishlist->rowCount() > 0) {
                    while ($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)) {

                        // Fetch product details for each wishlist item
                        $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                        $select_products->execute([$fetch_wishlist['product_id']]);

                        if ($select_products->rowCount() > 0) {
                            $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
            ?>
            <form action="" method="post" class="box <?php if($fetch_products['stock'] == 0){echo "disabled";} ?>">
                <input type="hidden" name="wishlist_id" value="<?= $fetch_wishlist['id']; ?>">
                <img src="uploaded_files/<?= $fetch_products['image']; ?>" class="image" alt="">
                <?php if($fetch_products['stock'] > 9){ ?>
                    <span class="stock" style="color: green;">in stock</span>
                <?php }elseif($fetch_products['stock'] == 0){ ?>
                    <span class="stock" style="color: red;">out of stock</span>
                <?php }else { ?>
                    <span class="stock" style="color: red;">only <?= $fetch_products['stock']; ?> left</span>
                <?php } ?>
                <div class="content">
                    <img src="image/shape-19.png" class="shap" alt="">
                    <div class="button">
                        <div><h3><?= $fetch_products['name']; ?></h3></div>
                        <div>
                            <!-- Add to cart, view, and remove from wishlist buttons -->
                            <button type="submit" name="add_to_cart"> <i class="bx bx-cart"></i></button>
                            <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="bx bxs-show"></a>
                            <button type="submit" name="delete_item" onclick="return confirm('remove from wishlist');"><i class="bx bx-x"></i></button>
                        </div>
                    </div>
                    <input type="hidden" name="product_id" value="<?= $fetch_products['id']; ?>">
                    <div class="flex">
                        <p class="price">price R<?= $fetch_products['price']; ?></p>
                    </div>
                    <div class="flex">
                        <input type="hidden" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty">
                        <a href="checkout.php?get_id=<?= $fetch_products['id']; ?>" class="btn">buy now</a>
                    </div>
                </div>
            </form>
            <?php 
                        $grand_total+= $fetch_wishlist['price'];
                        }
                    }
                }else {
                    // If no wishlist items, show a friendly message
                    echo '
                        <div class="empty">
                            <p>no products added yet!</p>
                        </div>
                    ';
                }
            ?>
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