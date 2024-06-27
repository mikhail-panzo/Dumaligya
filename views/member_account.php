<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- shared head code -->
    <?php require 'templates/head.php' ?>
    <!-- load product -->
    <?php
        require '../models/global_constants.php';
        require '../models/user_model.php';
        require '../models/member_model.php';
        
        // check if there is user id
        if(!isset($_SESSION['user_id'])) {
            require '../models/reset_to_guest.php';
        }

        $user = new User();
        $member = new Member();
        $success = $user->get_data($_SESSION['user_id']);
        if($success)
            $success = $member->get_data($user->member_id);

        if($success == false) {
            require '../models/reset_to_guest.php';
        }
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

        .user-actions .section-title{
            font-size: 2rem;
            margin-bottom: 1rem;
            text-align: center;
        }

    </style>
</head>

<body>

<!-- navbar -->
<?php require 'templates/navbar.php' ?>

<!-- member information -->
<section id="product-details" class="section-p1">
    <div class="single-pro-img">
        <?php
            echo "<img class='single-pro-img' src=\"../" .  $member->profile_pic_url . "\" width=\"100%\" id=\"mainImg\">";
        ?>
    </div>

    <div class="single-pro-details">
        <h1><?php echo $user->first_name . " " . $user->last_name ?></h1>

        <p>Username: <span><?php echo $user->user_name ?></span></p>
        <p>Email Address: <span><?php echo $member->user_address ?></span></p>

        <p class="short-desc">Bio: <?php echo $member->bio ?></p>
    </div>
</section>

<div class="divider">
    <i class="fa-solid fa-user"></i>
</div>


<section class="user-actions section-p1">
    <h3 class="section-title">User Actions</h3>
    <?php echo "<button class=\"btn\"onclick=\"rename(".$_SESSION['user_id'].")\"><i class=\"fa-solid fa-pen\"></i> Edit Information</button>" ?><br><br>

    <!-- user actions -->
    <a href="../controllers/account_switch_controller.php"><button class="btn"> <i class="fa-solid fa-shuffle"></i> Switch to Seller</button><br><br>

    <a href="cart"><button class="btn"><i class="fa-solid fa-cart-shopping"></i> Cart</button></a><br><br>
    <a href="orders"><button class="btn"><i class="fa-solid fa-truck-fast"></i> Orders</button><br><br>
    <a href="../controllers/logout_controller.php"><button class="btn" ><i class="fa-solid fa-door-open"></i> Logout</button></a><br><br>
</section>

<!-- popup form -->
<div class="popup-form form" id="popup">
    <form action="../../controllers/account_controller.php" method="POST" enctype="multipart/form-data">
        <h3>Edit Account details</h3><br>

        <label for="firstname">First Name:</label>
        <?php
            echo "<input class=\"form-input-text\" type=\"text\" id=\"firstname\" name=\"first_name\" value='" . $user->first_name . "' required>";
        ?>
        <br>

        <label for="lastname">Last Name:</label>
        <?php
            echo "<input class=\"form-input-text\" type=\"text\" id=\"lastname\" name=\"last_name\" value='" . $user->last_name . "' required>";
        ?>
        <br>

        <label for="email">Email:</label>
        <?php
            echo "<input class=\"form-input-text\" type=\"email\" id=\"email\" name=\"email\" value='" . $user->email . "' required>";
        ?>
        <br>

        <label for="contactnumber">Contact Number:</label>
        <?php
            echo "<input class=\"form-input-text\" type=\"tel\" id=\"contactnumber\" name=\"contact_number\" pattern=\"^0\d{10}$\" value='" . $user->contact_number . "' required>";
        ?>
        <br>

        <label for="birthdate">Birth Date:</label>
        <?php
            echo "<input class=\"form-input-date\" type=\"date\" id=\"birthdate\" name=\"birth_date\" value='" . $user->birth_date . "' required>";
        ?>
        <br>

        <label for="sex">Sex:</label>
        <?php
            echo "<select class=\"form-input-sex\" id=\"sex\" name=\"sex\" value='" . $user->sex . "' required>";
        ?>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select><br>

        <label for="user_address">Address:</label>
        <textarea class="form-input-text" id="user_address" name="user_address" maxlength="255" required><?php echo $member->user_address ?></textarea><br>

        <label for="bio">Bio:</label>
        <textarea class="form-input-text" id="bio" name="bio" maxlength="255"><?php echo $member->bio ?></textarea><br>

        <label for="profile_pic">Profile Picture:</label>
        <input type="file" id="profile_pic" name="profile_pic" accept="image/*"><br><br>
            <button class="btn" type="submit" name="member_reedit">Save</button>
            <button class="btn" type="submit" name="cancel">Cancel</button>
    </form>
</div>

<script src="../scripts/popup_form.js"></script>
</body>
</html>