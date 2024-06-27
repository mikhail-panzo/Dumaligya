<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- shared head code -->
    <?php require '../templates/head.php' ?>
</head>
<body>
    <?php
        require '../../models/global_constants.php';
        require '../../models/seller_model.php';
        require '../../models/member_model.php';
        require '../../models/user_model.php';
        require '../../controllers/model_controllers/user_model_controller.php';
        require '../../controllers/model_controllers/member_model_controller.php';
        require '../../controllers/model_controllers/seller_model_controller.php';

        $register_success = true;

        // see if this validate page has been requested while the main register sessions are active
        if(isset($_SESSION['member_register']) && isset($_SESSION['seller_register'])) {
            
            $user_type = "Guest";
            // insert into user table
            
            $user_id = insert_user($_SESSION['user_data']);
            $user_name = $_SESSION['user_data']['user_name'];

            if($user_id != 0) {
                $success = true;
                
                if(isset($_SESSION['member_register']) && isset($_SESSION['member_data'])) { // insert into member table

                    $member_id = insert_member($_SESSION['member_data']);
                
                    if($member_id != 0) {
                        $success = (bool) assign_member_to_user($user_id, $member_id);
                    } else {
                        $success = false;
                    }

                    $user_type = "Member";
                }

                if($success == true && isset($_SESSION['seller_register']) && isset($_SESSION['seller_data'])) { // insert into seller table
                    $seller_id = insert_seller($_SESSION['seller_data']);

                    if($seller_id != 0) {
                        $success = (bool) assign_seller_to_user($user_id, $seller_id);
                    } else {
                        $success = false;
                    }

                    $user_type = "Seller";
                }

                if ($success == true) {
                    echo "<h2 class=\"success\"> Registration successful </h2>";
                } else {
                    echo "<h2 class=\"success\"> Registration unsuccessful. Please try again. </h2>";
                }
            } else {
                $register_success = false;
                echo "<h2 class=\"success\"> Registration unsuccessful. Please try again. </h2>";
            }

            // clear all sessions and remove the register progress
            session_unset();
            setcookie("register_progress", 0, time() - 1, "/register-user");

            if($register_success) {
                // set the user type and id
                $_SESSION['user_type'] = $user_type;
                $_SESSION['user_id'] = $user_id;

                $user = new User();
                $_SESSION['user_name'] = $user_name;

                if($user_type == "Seller") {
                    setcookie("user_type", "seller", 0, "/");
                    $user->get_seller_id($user_id);
                    $_SESSION['seller_id'] = $user->seller_id;
                } else {
                    setcookie("user_type", "member", 0, "/");
                    $user->get_member_id($user_id);
                    $_SESSION['member_id'] = $user->member_id;
                }
            }
        }
    ?>

    <a href="/"><button class="btn">Return</button></a>
</body>