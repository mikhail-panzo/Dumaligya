<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- shared head code -->
    <?php require '../templates/head.php' ?>
</head>
<body>
    <?php
        // navbar
        require '../templates/navbar.php';

    ?>

    <section class="register section--lg">
        <div class="register-seller-container container grid">
            <div class="seller-register">
                <h3 class="section-title">SELLER REGISTRATION</h3>

                <form id="form" action="../../controllers/register_controller.php" method="POST" enctype="multipart/form-data">
                    <label for="profile_pic">Profile Picture:</label>
                    <input type="file" id="profile_pic" name="profile_pic" accept="image/*" required><br><br>
            
                    <input class="form-input-text" placeholder="Default Pickup Location" id="pickup_location" name="pickup_location" maxlength="255" required></input><br><br>
                    
                    <textarea class="textarea" placeholder="Seller Description" id="seller_description" name="seller_description" maxlength="1000"></textarea><br><br>
            
                    <input class="form-input-text" placeholder="Seller Schedule" id="seller_schedule" name="seller_schedule" maxlength="255"></input><br><br>
            
                    <button class="btn" type="submit" name="seller_register">REGISTER</button>
                </form>
            </div>
        </div>
    </section>

    <script src="../../scripts/unload_warning.js"></script>
</body>