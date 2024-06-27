<?php
    function get_category_name($id) : string {
        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "SELECT category_name FROM CATEGORY WHERE id=" . $id;

        $result = $conn->query($sql);
        $fetched_data = $result->fetch_assoc();
        $return_value = $fetched_data['category_name'];

        // Closes connection
        $conn->close();

        return $return_value;
    }

    function remove_self_products(&$fetched_data, $user_id) {
        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT seller_id FROM USER WHERE id=" . $user_id;

        $result = $conn->query($sql);
        $user_row = $result->fetch_assoc();
        $seller_id = $user_row['seller_id'];

        // Closes connection
        $conn->close();

        foreach($fetched_data as $index => $product) {
            if($product['seller_id'] == $seller_id) {
                unset($fetched_data[$index]);
            }
        }
    }

    function get_seller_id_from_product($product_id) : int {
        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                 
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
 
        $sql = "SELECT seller_id FROM PRODUCT WHERE id=" . $product_id;
 
        $result = $conn->query($sql);
        $fetched_data = $result->fetch_assoc();
 
        $return_value = $fetched_data['seller_id'];
 
        // Closes connection
        $conn->close();
 
        return $return_value;
     }

    function get_product_collection_mode($product_id) : string {
        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
            
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT collection_mode FROM PRODUCT WHERE id=" . $product_id;

        $result = $conn->query($sql);
        $fetched_data = $result->fetch_assoc();

        $return_value = $fetched_data['collection_mode'];

        // Closes connection
        $conn->close();

        return $return_value;
    }
?>