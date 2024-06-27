<section>
    <div style="margin: 20px">
        <table border="1" width="100%">
            <tr>
                <!-- header -->
                <td width="20%">
                    <h4>Ongoing</h4>
                </td>
                <td width="20%">
                    <h4>Order Number: <?php echo $order['id'] ?></h4>
                </td>
                <td width="55%" style="text-align: right">
                    <h4><?php echo $counter_type . ": " . $user->first_name . " " . $user->last_name ?></h4>
                </td>
            </tr>
            <tr>
                <!-- rendevous detail -->
                <td colspan="2">
                    <h5>Pickup Location: <?php echo $order['pickup_location'] ?></h5>
                </td>
                <td colspan="1" style="text-align: right">
                    <h5>Pickup Date and Time: <?php
                        $pickupDateTime = new DateTime($order['pickup_date_time']);
                        $formattedDateTime = $pickupDateTime->format('l - F j, Y (g:i A)');
                        echo $formattedDateTime;
                    ?></h5>
                </td>
            </tr>
            <tr>
                <!-- checkout items -->
                <td colspan="3">
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
                                if($user_type == 'Member') { 
                                    echo "<a href='product?product_number=" . $item['product_id'] . "'>";
                                }
                                echo "<table width='100%'>";
                                    echo "<tr>";
                                        echo "<td width='10%'>";
                                            echo "<img src=\"../" . $product->product_pic_url . "\" width='100%'>";
                                        echo "<td>" ;
                                        echo "<td>";
                                            echo "<h3>" . $product->product_name . "</h3>";
                                            echo "<p> Quantity: " . $item['quantity'] . "</p>";
                                        echo "<td>" ;
                                    echo "</tr>";
                                echo "</table>";
                                if($user_type == 'Member') echo "</a>";
                            }
                        ?>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <!-- proceed to checkout -->
                    <?php echo "<button onclick='open_chat(" . $order['id'] . ")'>" ?>
                        Chat with the <?php echo $counter_type ?>
                    </button>
                </td>
            </tr>
        </table>
    </div>
</section>