<?php
    require '../models/global_constants.php';

    session_start();

    if(isset($_POST['add_review'])) { // if the review was added
        if(!isset($_SESSION['member_id'])) {
            require '../models/reset_to_guest.php';
        
        }
        $current_dt = date('Y-m-d H:i:s');

        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
         
        $sql = "INSERT INTO REVIEW (score, review_message, review_date_time, product_id, member_id) VALUES (".$_POST['score'].",'".$_POST['review_message']."', '".$current_dt."', ".$_POST['product_id'].", ".$_SESSION['member_id'].")";

        $conn->query($sql);
 
        $conn->close();
 
        header('Location: /product?product_number=' . $_POST['product_id']);
        exit();
    } else if(isset($_POST['add_review'])) {
        if(!isset($_SESSION['member_id'])) {
            require '../models/reset_to_guest.php';
        
        }
        $current_dt = date('Y-m-d H:i:s');

        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
         
        $sql = "UPDATE REVIEW SET score=".$_POST['score'].", review_message='".$_POST['review_message']."', review_date_time='".$current_dt."' WHERE product_id=".$_POST['product_id']." AND member_id=" . $_SESSION['member_id'];

        $conn->query($sql);
 
        $conn->close();

        header('Location: /product?product_number=' . $_POST['product_id']);
        exit();
    }
?>