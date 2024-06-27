<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- shared head code -->
    <?php require 'templates/head.php' ?>
    <!-- setup for user controls -->
    <?php 
        require '../models/global_constants.php';
        require '../models/user_model.php';
        require '../models/seller_model.php';
        require '../models/product_model.php';

        if(!isset($_SESSION['member_id'])) {
            require '../models/reset_to_guest.php';
        }

        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Load the products
        $sql = "SELECT * FROM MEMBER_ORDER where order_status='Cart' and member_id=" . $_SESSION['member_id'];

        $result = $conn->query($sql);
        $fetched_data = $result->fetch_all(MYSQLI_ASSOC);
    
        // Closes connection
        $conn->close();
    ?>
</head>
<body>
    <!-- navbar -->
    <?php require 'templates/navbar.php' ?>

    <section id="page-header" class="product-header">
        <h2>Cart</h2>
    </section>

    <?php
        foreach($fetched_data as $cart) {
            $seller_user = new User();
            $seller = new Seller();

            $seller->get_data($cart['seller_id']);
            $seller_user->get_data_by_seller_id($cart['seller_id']);
            require 'templates/cart_card.php';
        }
    ?>

    <footer class="section-p1">
        <div class="col">
            <img class="logo"src="../images/logo.png" alt="">
            <h4>Contact</h4>
            <p><strong>Address:</strong> 8855+F7W, Noblefranca St, Dumaguete, Negros Oriental</p>
            <p><strong>Phone:</strong> +01 2345 6789</p>
            <p><strong>Hours:</strong> 10:00 - 18:00 Mon-Sat</p>
            <div class="follow">
                <h4>Follow us</h4>
                <div class="icon">
                    <i class="fab fa-facebook-f"></i>
                    <i class="fab fa-twitter"></i>
                    <i class="fab fa-instagram"></i>
                    <i class="fab fa-pinterest-p"></i>
                    <i class="fab fa-youtube"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <h4>About</h4>
            <a href="#">About Us</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms and Conditions</a>
            <a href="#">Contact Us</a>
        </div>

        <div class="col">
            <h4>My Account</h4>
            <a href="#">Sign In</a>
            <a href="#">View Cart</a>
            <a href="#">Help</a>
        </div>

        <div class="col install">
            <h4>Install App</h4>
            <p>From Apple Store or Google</p>
            <div class="row">
                <img src="../images/payment/apple.png" alt="">
                <img src="../images/payment/google.png" alt="">
            </div>
            <p>Secured Payment Gateways</p>
            <img src="../images/payment/card.png" alt="">
        </div>

        <div class="copyright">
            <p> Copyright &#169; 2023 Dumaligya. All rights reserved. </p>
        </div>
    </footer>
    <script src="../scripts/open_cart.js"></script>
</body>
</html>