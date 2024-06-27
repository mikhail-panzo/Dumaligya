<?php
    session_start();

    require '../models/global_constants.php';
    require '../models/user_model.php';
    require '../models/member_model.php';
    require '../models/seller_model.php';

    if (isset($_POST['seller_register'])) { // check if the controller is called from seller register
        
        // save the seller data
        $_SESSION['seller_data'] = $_POST;
        
        // save the profile picture
        $seller = new Seller();
        $seller->user_name = $_SESSION['user_data']['user_name'];
        $file = $_FILES['profile_pic'];
        $profile_pic_url = $seller->upload_profile_pic($file, "../");
        $_SESSION['seller_data']['profile_pic_url'] = $profile_pic_url;


        setcookie("register_progress", 4, time() + MINUTE * 10, "/register-user");
        header("Location: ../register-user");

    } else if (isset($_POST['member_register'])) { // check if the controller is called from member register

        // save the member data
        $_SESSION['member_data'] = $_POST;

        // save the profile picture
        $member = new Member();
        $member->user_name = $_SESSION['user_data']['user_name'];
        $file = $_FILES['profile_pic'];
        $profile_pic_url = $member->upload_profile_pic($file, "../");
        $_SESSION['member_data']['profile_pic_url'] = $profile_pic_url;


        // move to the member form
        if(isset($_SESSION['seller_register']) && $_SESSION['seller_register'] == true) {
            // moves to the seller registration page
            setcookie("register_progress", 3, time() + MINUTE * 10, "/register-user");
            header("Location: ../register-user");
        } else {
            setcookie("register_progress", 4, time() + MINUTE * 10, "/register-user");
            header("Location: ../register-user");
        }
    
    } else if (isset($_POST['user_register'])) { // check if the controller is called from user register
        
        // save the user data
        $_SESSION['user_data'] = $_POST;

        // save the government id picture
        $user = new User();
        $user->user_name = $_POST['user_name'];
        $file = $_FILES['government_pic'];
        $government_pic_url = $user->upload_government_pic($file, "../");
        $_SESSION['user_data']['government_pic_url'] = $government_pic_url;


        // move to the member form
        if(isset($_SESSION['member_register']) && $_SESSION['member_register'] == true) {
            // moves to the member registration page
            setcookie("register_progress", 2, time() + MINUTE * 10, "/register-user");
            header("Location: ../register-user");
        } else {
            // moves to the seller registration page
            setcookie("register_progress", 3, time() + MINUTE * 10, "/register-user");
            header("Location: ../register-user");
        }

    } else if (isset($_POST['register_type'])) {  // check if the controller is called from the register page
        
        // loads the pages for registration depending on the register type
        $_SESSION['member_register'] = true;
        $_SESSION['seller_register'] = true;

        switch ($_POST['register_type']) {
            case "Member":
                $_SESSION['seller_register'] = false;
                break;
            case "Seller":
                $_SESSION['member_register'] = false;
                break;
            case "Both":
                break;
        };

        // set a cookie to save register progress for 10 minutes
        // 1 is for progress at user registration
        // 2 is for progress at member registration
        // 3 is for progress at seller registration
        // 4 is for validation

        // moves to the user page
        setcookie('register_progress', 1, time() + MINUTE * 10, '/register-user');
        header("Location: ../register-user");
        
    } else {

        throw new Exception("No post request made");

    }
?>