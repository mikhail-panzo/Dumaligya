<?php
    function insert_seller($seller_data) : int {
        $seller = new Seller();

        $seller->pickup_location = $seller_data['pickup_location'];
        $seller->seller_description = $seller_data['seller_description'];
        $seller->seller_schedule = $seller_data['seller_schedule'];
        $seller->profile_pic_url = $seller_data['profile_pic_url'];

        return $seller->insert_data();
    }

    function get_seller_name($id) : string {
        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT first_name, last_name FROM USER WHERE seller_id=" . $id;

        $result = $conn->query($sql);
        $fetched_data = $result->fetch_assoc();

        $return_value = $fetched_data['first_name'] . " " . $fetched_data['last_name'];

        // Closes connection
        $conn->close();

        return $return_value;
    }
    function get_pickup_location($seller_id) : string { // only when collection mode is pickup
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                 
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
 
        $sql = "SELECT pickup_location FROM SELLER_USER WHERE id=" . $seller_id;
 
        $result = $conn->query($sql);
        $fetched_data = $result->fetch_assoc();
 
        $return_value = $fetched_data['pickup_location'];
 
        // Closes connection
        $conn->close();
 
        return $return_value;
    }

    function remove_self_seller(&$fetched_data, $user_id) {
        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Load the products
        $sql = "SELECT seller_id FROM USER WHERE id=" . $user_id;

        $result = $conn->query($sql);
        $user_row = $result->fetch_assoc();
        $seller_id = $user_row['seller_id'];

        // Closes connection
        $conn->close();

        foreach($fetched_data as $index => $seller) {
            if($seller['id'] == $seller_id) {
                unset($fetched_data[$index]);
            }
        }
    }
?>