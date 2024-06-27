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
        }
    </style>
</head>
<body>
    <?php
        // navbar
        require '../templates/navbar.php';
    ?>

    <section class="register section--lg">
        <div class="register-seller-container container grid">
            <div class="seller-register">
                <h3 class="section-title">USER REGISTRATION</h3>

                <form id="form" action="../../controllers/register_controller.php" method="POST" onsubmit="return validateForm();" enctype="multipart/form-data">
                    <label for="username">Username: </label><br>
                    <input class="form-input-text" placeholder="Username" type="text" id="username" name="user_name" maxlength="100" required><br><br>

                    <label for="firstname">First Name: </label><br>
                    <input class="form-input-text" placeholder="First Name" type="text" id="firstname" name="first_name" maxlength="100" required><br><br>

                    <label for="lastname">Last Name: </label><br>
                    <input class="form-input-text" placeholder="Last Name" type="text" id="lastname" name="last_name" maxlength="100" required><br><br>

                    <label for="email">Email Address: </label><br>
                    <input class="form-input-text" placeholder="Email" type="email" id="email" name="email" maxlength="100" required><br><br>

                    <label for="contactnumber">Contact Number: </label><br>
                    <input class="form-input-text" placeholder="Contact Number" type="tel" id="contactnumber" name="contact_number" pattern="^0\d{10}$" placeholder="0987653211" required><br><br>
            
                    <label for="password" id="password_error" class="error-message">password do not match</label><br>
                    <label for="password">Password: </label><br>
                    <input class="form-input-text" placeholder="Password" type="password" id="password" name="user_password" maxlength="100" required><br><br>

                    <label for="confirmpassword">Confirm Password: </label><br>
                    <input class="form-input-text" placeholder="Confirm Password" type="password" id="confirmpassword" maxlength="100" required><br><br>

                    <label for="birthdate">Birthdate: </label><br>
                    <input class="form-input-date" placeholder="Birth Date" type="date" id="birthdate" name="birth_date" required><br><br>
                    
                    <label for="sex">Sex: </label><br>
                    <select class="form-input-sex " id="sex" name="sex" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select><br><br>
                    
                    <label for="governmentpic">Government ID Picture: </label><br>
                    <input type="file" id="governmentpic" name="government_pic" accept="image/*" required><br><br>
                
                    <button class="btn" type="submit" name="user_register">Next</button>
                </form>
            </div>
        </div>
    </section>
    
    <script>

        function validateForm() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmpassword").value;

            if (password !== confirmPassword) {
                document.getElementById("password_error").style.display = "block";
                return false;
            }
            return true;
        }
    </script>
    <script src="../../scripts/unload_warning.js"></script>
</body>