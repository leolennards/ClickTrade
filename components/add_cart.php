<?php
    // This handles adding a product to the user's cart

    // Check if the "add to cart" button was clicked
    if (isset($_POST['add_to_cart'])) {
        // Make sure the user is logged in
        if ($user_id != '') {

            // Generate a unique ID for this cart entry
            $id = unique_id();
            $product_id = $_POST['product_id'];

            // Get the quantity the user wants to add
            $qty = (int)$_POST['qty'];

            // Check if this product is already in the user's cart
            $verify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?");
            $verify_cart->execute([$user_id, $product_id]);

            // Check how many items are already in the user's cart
            $max_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $max_cart_items->execute([$user_id]);

            if ($verify_cart->rowCount() > 0) {
                // Warn if the product is already in the cart
                $warning_msg[] = 'product has already been added to your cart!';
            } elseif ($max_cart_items->rowCount() > 20) {
                // Warn if the cart is full (more than 20 items)
                $warning_msg[] = 'your cart is full!';
            } else {
                // Get the price of the product from the database
                $select_price = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
                $select_price->execute([$product_id]);
                $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

                // Add the product to the cart
                $insert_cart = $conn->prepare("INSERT INTO `cart`(id, user_id, product_id, price, qty) VALUES(?,?,?,?,?)");
                $insert_cart->execute([$id, $user_id, $product_id, $fetch_price['price'], $qty]);
                $success_msg[] = 'product has been added to your cart!';
            }
        } else {
            // If the user isn't logged in, ask them to log in first
            $warning_msg[] = 'please login first!';
        }
    }
?>