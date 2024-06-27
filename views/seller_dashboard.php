<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- shared head code -->
    <?php require 'templates/head.php' ?>
    <!-- setup for user controls -->
    <?php 
        require '../models/global_constants.php';
        require '../controllers/model_controllers/user_model_controller.php';

        if (!isset($_SESSION['user_type'])) { // resets the user if no logged in user is found
            include '../models/reset_to_guest.php';
        }

        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Load the products
        $sql = "SELECT * FROM PRODUCT where seller_id=" . $_SESSION['seller_id'];

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
        <h2> Welcome, <?php echo get_user_name($_SESSION['user_id']) ?>
        </h2>
    </section>


    <section class="seller section-p1 grid" >
        <h2 class="section-name">SELLER ACTIONS</h2><br>
        <a href="add-product"><button class="btn">Add Product to Sell</button></a>
        <a href="categories"><button class="btn">Edit Categories</button></a>

        <div class="divider">
            <i class="fa-solid fa-boxes-stacked"></i>
        </div>
    </section>

    <?php
        require '../controllers/model_controllers/product_model_controller.php';

        foreach($fetched_data as $product) {
            require 'templates/product_card.php';
        }
    ?>

    <?php include 'templates/footer.php' ?>
    <script src="../scripts/seller_open_product.js"></script>
</body>
</html>