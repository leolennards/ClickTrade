<?php 
    // Connect to the database
    include 'components/connect.php';

    // Check if the user is logged in
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    } else {
        $user_id = '';
    }

    // Handle wishlist and cart actions
    include 'components/add_wishlist.php';
    include 'components/add_cart.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClickTrade - our shop page</title>
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
            <h1>Shop</h1>
            <img src="image/separator-img.png" alt="">
        </div>
        <div class="box-container">
            <?php 
                // Fetch all active products from the database
                $select_products = $conn->prepare("SELECT * FROM `products` WHERE status = ?");
                $select_products->execute(['active']);

                if ($select_products->rowCount() > 0) {
                    while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <form action="" method="post" class="box <?php if($fetch_products['stock'] == 0){echo "disabled";} ?>">
                <!-- Product image -->
                <img src="uploaded_files/<?= $fetch_products['image']; ?>" alt="" class="image">

                <!-- Show stock status with color coding -->
                <?php if($fetch_products['stock'] > 9){ ?>
                    <span class="stock" style="color: green;">In stock</span>
                <?php }elseif($fetch_products['stock'] == 0){ ?>
                    <span class="stock" style="color: red;">Out of stock</span>
                <?php }else{ ?>
                    <span class="stock" style="color: red;">Only <?= $fetch_products['stock']; ?></span>
                <?php } ?>
                <div class="content">
                    <img src="image/shape-19.png" alt="" class="shap">
                    <div class="button">
                        <div>
                            <h3 class="name"><?= $fetch_products['name']; ?></h3>
                        </div>
                        <div>
                            <!-- Add to cart, wishlist, and view buttons -->
                            <button type="submit" name="add_to_cart"><i class="bx bx-cart"></i></button>
                            <button type="submit" name="add_to_wishlist"><i class="bx bx-heart"></i></button>
                            <a href="view_page.php?pid=<?= $fetch_products['id'] ?>" class="bx bxs-show"></a>
                        </div>
                    </div>
                    <p class="price">price R<?= $fetch_products['price']; ?></p>
                    <input type="hidden" name="product_id" value="<?= $fetch_products['id'] ?>">

                    <!-- Buy Now section (not a form) -->
                    <div style="margin-top:10px;">
                        <input type="number" id="qty_<?= $fetch_products['id'] ?>" min="1" value="1" max="<?= $fetch_products['stock']; ?>" maxlength="2" class="qty box" style="width:60px;display:inline;">
                        <button type="button" class="btn" onclick="buyNow('<?= $fetch_products['id'] ?>')">buy now</button>
                    </div>
                </div>
            </form>
            <?php
                    }
                }else {
                    echo '
                        <div class="empty">
                            <p>no products added yet!</p>
                        </div>
                        ';

                }
            ?>
        </div>
    </div>
    


     <?php include 'components/footer.php'; ?>

    <!-- sweetalert cdn link-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- custom js link-->
    <script src="js/user_script.js"></script>

    <?php include 'components/alert.php'; ?>
    <script>
    function buyNow(productId) {
        var qty = document.getElementById('qty_' + productId).value;
        if (!qty || qty < 1) qty = 1;
        window.location.href = 'checkout.php?get_id=' + productId + '&qty=' + qty;
    }
    </script>
</body>
</html>