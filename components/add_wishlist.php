<?php
    // This handles adding products to the user's wishlist

    // Check if the "add to wishlist" button was clicked
    if (isset($_POST['add_to_wishlist'])) {
        // Make sure the user is logged in
        if ($user_id != '') {

            // Generate a unique ID for this wishlist entry
            $id = unique_id();
            $product_id = $_POST['product_id'];

            // Check if this product is already in the user's wishlist
            $verify_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ? AND product_id = ?");
            $verify_wishlist->execute([$user_id, $product_id]);

            // (Optional) Check if this product is already in the user's cart
            $cart_num = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?");
            $cart_num->execute([$user_id, $product_id]);

            if ($verify_wishlist->rowCount() > 0) {
                // Warn if the product is already in the wishlist
                $warning_msg[] = 'product has already been added to your wishlist!';
            } else {
                // Get the price of the product from the database
                $select_price = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
                $select_price->execute([$product_id]);
                $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

                // Add the product to the wishlist
                $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(id, user_id, product_id, price) VALUES(?,?,?,?)");
                $insert_wishlist->execute([$id, $user_id, $product_id, $fetch_price['price']]);
                $success_msg[] = 'product has been added to your wishlist!';
            }
        } else {
            // If the user isn't logged in, ask them to log in first
            $warning_msg[] = 'please login first!';
        }
    }
?>