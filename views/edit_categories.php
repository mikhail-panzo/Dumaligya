<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- shared head code -->
    <?php require 'templates/head.php' ?>
    <!-- collect categories -->
    <?php
        require '../models/global_constants.php';

        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Load the categories
        $sql = "SELECT id, category_name FROM CATEGORY WHERE seller_id=" . $_SESSION['seller_id'];
        
        $result = $conn->query($sql);
        $fetched_data = $result->fetch_all(MYSQLI_ASSOC);

        // Closes connection
        $conn->close();
    ?>
    <style>
        .popup-form {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #f1f1f1;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
            z-index: 9999;
        }

        .delete_form {
            display: inline;
        }
        
        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <!-- navbar -->
    <?php require 'templates/navbar.php' ?>

    <section id="page-header" class="product-header">
        <h2> Categories </h2>
    </section>

    <!-- form for adding category names -->
   
    <section class="category grid section-p1">
     <h2>Create A New Category</h2>
        <form id="form" action="../../controllers/categories_controller.php" method="POST">
            <input class="form-input-text" placeholder="Category Name" type="text" id="categoryname" name="category_name" required>
            <button class="btn" type="submit" name="category_add">Add</button>
        </form>

        <?php
            if(isset($_SESSION['no_category_fail_message'])) {
                echo "<p class=\"error-message\">" . $_SESSION['no_category_fail_message'] . "</p>";
                unset($_SESSION['no_category_fail_message']);
            }
        ?>

        <div class="divider">
            <i class="fa-solid fa-clipboard"></i>
        </div>

        <h2>Existing Categories</h2>
        <ul>
            <?php
                foreach($fetched_data as $category) { // get each category row from fetched data
                    require 'templates/category_card.php';
                }
            ?>
        </ul>
    </section>
    

    <!-- popup form -->
    <div class="popup-form" id="popup">
        <form action="../../controllers/categories_controller.php" method="POST">
            <label>Rename Category</label><br>
            <input type="hidden" id="id_input" name="category_id">
            <input type="text" id="name" name="category_name" required><br><br>
            <button type="submit" name="rename">Save</button>
        </form>
    </div>

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
    <script src="../scripts/popup_form.js"></script>
</body>
</html>