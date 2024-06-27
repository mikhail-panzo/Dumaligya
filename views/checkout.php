<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- shared head code -->
    <?php require 'templates/head.php' ?>
    <!-- setup for user control -->
    <?php 
        require '../models/global_constants.php';
        require '../models/user_model.php';
        require '../models/seller_model.php';
        require '../models/product_model.php';
        require '../controllers/model_controllers/checkout_model_controller.php';

        if(!isset($_SESSION['member_id'])) {
            require '../models/reset_to_guest.php';
        }

        if(!isset($_GET['order_number'])) {
            header('Location: /cart');
            exit();
        }

        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Load the order
        $sql = "SELECT * FROM MEMBER_ORDER where order_status='Cart' and member_id=" . $_SESSION['member_id'] . " and id=" . $_GET['order_number'];

        $result = $conn->query($sql);
        $fetched_data = $result->fetch_assoc();

        if(empty($fetched_data['id'])) {
            $conn->close();
            header('Location: /cart');
            exit();
        }

        // Load seller address
        $sql = "SELECT seller_schedule FROM SELLER_USER WHERE id=" .$fetched_data['seller_id'];
        $result = $conn->query($sql);
        $seller_data = $result->fetch_assoc();
        $seller_address = $seller_data['seller_schedule'];
    
        // Closes connection
        $conn->close();

        if($fetched_data['member_id'] != $_SESSION['member_id']) { //order should not be accessed
            header('Location: /cart');
            exit();
        }

        $seller_user = new User();
        $seller_user->get_data_by_seller_id($fetched_data['seller_id']);
    ?>
</head>

<body>
    <?php
    // navbar
    require 'templates/navbar.php';
    ?>
    <h5>Seller: <?php echo $seller_user->first_name . " " . $seller_user->last_name ?></h5>
    <?php
        if(isset($_SESSION['updated_quantity_message'])) {
            echo "<p>";
            echo $_SESSION['updated_quantity_message'];
            echo "</p>";

            unset($_SESSION['updated_quantity_message']);
        }
    ?>

    <form action="../../controllers/checkout_controller.php" method="POST" onsubmit="return validateForm();">
        <input type="hidden" name="order_id" value="<?php echo $_GET['order_number'] ?>">
        <section class="cart section--lg container">
            <div class="table-container">
                <table class="table">
                    <tr>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Remove</th>
                    </tr>

                    <?php
                        // Create connection
                        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

                        // Check connection
                        if ($conn->connect_error) {
                            echo "Connection failed: " . $conn->connect_error;
                        }

                        $sql = "SELECT * FROM CHECKOUT_ITEM WHERE order_id=" . $_GET['order_number'];

                        $result = $conn->query($sql);
                        $items = $result->fetch_all(MYSQLI_ASSOC);

                        // Closes connection
                        $conn->close();

                        if(empty($items)) {
                            delete_order($_GET['order_number']);
                            header('Location: cart');
                            exit();
                        }

                        foreach($items as $item) {
                            $product = new Product();
                            $product->get_data($item['product_id']);
                            refresh_quantity($item['id'], $item['quantity'], $product->quantity);
                            if($item['quantity'] == 0) {
                                break;
                            }

                            echo "<tr>";
                                echo "<td><img src='" . $product->product_pic_url . "' class='table-img'></td>";
                                echo "<td><h3 class='table-title'>".$product->product_name."</h3></td>";
                                echo "<td><span class='table-price'>&#8369;".$product->price."</span></td>";
                                echo "<td><input type='number' value='".$item['quantity']."' class='quantity' onchange='updateSubtotal(".$item['id'].", this.value, ".$product->price.")' name='quantity".$item['id']."' min='1' max='".$product->quantity."'></td>";
                                echo "<td><span class='table-price'>&#8369;<span class='item_subtotal' id='subtotal".$item['id']."'>".$product->price*$item['quantity']."</span></span></td>";
                                echo "<td><a href=\"../controllers/checkout_controller.php?id=".$item['id']."&ord=".$_GET['order_number']."\"><i class='fa-regular fa-trash-can table-trash'></button></i></a></td>";
                            echo "</tr>";
                        }
                    ?>
                </table>
            </div>

            <div class="divider">
                <i class="fa-solid fa-money-check-dollar"></i>
            </div>

            <div class="cart-group grid">
                <div>
                    <div class="cart-shipping">
                        <h3 class="section-title">Rendezvous Details: </h3>
                        <div action="" class="form grid">
                            <label for="pickup_location">Pickup Location: </label>
                            <input type="text" id="pickup_location" value="<?php echo $fetched_data['pickup_location']?>" name="pickup_location" class="form-input-text" maxlength="255">

                            <label for="pickup_date">Pickup Date and Time: </label>
                            <div class="form-group grid" id="pickup_date">
                                <input type="date" placeholder="Pick up Date" name="date" class="form-input-text" required>

                                <input type="time" name="time" placeholder="Pick up Time" class="form-input-text" required>
                            </div>

                            <p>Seller's schedule: <?php echo $seller_address ?></p>

                        </div>
                    </div>

                    <div class="cart-method">

                        <h3 class="section-title">Choose your payment method: </h3>
                        <label for="bdo">
                            <input type="radio" id="bdo" name="payment_mode" value="BDO" required>BDO
                        </label><br>
                        <label for="metrobank">
                            <input type="radio" id="metrobank" name="payment_mode" value="Metrobank" required>Metrobank
                        </label><br>
                        <label for="BPI">
                            <input type="radio" id="bpi" name="payment_mode" value="BPI" required>BPI
                        </label><br>
                        <label for="inperson">
                        <input type="radio" id="inperson" name="payment_mode" value="In Person" required>In Person
                        </label><br>
                        <label for="other">
                        <input type="radio" id="other" name="payment_mode" value="Other" required>Other 
                        <input type="text" id="other_text" name="other_payment_mode" maxlength="50">
                        </label><br>

                    </div>
                </div>

                <div class="cart-total">
                    <h3 class="section-title">Total Payment: </h3>

                    <table class="cart-total-table">
                        <tr>
                            <td><span class="cart-total-title">Cart Subtotal</span></td>
                            <td><span class="cart-total-price">&#8369;<span id="cart_subtotal"></span></span></td>
                        </tr>

                        <tr>
                            <td><span class="cart-total-title">App Fee</span></td>
                            <td>
                                <span class="cart-total-price">&#8369;
                                    <span id="app_fee"></span>
                                    <input type="hidden" id="fee" name="fee" value="<?php echo FEE ?>"></span>
                                </span>
                            </td>

                        </tr>

                        <tr>
                            <td><span class="cart-total-title">Total</span></td>
                            <td><span class="cart-total-price">&#8369;<span id='total'></span></span></td>
                        </tr>
                    </table>

                    <button type="submit" name="checkout_confirm" class="btn flex btn-md"> 
                        <i class="fa-solid fa-box"></i> Confirm Payment 
                    </button>
                </div>
            </div>
        </section>
    </form>

    <footer class="section-p1">
        <div class="col">
            <img class="logo"src="images/logo.png" alt="">
            <h4>Contact</h4>
            <p><strong>Address:</strong> 8855+F7W, Noblefranca St, Dumaguete, Negros Oriental</p>
            <p><strong>Phone:</strong> +01 2345 6789</p>
            <p><strong>Hours:</strong> 10:00 - 18:00 Mon-Sat</p>
            <div class="follow">
                <h4>Follow us</h4>
                <div class="icon">
                    <i class="fab fa-facebook-f"></i>
                    <i class="fab fa-twitter"></i>
                    <i class="fab fa-instagram"></i>
                    <i class="fab fa-pinterest-p"></i>
                    <i class="fab fa-youtube"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <h4>About</h4>
            <a href="#">About Us</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms and Conditions</a>
            <a href="#">Contact Us</a>
        </div>

        <div class="col">
            <h4>My Account</h4>
            <a href="#">Sign In</a>
            <a href="#">View Cart</a>
            <a href="#">Help</a>
        </div>

        <div class="col install">
            <h4>Install App</h4>
            <p>From Apple Store or Google</p>
            <div class="row">
                <img src="images/payment/apple.png" alt="">
                <img src="images/payment/google.png" alt="">
            </div>
            <p>Secured Payment Gateways</p>
            <img src="images/payment/card.png" alt="">
        </div>

        <div class="copyright">
            <p> Copyright &#169; 2023 Dumaligya. All rights reserved. </p>
        </div>
    </footer>
    <script src="../scripts/confirm_checkout.js"></script>
</body>
</html>