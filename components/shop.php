<div class="products">
    <div class="box-container">
        <?php 
            // Fetch up to 6 active products from the database
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE status = ? LIMIT 6");
            $select_products->execute(['active']);

            // If there are products, display each one in a box
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
                <!-- Decorative shape image -->
                <img src="image/shape-19.png" alt="" class="shap">
                <div class="button">
                    <div>
                        <!-- Product name -->
                        <h3 class="name"><?= $fetch_products['name']; ?></h3>
                    </div>
                    <div>
                        <!-- Add to cart, wishlist, and view buttons -->
                        <button type="submit" name="add_to_cart"><i class="bx bx-cart"></i></button>
                        <button type="submit" name="add_to_wishlist"><i class="bx bx-heart"></i></button>
                        <a href="view_page.php?pid=<?= $fetch_products['id'] ?>" class="bx bxs-show"></a>
                    </div>
                </div>
                <!-- Product price -->
                <p class="price">price R<?= $fetch_products['price']; ?></p>
                <!-- Hidden input for product ID -->
                <input type="hidden" name="product_id" value="<?= $fetch_products['id'] ?>">
                <div class="flex-btn">
                    <!-- Buy now button and quantity selector -->
                    <a href="checkout.php?get_id=<?= $fetch_products['id'] ?>" class="btn">buy now</a>
                    <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty box">
                </div>
            </div>
        </form>
        <?php
                }
            } else {
                // If there are no products, show a friendly message
                echo '
                    <div class="empty">
                        <p>no products added yet!</p>
                    </div>
                ';
            }
        ?>
    </div>
</div>