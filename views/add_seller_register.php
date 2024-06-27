<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- shared head code -->
    <?php require 'templates/head.php' ?>
    <!-- php code for registration -->
    <?php
        require '../models/global_constants.php';
        require '../models/seller_model.php';

        if(isset($_POST['seller_register'])) {
            if(isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
                $user_name = $_SESSION['user_name'];
            } else {
                require '../models/reset_to_guest.php';
            }
            
            $picture = $_FILES['profile_pic'];
            $seller = new Seller();
            $seller->user_name = $_SESSION['user_name'];
            $seller->pickup_location = $_POST['pickup_location'];
            $seller->seller_description = $_POST['seller_description'];
            $seller->seller_schedule = $_POST['seller_schedule'];
            $seller->upload_profile_pic($picture, "../");
            $seller_id = $seller->insert_data();
            
            if($seller_id != 0) {
                // Create connection
                $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "UPDATE USER SET seller_id='" . $seller_id . "' WHERE id=" . $user_id;
                
                if($conn->query($sql)) {
                    setcookie("add_register", 1, time() - 1, "/seller-register");
                    session_unset();

                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['user_name'] = $user_name;
                    $_SESSION['user_type'] = "Seller";
                    setcookie("user_type", "seller", 0, "/");
                    $_SESSION['seller_id'] = $seller_id;

                    header("Location: /");
                    exit();
                }
            }
        }
    ?>
    <style>
        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <?php
        // navbar
        require 'templates/navbar.php';

    ?>

    <h2>SELLER REGISTRATION</h2>
    <?php
        if(isset($_POST['seller_register'])) {
            echo "<p class='error-message'>Seller registration failed. Please try again.</p>";
        }
    ?>
    <form id="form" action="views/add_seller_register.php" method="POST" enctype="multipart/form-data">
        <label for="profile_pic">Profile Picture:</label>
        <input type="file" id="profile_pic" name="profile_pic" accept="image/*" required><br><br>

        <label for="pickup_location">Default Pickup Location:</label>
        <textarea id="pickup_location" name="pickup_location" maxlength="255" required></textarea><br><br>

        <label for="seller_description">Seller Description:</label>
        <textarea id="seller_description" name="seller_description" maxlength="1000"></textarea><br><br>

        <label for="seller_schedule">Seller Schedule:</label>
        <textarea id="seller_schedule" name="seller_schedule" maxlength="255"></textarea><br><br>

        <button type="submit" name="seller_register">REGISTER</button>
    </form>
    <script src="../scripts/unload_warning.js"></script>
</body