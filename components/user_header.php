<header class="header">
    <section class="flex">
        <!-- Logo and navigation links -->
        <a href="home.php" class="logo"> <img src="image/ClickTrade-Logo.svg" width="150px"></a>
        <nav class="navbar">
            <a href="home.php">home</a>
            <a href="about-us.php">about us</a>
            <a href="menu.php">shop</a>
            <a href="order.php">order</a>
            <a href="contact.php">contact us</a>
        </nav>
        <!-- Product search form -->
        <form action="search_product.php" method="post" class="search-form">
            <input type="text" name="search_product" placeholder="search product..." required maxlength="100">
            <button type="submit" class="bx bx-search-alt-2" id="search_product_btn"></button>
        </form>
        <div class="icons">
            <!-- Menu and search icons for mobile -->
            <div class="bx bx-list-plus" id="menu-btn"></div>
            <div class="bx bx-search-alt-2" id="search-btn"></div>

            <?php
                // Count wishlist items for this user
                $count_wishlist_item = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
                $count_wishlist_item->execute([$user_id]);
                $total_wishlist_items = $count_wishlist_item->rowCount();
            ?>
            <a href="wishlist.php"> <i class="bx bx-heart"></i> <sup><?= $total_wishlist_items; ?></sup> </a>
            <?php
                // Count cart items for this user
                $count_cart_item = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                $count_cart_item->execute([$user_id]);
                $total_cart_items = $count_cart_item->rowCount();
            ?>
            <a href="cart.php"> <i class="bx bx-cart"></i> <sup><?= $total_cart_items; ?></sup> </a>
            <div class="bx bxs-user" id="user-btn"></div>
        </div>
        <div class="profile-detail">
            <?php 
            // Show profile details if user is logged in
            if (!empty($user_id)) {
                $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                $select_profile->execute([$user_id]);

                if ($select_profile->rowCount() > 0) {
                    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
                    <img src="uploaded_files/<?= $fetch_profile['image']; ?>">
                    <h3 style="margin-bottom: 1rem"><?= $fetch_profile['name']; ?></h3>
                    <div class="flex-btn">
                        <a href="profile.php" class="btn">view profile</a>
                        <a href="components/user_logout.php" onclick="return confirm('logout from this website');" class="btn">logout</a>
                    </div>
            <?php 
                } else {
            ?>
                    <!-- If user not found, prompt to login or register -->
                    <h3 style="margin-bottom: 1rem">please login or register</h3>
                    <div class="flex-btn">
                        <a href="login.php" class="btn">login</a>
                        <a href="register.php" class="btn">register</a>
                    </div>
            <?php 
                }
            } else {
            ?>
                <!-- If not logged in, prompt to login or register -->
                <h3 style="margin-bottom: 1rem">please login or register</h3>
                <div class="flex-btn">
                    <a href="login.php" class="btn">login</a>
                    <a href="register.php" class="btn">register</a>
                </div>
            <?php 
            }
            ?>
        </div>
    </section>
</header>
