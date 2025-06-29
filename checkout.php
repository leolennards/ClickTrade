<?php 
    // Connect to the database
    include 'components/connect.php';

    // Check if the user is logged in, otherwise send them to the login page
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    } else {
        $user_id = '';
        header('location:login.php');
    }

    // If the user submits the order form
    if (isset($_POST['place_order'])) {
        // Get and clean all the billing details from the form
        $name = htmlspecialchars(strip_tags($_POST['name']));
        $number = htmlspecialchars(strip_tags($_POST['number']));
        $email = htmlspecialchars(strip_tags($_POST['email']));
        $street = htmlspecialchars(strip_tags($_POST['street']));
        $suburb = htmlspecialchars(strip_tags($_POST['suburb']));
        $city = htmlspecialchars(strip_tags($_POST['city']));
        $province = htmlspecialchars(strip_tags($_POST['province']));
        $pin = htmlspecialchars(strip_tags($_POST['pin']));

        // Just putting all the address parts together for reference
        $address = "Street: $street, Suburb: $suburb, City: $city, Province: $province, Postal Code: $pin";

        $address_type = htmlspecialchars(strip_tags($_POST['address_type']));
        $method = htmlspecialchars(strip_tags($_POST['method']));

        // Check if the user is buying a single product or the whole cart
        $verify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $verify_cart->execute([$user_id]);

        if (isset($_GET['get_id'])) {
            // If it's a single product checkout (Buy Now)
            $qty = isset($_GET['qty']) ? (int)$_GET['qty'] : 1;
            $get_product = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
            $get_product->execute([$_GET['get_id']]);
            if ($get_product->rowCount() > 0) {
                while($fetch_p = $get_product->fetch(PDO::FETCH_ASSOC)) {
                    $seller_id = $fetch_p['seller_id'];
                    // Save the order for this product
                    $insert_order = $conn->prepare("INSERT INTO `orders`
                        (id, user_id, seller_id, name, number, email, address_type, street, suburb, city, province, pin, method, product_id, price, qty)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $insert_order->execute([
                        uniqid(), $user_id, $seller_id, $name, $number, $email, $address_type,
                        $street, $suburb, $city, $province, $pin,
                        $method, $fetch_p['id'], $fetch_p['price'], $qty // <-- use $qty here
                    ]);

                    // After placing the order, send the user to the order page
                    header('location:order.php');
                }
            } else {
                // If something goes wrong, show a warning
                $warning_msg[] = 'something went wrong';
            }
        } elseif ($verify_cart->rowCount() > 0) {
            // If the user is checking out their whole cart
            while($f_cart = $verify_cart->fetch(PDO::FETCH_ASSOC)) {
                $s_products = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
                $s_products->execute([$f_cart['product_id']]);
                $f_product = $s_products->fetch(PDO::FETCH_ASSOC);

                $seller_id = $f_product['seller_id'];

                // Save the order for each item in the cart
                $insert_order = $conn->prepare("INSERT INTO `orders`
                    (id, user_id, seller_id, name, number, email, address_type, street, suburb, city, province, pin, method, product_id, price, qty)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insert_order->execute([
                    uniqid(), $user_id, $seller_id, $name, $number, $email, $address_type,
                    $street, $suburb, $city, $province, $pin,
                    $method, $f_cart['product_id'], $f_cart['price'], $f_cart['qty']
                ]);
            }
            if ($insert_order) {
                // After placing the order, clear the cart and go to the order page
                $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
                $delete_cart->execute([$user_id]);
                header('location:order.php');
            }
        } else {
            // If there is nothing to order, show a warning
            $warning_msg[] = 'something went wrong';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClickTrade - checkout page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time (); ?>">

    <!-- Boxicons CDN -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Font Awesome cdn link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
    <!-- User header/navigation bar -->
    <?php include 'components/user_header.php'; ?>
    <div class="checkout">
        <div class="heading">
            <h1>checkout summary</h1>
            <img src="image/separator-img.png" alt="">
        </div>
        <div class="row">
            <!-- Billing details form -->
            <form action="" method="post" class="register">
                <input type="hidden" name="p_id" value="<?= isset($get_id) ? $get_id : ''; ?>">
                <h3>billing details</h3>
                <div class="flex">
                    <div class="box">
                        <div class="input-field">
                            <p>your name <span>*</span> </p>
                            <input type="text" name="name" required maxlength="50" placeholder="Recipient Name" class="input">
                        </div>
                        <div class="input-field">
                            <p>your number <span>*</span> </p>
                            <input type="number" name="number" required maxlength="10" placeholder="Recipient Mobile Number" class="input">
                        </div>
                        <div class="input-field">
                            <p>your email <span>*</span> </p>
                            <input type="email" name="email" required maxlength="50" placeholder="Recipient Email Address" class="input">
                        </div>
                        <div class="input-field">
                            <p>address type <span>*</span> </p>
                            <select name="address_type" class="input">
                                <option value="home">Home</option>
                                <option value="office">Office</option>
                            </select>
                        </div>
                    </div>
                    <div class="box">
                        <div class="input-field">
                            <p>street address <span>*</span> </p>
                            <input type="text" name="street" required maxlength="50" placeholder="e.g 21 Hartbees Street" class="input">
                        </div>
                        <div class="input-field">
                            <p>Suburb <span>*</span> </p>
                            <input type="text" name="suburb" required maxlength="50" placeholder="e.g Parow" class="input">
                        </div>
                        <div class="input-field">
                            <p>city <span>*</span> </p>
                            <input type="text" name="city" required maxlength="50" placeholder="e.g Cape Town" class="input">
                        </div>
                        <div class="input-field">
                            <p>Select Province <span>*</span> </p>
                            <select name="province" class="input">
                                <option value="eastern cape">Eastern Cape</option>
                                <option value="free state">Free State</option>
                                <option value="gauteng">Gauteng</option>
                                <option value="kwazulu-natal">KwaZulu-Natal</option>
                                <option value="limpopo">Limpopo</option>
                                <option value="mpumalanga">Mpumalanga</option>
                                <option value="northern cape">Northern Cape</option>
                                <option value="north west">North West</option>
                                <option value="western cape">Western Cape</option>
                            </select>
                        </div>
                        <div class="input-field">
                            <p>postal code <span>*</span> </p>
                            <input type="text" name="pin" required maxlength="4" placeholder="e.g 7500" class="input">
                        </div>
                        <div class="input-field">
                            <p>Payment Method <span>*</span> </p>
                            <select name="method" class="input" required>
                                <option value="cash">Cash</option>
                                <option value="credit/debit">Credit / Debit Card</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" name="place_order" class="btn">place order</button>
            </form>
            <div class="summary">
                <h3>my bag</h3>
                <div class="box-container">
                    <?php
                        $grand_total = 0;
                        if (isset($_GET['get_id'])) {

                            $qty = isset($_GET['qty']) ? (int)$_GET['qty'] : 1;
                            $select_get = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                            $select_get->execute([$_GET['get_id']]);

                            while($fetch_get = $select_get->fetch(PDO::FETCH_ASSOC)) {
                                $sub_total = $fetch_get['price'] * $qty;
                                $grand_total += $sub_total;
                    ?>
                    <div class="flex">
                        <img src="uploaded_files/<?= $fetch_get['image']; ?>" class="image" alt="">
                        <div>
                            <h3 class="name"><?= $fetch_get['name']; ?></h3>
                            <p class="price"><?= $fetch_get['price']; ?> X <?= $qty; ?></p>
                        </div>
                    </div>
                    <?php 
                            }
                        }else {
                            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                            $select_cart->execute([$user_id]);

                            if ($select_cart->rowCount() > 0) {
                                while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                                    $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                                    $select_products->execute([$fetch_cart['product_id']]);
                                    $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                                    $sub_total = ($fetch_cart['qty'] * $fetch_products['price']);
                                    $grand_total += $sub_total;
                    ?>
                    <div class="flex">
                        <img src="uploaded_files/<?= $fetch_products['image']; ?>" class="image" alt="">
                        <div>
                            <h3 class="name"><?= $fetch_products['name']; ?></h3>
                            <p class="price"><?= $fetch_products['price']; ?> X <?= $fetch_cart['qty']; ?></p>
                        </div>
                    </div>
                    <?php
                                }
                            }else {
                                echo '<p class="empty">your cart is empty</p>';
                            }
                        }
                    ?>

                </div>
                <div class="grand-total">
                    <span>total amount payable:</span>
                    <p>R<?= $grand_total; ?></p>

                </div>
            </div>
        </div>
    </div>
    


     <?php include 'components/footer.php'; ?>

    <!-- sweetalert cdn link-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- custom js link-->
    <script src="js/user_script.js"></script>

    <?php include 'components/alert.php'; ?>
</body>
</html>

<input type="hidden" name="p_id" value="<?= $get_id; ?>">