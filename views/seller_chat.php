<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- shared head code -->
    <?php require 'templates/head.php' ?>
    <link rel="stylesheet" href="../styles/chat_order_detail.css">
    <!-- load product -->
    <?php
        require '../models/global_constants.php';
        require '../models/product_model.php';
        require '../models/user_model.php';
        
        // if the product page was requested without product number
        if(!isset($_GET['order_number'])) {
            header('Location: /orders');
            exit();
        }

        if(!isset($_SESSION['seller_id'])) {
            require '../models/reset_to_guest.php';
        }

        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Load the order
        $sql = "SELECT * FROM MEMBER_ORDER WHERE id=" . $_GET['order_number'] . " AND seller_id=" . $_SESSION['seller_id'];

        $result = $conn->query($sql);
        $order = $result->fetch_assoc();

        if(empty($order['id'])) {
            header('Location: /orders');
        }

        // Closes connection
        $conn->close();
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

        .profile_img {
            width: 400px;
            height: 400px;
            object-fit: cover;
        }
    </style>
</head>

<body>

<!-- navbar -->
<?php require 'templates/navbar.php' ?>

<!-- order_details -->
<div class="order_detail">
    <p class="order_detail_text">Order number: <?php echo $order['id'] ?></p>
    <br>
    <p class="order_detail_text">Pickup Details:</p>
    <p class="order_detail_text" style="font-size: 15px"><?php echo $order['pickup_location'] ?></p>
    <p class="order_detail_text" style="font-size: 15px">
        <?php
            $pickupDateTime = new DateTime($order['pickup_date_time']);
            $formattedDateTime = $pickupDateTime->format('l - F j, Y (g:i A)');
            $date = $pickupDateTime->format('Y-m-d');
            $time = $pickupDateTime->format('H:i:s');
            echo $formattedDateTime;
        ?>
    </p>
    <br>
    <div class="product_show">
        <?php
            // Create connection
            $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

            // Check connection
            if ($conn->connect_error) {
                echo "Connection failed: " . $conn->connect_error;
            }

            $sql = "SELECT * FROM CHECKOUT_ITEM WHERE order_id=" . $order['id'];

            $result = $conn->query($sql);
            $items = $result->fetch_all(MYSQLI_ASSOC);

            // Closes connection
            $conn->close();

            foreach($items as $item) {
                $product = new Product();
                $product->get_data($item['product_id']);
                echo "<table width='100%'>";
                    echo "<tr>";
                        echo "<td width='20%'>";
                            echo "<img src=\"../" . $product->product_pic_url . "\" width='100%'>";
                        echo "<td>" ;
                        echo "<td>";
                            echo "<p class=\"order_detail_text\">" . $product->product_name . "</p>";
                            echo "<p class=\"order_detail_text\"> Quantity: " . $item['quantity'] . "</p>";
                        echo "<td>" ;
                    echo "</tr>";
                echo "</table>";
            }
        ?>
    </div>
    <br>
    <button onclick="rename(<?php echo $_SESSION['user_id'] ?>)">Edit Order Details</button>
</div>

<!-- popup form -->
<div class="popup-form" id="popup">
    <form action="../../controllers/chat_controller.php" method="POST">
        <h3>Edit Order Details</h3><br>

        <input type="hidden" name="order_id" value="<?php echo $order['id'] ?>">

        <label for="pickup_location">Pickup Location: </label>
        <input type="text" id="pickup_location" name="pickup_location" value="<?php echo $order['pickup_location'] ?>" maxlength="255"><br>

        <label for="pickup_date">Pickup Date:</label>
        <input type="date" id="pickup_date" value="<?php echo $date ?>" name="date" class="form-input-text" required><br>

        <label for="pickup_time">Pickup Date:</label>
        <input type="time" id="pickup_time" name="time" value="<?php echo $time ?>" class="form-input-text" required><br>

        <button type="submit" name="order_edit">Save</button>
        <button type="submit" name="cancel">Cancel</button>
    </form>
</div>

<!-- chat -->
<section style="width: 65%; min-height: 500px">
    <form method="POST" action="../controllers/chat_controller.php">
        <input type="hidden" name="order_id" value="<?php echo $order['id'] ?>">
        <button type="submit" name="seller_send">Send</button>
        <textarea type="text" name="message" maxlength="1000" required></textarea>
    </form>
    <?php
        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM MESSAGE WHERE order_id=" . $order['id'] . " ORDER BY mes_timestamp DESC";

        $result = $conn->query($sql);
        $messages = $result->fetch_all(MYSQLI_ASSOC);
                
        // Closes connection
        $conn->close();

        foreach($messages as $message) {
            if($message['source'] == 'Member') {
                $user = new User();
                $user->get_data_by_member_id($message['member_id']);
            } else {
                $user = new User();
                $user->get_data_by_seller_id($message['seller_id']);
            }

            $pickupDateTime = new DateTime($message['mes_timestamp']);
            $timestamp = $pickupDateTime->format('F j, Y (g:i A)');

            echo "<table border='1' width='100%''>";
                echo "<tr>";
                    echo "<td width='50%'>";
                        echo "<span>[".$message['source']."] ".$user->first_name . " " . $user->last_name."</span>";
                    echo "</td>";
                    echo "<td width='50%' style='text-align: right'>";
                        echo "<span>".$timestamp."</span>";
                    echo "</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td colspan='2'>";
                        echo "<p>" . $message['mes_text']. "</p>";
                    echo "</td>";
                echo "</tr>";
            echo "</table>";
        }
    ?>
</section>

<script src="../scripts/popup_form.js"></script>
</body>
</html>