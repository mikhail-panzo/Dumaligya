<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- shared head code -->
    <?php require '../templates/head.php' ?>
</head>
<body>
    <!-- navbar -->
    <?php require '../templates/navbar.php' ?>

    <!-- selection -->

    <section class="register section--lg">
    <h2 class="section-title">Registration</h2>
        <div class="register-container container grid">
            <div class="register-member">
                <h3 class="section-title">Member</h3>
                <p>Buy products from sellers</p>
                <form method="POST" action="../../controllers/register_controller.php">
                    <button type="submit" class="btn" name="register_type" value="Member">Register as Member</button>
                </form>
            </div>

            <div class="register-both">
                <h3 class="section-title">Both Member and Seller</h3>
                <p>Buy and sell as both member and seller</p>
                <form method="POST" action="../../controllers/register_controller.php">
                    <button type="submit" class="btn" name="register_type" value="Both">Register as Both</button>   
                </form>
                
            </div>

            <div class="register-seller">
                <h3 class="section-title">Seller</h3>
                <p>Sell items to members</p>
                <form method="POST" action="../../controllers/register_controller.php">
                    <button type="submit" class="btn" name="register_type" value="Seller">Register as Seller</button>
                </form>
            </div>
        </div>
    </section>
</body>