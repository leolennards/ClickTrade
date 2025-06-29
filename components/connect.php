<?php 
    // Set up the database connection details
    $host = 'sql202.infinityfree.com';
    $dbname = 'if0_39173833_clicktrade';
    $username = "if0_39173833";
    $password = "Isaiah60v22";

    try {
        // Create a new PDO connection to the database
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("DB Connection failed: " . $e->getMessage());
    }

    // Function to generate a unique 20-character ID (used for things like cart, wishlist, etc.)
    function unique_id() {
        $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $charLength = strlen($chars);
        $randomString = "";
        for ($i = 0; $i < 20; $i++) {
            $randomString .= $chars[mt_rand(0, $charLength - 1)];
        }
        return $randomString;
    }
?>