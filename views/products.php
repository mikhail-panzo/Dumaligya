<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- shared head code -->
    <?php require 'templates/head.php' ?>
    <!-- load products -->
    <?php
        require '../models/global_constants.php';
        require '../controllers/model_controllers/product_model_controller.php';

        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Load the products
        $sql = "SELECT * FROM PRODUCT where quantity!=0 ORDER BY product_name";

        $result = $conn->query($sql);
        $fetched_data = $result->fetch_all(MYSQLI_ASSOC);

        // Closes connection
        $conn->close();

        //remove self for checking products
        if(isset($_SESSION['user_type']) && $_SESSION['user_type'] != 'Guest') {
            if(!isset($_SESSION['user_id'])) {
                require '../models/reset_to_guest.php';
            } else {
                remove_self_products($fetched_data, $_SESSION['user_id']);
            }
        }
         
    ?>
</head>
<body>
    <!-- navbar -->
    <?php require 'templates/navbar.php' ?>

    <section id="page-header" class="product-header">
        <h2>New Items Everyday</h2>
        <p>Unlock your trading potential and seize profitable possibilities today.</p> 
    </section>

    <?php
        foreach($fetched_data as $product) {
            require 'templates/product_card.php';
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
    <script src="../scripts/open_product.js"></script>
</body>
</html>