<?php
    require '../models/global_constants.php';

    session_start();

    if(!isset($_SESSION['user_type']) || !isset($_SESSION['user_id'])) {
        require '../models/reset_to_guest.php';
    }

    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];

    // Create connection
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if($_SESSION['user_type'] == 'Seller') {

    } else if($_SESSION['user_type'] == 'Member') {
        // check seller id to see if there is seller account
        $sql = "SELECT seller_id FROM USER WHERE id=" . $user_id;
        $result = $conn->query($sql);
        $fetched_data = $result->fetch_assoc();
        $seller_id = $fetched_data['seller_id'];

        if(empty($seller_id)) {
            setcookie("add_register", 1, time() + MINUTE, "/seller-register");

            header("Location: /seller-register");
        } else {
            session_unset();

            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $user_name;
            $_SESSION['user_type'] = "Seller";
            setcookie("user_type", "seller", 0, "/");
            $_SESSION['seller_id'] = $seller_id;

            header("Location: /");
        }
    } else {
        require '../models/reset_to_guest.php';
    }
?>