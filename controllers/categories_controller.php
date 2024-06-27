<?php
    require '../models/global_constants.php';
    
    session_start();

    if(isset($_POST['category_add'])) { // if this controller is called by add category
         // Create connection 
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Check if the session for seller id is available
        if(!isset($_SESSION['seller_id'])) {
            include '../models/reset_to_guest.php';
        } else {
            $seller_id = $_SESSION['seller_id'];
        }

        // Adds the new category
        $sql = "INSERT INTO CATEGORY (category_name, seller_id) VALUES ('" . $_POST['category_name'] . "', '" . $_SESSION['seller_id'] .  "')";
        
        $conn->query($sql);

        // Closes connection
        $conn->close();

        header("Location: /categories");
    } else if(isset($_POST['rename'])) {
        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Check if the session for seller id is available
        if(!isset($_SESSION['seller_id'])) {
            include '../models/reset_to_guest.php';
        }

        // Adds the new category
        $sql = "UPDATE CATEGORY SET category_name='" . $_POST['category_name'] . "' WHERE id=" .  $_POST['category_id'];
        
        $conn->query($sql);
        
        // Closes connection
        $conn->close();

        header("Location: /categories");
    } else if(isset($_POST['delete_cat'])) {
        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Check if the session for seller id is available
        if(!isset($_SESSION['seller_id'])) {
            include '../models/reset_to_guest.php';
        }

        // Deletes the category
        $sql = "DELETE FROM CATEGORY WHERE id=" . $_POST['category_id'];
        $conn->query($sql);

        //Deletes all product with the said category
        $sql = "DELETE FROM PRODUCT WHERE category_id=" . $_POST['category_id'];
        $conn->query($sql);

        // Closes connection
        $conn->close();

        header("Location: /categories");
    }
?>