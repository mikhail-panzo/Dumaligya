<?php
    require '../models/global_constants.php';

    session_start();

    if(isset($_POST['member_send'])) { // if message was sent from member
        if(isset($_SESSION['member_id'])) {
            $member_id = $_SESSION['member_id'];
        }else{
            require '../models/reset_to_guest.php';
        }

        $current_dt = date('Y-m-d H:i:s');

        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
            
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO MESSAGE (source, mes_text, mes_timestamp, member_id, order_id) VALUES ('Member', '".$_POST['message']."', '".$current_dt."', '" .$member_id. "', '".$_POST['order_id']."');";
        $conn->query($sql);
                
        // Closes connection
        $conn->close();
        
        header('Location: /chat?order_number=' . $_POST['order_id']);
        exit();
    } else if(isset($_POST['seller_send'])) { // if message was sent from seller
        if(isset($_SESSION['seller_id'])) {
            $seller_id = $_SESSION['seller_id'];
        }else{
            require '../models/reset_to_guest.php';
        }

        $current_dt = date('Y-m-d H:i:s');

        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
            
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }


        $sql = "INSERT INTO MESSAGE (source, mes_text, mes_timestamp, seller_id, order_id) VALUES ('Seller', '".$_POST['message']."', '".$current_dt."', " .$seller_id. ", ".$_POST['order_id'].");";
        $conn->query($sql);


        // Closes connection
        $conn->close();
        
        header('Location: /chat?order_number=' . $_POST['order_id']);
        exit();
    } else if(isset($_POST['order_receive'])) { // if order receive is pressed
        $current_dt = date('Y-m-d H:i:s');

        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
            
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "UPDATE MEMBER_ORDER SET order_status='Completed', end_date_time='".$current_dt."' WHERE id=" . $_POST['order_id'];
        $conn->query($sql);
                
        // Closes connection
        $conn->close();
        
        header('Location: /orders?type=Completed');
        exit();
    } else if(isset($_POST['order_edit'])) { // if order edit is made
        $date = $_POST['date'];
        $time = $_POST['time'];

        $datetimeString = $date . ' ' . $time;

        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
            
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "UPDATE MEMBER_ORDER SET pickup_date_time='".$datetimeString."', pickup_location='".$_POST['pickup_location']."' WHERE id=" . $_POST['order_id'];
        $conn->query($sql);
                
        // Closes connection
        $conn->close();
        
        header('Location: /chat?order_number=' . $_POST['order_id']);
        exit();
    } else {
        header('Location: /chat?order_number=' . $_POST['order_id']);
        exit();
    }
?>