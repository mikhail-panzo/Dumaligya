<?php
    require '../models/global_constants.php';
    require '../controllers/model_controllers/checkout_model_controller.php';
    session_start();

    if(isset($_POST['checkout_confirm'])) { //check if this controller is called from checkout
       
        // transforms the quantity elements to array of id and quantity
        $items = get_items($_POST);
        // reassigns the quantity of checkout items
        $reassign_result = reassign_items($items);

        // if the product quantity has changed when the user confirmed payment
        if($reassign_result) {
            header('Location: /checkout?order_number=' . $_POST['order_id']);
            $_SESSION['updated_quantity_message'] = "The quantity of some products have changed. Confirm new quantity.";
            exit();
        }

        
        // update all products after item quantities are not changed
        update_products($items);

        // save the order
        $datetime = convert_datetime($_POST['date'], $_POST['time']);
        $total_amount = calculate_total_amount($items);
        if($_POST['payment_mode'] == "Other") {
            $payment_mode = $_POST['other_payment_mode'];
        } else {
            $payment_mode = $_POST['payment_mode'];
        }

        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "UPDATE MEMBER_ORDER SET order_status='Ongoing', pickup_location='". $_POST['pickup_location']."', pickup_date_time='".$datetime."', total_amount='".$total_amount."', payment_mode='".$payment_mode."' WHERE id=" . $_POST['order_id'];
        $conn->query($sql);

        $conn->close();
    
        // go to orders
        header('Location: /orders?type=Ongoing');
        exit();        
    } else if(isset($_GET['id']) && isset($_GET['ord'])) { //check if the delete item is clicked
        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "DELETE FROM CHECKOUT_ITEM WHERE id=" . $_GET['id'];
        $conn->query($sql);

        $conn->close();

        header('Location: /checkout?order_number=' . $_GET['ord']);
        exit();
    }
?>