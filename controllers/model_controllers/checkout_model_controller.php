<?php
    function checkout_items($order_id, $product_id, $quantity) {
        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                            
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // check if the quantity exceeds the stock
        $sql = "SELECT quantity FROM PRODUCT WHERE id=" . $product_id;
        $result = $conn->query($sql);
        $fetched_data = $result->fetch_assoc();
        $product_stock = $fetched_data['quantity'];

        if($product_stock < $quantity) {
            $quantity = $product_stock; // max out the quantity to the stock
        }

        // Check if the item is already there
        $sql = "SELECT id, quantity FROM CHECKOUT_ITEM WHERE order_id=" . $order_id . " AND product_id=" . $product_id;

        $result = $conn->query($sql);
        $fetched_data = $result->fetch_assoc();

        if(empty($fetched_data['id'])) { // no existing item
            $sql = "INSERT INTO CHECKOUT_ITEM (quantity, product_id, order_id) VALUES (" . $quantity . ", ". $product_id .", " . $order_id .");";
            
            $conn->query($sql);
        } else { // existing item
            $new_quantity = $fetched_data['quantity'] + $quantity;

            if($product_stock < $new_quantity) {
                $new_quantity = $product_stock; // max out the quantity to the stock
            }

            $sql = "UPDATE CHECKOUT_ITEM SET quantity=" . $new_quantity . " WHERE id=" . $fetched_data['id'];
            
            $result = $conn->query($sql);
        }

        // Closes connection
        $conn->close();
    }

    function refresh_quantity($item_id, &$item_quantity, $product_quantity) : bool {
        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                            
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        

        if($product_quantity == 0) {
            $item_quantity = 0;
            $sql = "DELETE FROM CHECKOUT_ITEM WHERE id=" . $item_id;
            $conn->query($sql);
            $changed = true;
        } else if($item_quantity > $product_quantity) {
            $item_quantity = $product_quantity;
            $sql = "UPDATE CHECKOUT_ITEM SET quantity=" .$product_quantity . " WHERE id=" . $item_id;
            $conn->query($sql);
            $changed = true;
        } else {
            $changed = false;
        }

        // Closes connection
        $conn->close();

        return $changed;
    }

    function get_items($postArray) { // key is checkout item id and value is quantity
        $quantities = array();

        foreach ($postArray as $key => $value) {
            if (strpos($key, 'quantity') === 0) {
                $id = substr($key, strlen('quantity'));
                $quantities[$id] = $value;
            }
        }

        return $quantities;
    }

    function reassign_items(&$items) : bool {
        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                            
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $refreshed = false;

        foreach($items as $item_id=>$item_quantity) {
            // get the product id of the item
            $sql = "SELECT product_id FROM CHECKOUT_ITEM WHERE id=" . $item_id;
            $result = $conn->query($sql);
            $fetched_data = $result->fetch_assoc();
            $product_id = $fetched_data['product_id'];

            // get the product quantity of the item
            $sql = "SELECT quantity FROM PRODUCT WHERE id=" . $product_id;
            $result = $conn->query($sql);
            $fetched_data = $result->fetch_assoc();
            $product_quantity = $fetched_data['quantity'];

            // refresh the item quantity if applicable
            $changed = refresh_quantity($item_id, $item_quantity, $product_quantity);

            if($changed) { // if there is a change
                $refreshed = true;
            }
        }

        // Closes connection
        $conn->close();
        return $refreshed;
    }

    function update_products($items) {
        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $refreshed = false;

        foreach($items as $item_id=>$item_quantity) {
            // get the product id of the item
            $sql = "SELECT product_id, quantity FROM CHECKOUT_ITEM WHERE id=" . $item_id;
            $result = $conn->query($sql);
            $fetched_data = $result->fetch_assoc();
            $product_id = $fetched_data['product_id'];

            $sql = "SELECT quantity FROM PRODUCT WHERE id=" . $product_id;
            $result = $conn->query($sql);
            $fetched_data = $result->fetch_assoc();
            $product_quantity = $fetched_data['quantity'];

            // set the quantity of the products
            $sql = "UPDATE PRODUCT SET quantity=" .$product_quantity - $item_quantity . " WHERE id=" . $product_id;
            
            $conn->query($sql);
        }

        // Closes connection
        $conn->close();
    }

    function convert_datetime($date, $time) {
        $datetimeString = $date . ' ' . $time;
        $datetime = DateTime::createFromFormat('Y-m-d H:i', $datetimeString);
        return $datetime->format('Y-m-d H:i:s');
    }

    function calculate_total_amount($items) {
        $total = 0;

        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        foreach($items as $item_id=>$item_quantity) {
            // get the product id of the item
            $sql = "SELECT product_id FROM CHECKOUT_ITEM WHERE id=" . $item_id;
            $result = $conn->query($sql);
            $fetched_data = $result->fetch_assoc();

            // select the price
            $sql = "SELECT price FROM PRODUCT WHERE id=" . $fetched_data['product_id'];
            $result = $conn->query($sql);
            $fetched_data = $result->fetch_assoc();

            $total += ($item_quantity * $fetched_data['price']);
        }

        // Closes connection
        $conn->close();
        return $total;
    }

    function delete_order($order_id) {
        // Create connection
       $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

       // Check connection
       if ($conn->connect_error) {
           die("Connection failed: " . $conn->connect_error);
       }
       
       $sql = "DELETE FROM MEMBER_ORDER WHERE id=" . $order_id;
       $conn->query($sql);

       $conn->close();
   }
?>