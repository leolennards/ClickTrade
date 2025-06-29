<header>
    <div class="logo">
        <!-- ClickTrade logo in the header -->
        <img src="../image/ClickTrade-Logo.svg" width="150" alt="ClickTrade Logo">
    </div>
    <div class="right">
        <!-- User icon toggle button -->
        <div class="bx bxs-user" id="user-btn"></div>
    </div>
    <div class="profile-detail">
        <?php 
            // Fetch the seller's profile to show their info in the header
            $select_profile = $conn->prepare("SELECT * FROM `sellers` WHERE id = ?");
            $select_profile->execute([$seller_id]);
            
            if ($select_profile->rowCount() > 0) {
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="profile">
            <!-- Seller's profile image and name -->
            <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" class="logo-img" width="100" alt="">
            <p><?= $fetch_profile['name']; ?></p>
            <div class="flex-btn">
                <!-- Profile and logout buttons -->
                <a href="profile.php" class="btn">profile</a>
                <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');" class="btn">logout</a>
            </div>
        </div>
        <?php } ?>
    </div>
</header>

<div class="sidebar-container">
    <div class="sidebar">
        <?php 
            // Fetch the seller's profile again for the sidebar
            $select_profile = $conn->prepare("SELECT * FROM `sellers` WHERE id = ?");
            $select_profile->execute([$seller_id]);
            
            if ($select_profile->rowCount() > 0) {
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="profile">
            <!-- Seller's profile image and name in the sidebar -->
            <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" class="logo-img" width="100">
            <p><?= $fetch_profile['name']; ?></p>
            <?php
            // If this seller is also a user, show a button to switch dashboards
            if (!empty($fetch_profile['user_id'])) {
                echo '<a href="../home.php" class="btn" style="margin:1rem;">Switch to User Dashboard</a>';
            }
            ?>
        </div>
        <?php } ?>
        <h5>menu</h5>
        <div class="navbar">
            <ul>
                <!-- Sidebar navigation links -->
                <li><a href="dashboard.php"><i class="bx bxs-home-smile"></i>Dashboard</a></li>
                <li><a href="add_products.php"><i class="bx bxs-shopping-bags"></i>Add products</a></li>
                <li><a href="view_product.php"><i class="bx bxs-food-menu"></i>View product</a></li>
                <li><a href="../components/admin_logout.php" onclick="return confirm('logout from this website')"><i class="bx bx-log-out"></i>logout</a></li>
            </ul>
        </div>
    </div>
</div>