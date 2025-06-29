<?php
    // Include the database connection (not strictly needed for logout, but included for consistency)
    include 'connect.php';

    // Remove the user and seller cookies by setting them to expire in the past
    setcookie('user_id', '', time() - 1, '/');
    setcookie('seller_id', '', time() - 1, '/');

    // Redirect the user to the admin login page after logging out
    header('location: ../admin panel/login.php');
?>