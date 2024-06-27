<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- shared head code -->
    <?php require 'templates/head.php' ?>
    <!-- setup for user controls -->
    <?php 
        require '../models/global_constants.php';
        require '../models/user_model.php';
        require '../models/product_model.php';
        require '../controllers/model_controllers/seller_model_controller.php';

        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

        if(!isset($_SESSION['seller_id'])) {
            require '../models/reset_to_guest.php';
        }
                
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if(!isset($_GET['type']) || $_GET['type'] == 'All') {
            $where_status = "order_status!='Cart'";
        } else if($_GET['type'] == 'Ongoing') {
            $where_status = "order_status='Ongoing'";
        } else if($_GET['type'] == 'Completed') {
            $where_status = "order_status='Completed'";
        } else {
            $conn->close();
            header('Location: /orders');
            exit();
        }

        // Load the orders
        $sql = "SELECT * FROM MEMBER_ORDER WHERE " . $where_status . " AND seller_id=" . $_SESSION['seller_id'] . " ORDER BY order_status ASC, id DESC";

        $result = $conn->query($sql);
        $fetched_data = $result->fetch_all(MYSQLI_ASSOC);

        // Closes connection
        $conn->close();
    ?>
</head>
<body>
    <!-- navbar -->
    <?php require 'templates/navbar.php' ?>

    <section id="page-header" class="product-header">
        <h2>Orders</h2>
    </section>

    <form method="GET" action="orders">
        <label for="order_type">Product Type:</label>
        <select id="order_type" name="type" required>
            <option value="All" <?php if(!isset($_GET['type']) || $_GET['type'] == 'All') echo "selected" ?>>All orders</option>
            <option value="Ongoing" <?php if(isset($_GET['type']) && $_GET['type'] == 'Ongoing') echo "selected" ?>>Ongoing orders</option>
            <option value="Completed" <?php if(isset($_GET['type']) && $_GET['type'] == 'Completed') echo "selected" ?>>Completed orders</option>
        </select>
        <br>
        <button type="submit">Apply Filter</button>
    </form>

    <?php
        if(isset($_GET['type'])) {
            $order_status = $_GET['type'];
        } else { 
            $order_status = 'All';
        }

        foreach($fetched_data as $order) {
            $user = new User();
            if(isset($_SESSION['user_type'])) {
                $user_type = $_SESSION['user_type'];
            } else {
                require '../models/reset_to_guest.php';
            }

            if($user_type == 'Seller') {
                $user->get_data_by_seller_id($order['seller_id']);
                $counter_type = 'Member';
            }

            if($user_type == 'Member') {
                $user->get_data_by_member_id($order['member_id']);
                $counter_type = 'Seller';
            }

            if(($order_status == 'All' || $order_status == 'Ongoing') && $order['order_status'] == 'Ongoing')
                require 'templates/ongoing_order_card.php';
            if(($order_status == 'All' || $order_status == 'Completed') && $order['order_status'] == 'Completed')
                require 'templates/completed_order_card.php';
        }
    ?>

    <?php include 'templates/footer.php' ?>
    <script src="../scripts/open_order.js"></script>
</body>
</html>