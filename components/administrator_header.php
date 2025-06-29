<header>
    <div class="logo">
        <!-- ClickTrade logo in the header -->
        <img src="../image/ClickTrade-Logo.svg" width="150" alt="ClickTrade Logo">
    </div>
    <div class="right">
        <!-- User icon and menu toggle button -->
        <div class="profile-icon" id="user-btn">
            <i class="fas fa-user"></i>
        </div>
        <div class="toggle-btn"><i class="bx bx-menu"></i></div>
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
                <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');" class="btn">logout</a>
            </div>
        </div>
        <?php } ?>
    </div>
</header>

<div class="sidebar-container">
    <div class="sidebar">
        <?php 
            if (isset($seller_id)) { 
                // Fetch the seller's profile for the sidebar
                $select_profile = $conn->prepare("SELECT * FROM `sellers` WHERE id = ?");
                $select_profile->execute([$seller_id]);
                if ($select_profile->rowCount() > 0) {
                    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="profile" style="text-align:center; margin-bottom:1rem;">
            <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" class="logo-img" width="100" alt="Profile Image">
            <p style="margin:0.5rem 0;"><?= $fetch_profile['name']; ?></p>
        </div>
        <?php 
                }
            } 
        ?>
        <h5>menu</h5>
        <div class="navbar">
            <ul>
                <!-- Sidebar navigation links -->
                <li><a href="administrator_access.php"><i class="bx bxs-home-smile"></i>Admin Dashboard</a></li>
                <li><a href="admin_manage_users.php"><i class="bx bxs-user-detail"></i>Accounts</a></li>
                <li><a href="../components/admin_logout.php" onclick="return confirm('logout from this website')"><i class="bx bx-log-out"></i>logout</a></li>
            </ul>
        </div>
    </div>
</div>