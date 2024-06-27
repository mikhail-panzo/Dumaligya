<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- shared head code -->
    <?php require '../templates/head.php' ?>
    <style>        
        .error-message {
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
    <?php
        if(isset($_SESSION['login_fail_message'])) {
            echo "<p class=\"error-message\">" . $_SESSION['login_fail_message'] . "</p>";
            unset($_SESSION['login_fail_message']);
        }
    ?>
    
    <section class="login section--lg">
        <div class="login-container center">
            <div class="login">
                <h3 class="section-title">Log In</h3>

                <form id="form" class="form grid" action="../../controllers/login_controller.php" method="POST">
                    
                    <label for="username">Username: </label>
                    <input type="text" id="username" placeholder="Username" class="form-input-text" name="user_name" required>

                    <label for="password">Password: </label>
                    <input type="password" id="password" placeholder="Password" class="form-input-text" name="user_password" required>
                    
                    <div class="form-btn">
                        <button type="submit" class="btn" name="member_login">Log In as Member</button>
                        <button type="submit" class="btn" name="seller_login">Log In as Seller</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script src="../../scripts/unload_warning.js"></script>
</body>