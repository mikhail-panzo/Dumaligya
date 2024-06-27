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

        if(!isset($_SESSION['member_id'])) {
            require '../models/reset_to_guest.php';
        }

        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Load the order
        $sql = "SELECT * FROM MEMBER_ORDER WHERE id=" . $_GET['order_number'] . " AND member_id=" . $_SESSION['member_id'];

        $result = $conn->query($sql);
        $order = $result->fetch_assoc();

        if(empty($order['id'])) {
            header('Location: /orders');
        }

        // Closes connection
        $conn->close();
    ?>
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
                echo "<a href='product?product_number=" . $item['product_id'] . "'>";
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
                echo "</a>";
            }
        ?>
    </div>
    <br>
    <form method="POST" action="../controllers/chat_controller.php">
        <input type="hidden" name="order_id" value="<?php echo $order['id'] ?>">
        <button type="submit" name="order_receive">Receive Order</button>
    </form>
</div>

<!-- chat -->
<section style="width: 65%; min-height: 500px">
    <form method="POST" action="../controllers/chat_controller.php">
        <input type="hidden" name="order_id" value="<?php echo $order['id'] ?>">
        <button type="submit" name="member_send">Send</button>
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

</body>
</html>