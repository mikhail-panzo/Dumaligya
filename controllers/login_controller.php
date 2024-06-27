<?php
    session_start();
    
    require '../models/global_constants.php';
    require '../models/user_model.php';
    require '../models/member_model.php';
    require '../models/seller_model.php';

    // Create connection
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
               
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
     
    // Gets the user_name
    $sql = "SELECT * FROM USER WHERE user_name='" . $_POST['user_name'] ."'";
     
    $result = $conn->query($sql);
    $fetched_data = $result->fetch_assoc();

    if(empty($fetched_data)) {
        $conn->close();
        $_SESSION['login_fail_message'] = "Username does not exist";
        header('Location: /login');
        exit();
    } else {
        if($fetched_data['user_password'] != $_POST['user_password']) {
            $conn->close();
            $_SESSION['login_fail_message'] = "Password does not match";
            header('Location: /login');
            exit();
        } else {
            // check if member account or seller account exist
            if((isset($_POST['seller_login']) && empty($fetched_data['seller_id']))) {
                $conn->close();
                $_SESSION['login_fail_message'] = "You do not have a seller account";
                header('Location: /login');
                exit();
            } else if ((isset($_POST['member_login']) && empty($fetched_data['member_id']))) {
                $conn->close();
                $_SESSION['login_fail_message'] = "You do not have a member account";
                header('Location: /login');
                exit();
            } else {
                session_unset();
                
                // password and username is matched
                $user_id = $fetched_data['id'];
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $fetched_data['user_name'];
                $user = new User();

                if(isset($_POST['seller_login'])) {
                    $_SESSION['user_type'] = "Seller";
                    setcookie("user_type", "seller", 0, "/");
                    $user->get_seller_id($user_id);
                    $_SESSION['seller_id'] = $user->seller_id;
                }

                if(isset($_POST['member_login'])) {
                    $_SESSION['user_type'] = "Member";
                    setcookie("user_type", "member", 0, "/");
                    $user->get_member_id($user_id);
                    $_SESSION['member_id'] = $user->member_id;
                }

                header('Location: /');
            }
        }
    }
    
    $conn->close();
?>