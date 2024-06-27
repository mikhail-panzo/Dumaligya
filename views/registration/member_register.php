<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- shared head code -->
    <?php require '../templates/head.php' ?>
    <style>        
        .error-message {
            display: none;
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php
        // navbar
        require '../templates/navbar.php';
    ?>

    <section class="register section--lg">
        <div class="register-member-container container grid">
            <div class="member-register">
                <h3 class="section-title">MEMBER REGISTRATION</h3>

                <form id="form" action="../../controllers/register_controller.php" method="POST" enctype="multipart/form-data">
                    <label for="profile_pic">Profile Picture:</label>
                    <input type="file" id="profile_pic" name="profile_pic" accept="image/*" required><br><br>
        
                    <input type="text" class="form-input-text" placeholder="Address" id="user_address" name="user_address" maxlength="255" required></input><br><br>
        
                    <textarea class="textarea" type="text" placeholder="Bio" id="bio" name="bio" maxlength="255"></textarea><br><br>
            
                    <button type="submit" class="btn" name="member_register"> 
                        <?php
                            if(isset($_SESSION['seller_register']) && $_SESSION['seller_register'] == true) {
                                echo "NEXT";
                            } else {
                                echo "REGISTER";
                            }
                        ?>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <script src="../../scripts/unload_warning.js"></script>
</body>