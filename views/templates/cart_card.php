<section>
    <div style="margin: 20px">
        <table border="1" width="100%">
            <tr>
                <!-- header -->
                <td width="5%">
                    <?php
                        echo "<img src=\"../" . $seller->profile_pic_url . "\" width='100%'>";
                    ?>
                </td>
                <td width="45%">
                    <h4><?php echo $seller_user->first_name . " " . $seller_user->last_name ?></h4>
                </td>
                <td width="30%">
                    <h4>Order Number: <?php echo $cart['id'] ?></h4>
                </td>
                <td>
                    <h4>For <?php echo $cart['collection_mode'] ?></h4>
                </td>
            </tr>
            <tr>
                <!-- checkout items -->
                <td colspan="4">
                        <?php
                        // Create connection
                        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

                        // Check connection
                        if ($conn->connect_error) {
                            echo "Connection failed: " . $conn->connect_error;
                        }

                        $sql = "SELECT * FROM CHECKOUT_ITEM WHERE order_id=" . $cart['id'];

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
                                        echo "<td width='10%'>";
                                            echo "<img src=\"../" . $product->product_pic_url . "\" width='100%'>";
                                        echo "<td>" ;
                                        echo "<td>";
                                            echo "<h3>" . $product->product_name . "</h3>";
                                            echo "<p> Quantity: " . $item['quantity'] . "</p>";
                                            echo "<p> Subtotal: &#8369;" . $item['quantity'] * $product->price . "</p>";
                                        echo "<td>" ;
                                    echo "</tr>";
                                echo "</table>";
                                echo "</a>";
                            }
                        ?>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <!-- proceed to checkout -->
                    <?php echo "<button onclick='open_cart(" . $cart['id'] . ")'>" ?>
                        Proceed to Checkout
                    </button>
                    <span style="float: right">You can edit your cart at checkout</span>
                </td>
            </tr>
        </table>
    </div>
</section>