<?php
    class Order {
    // Properties
        public $order_status;
        public $pickup_location;
        public $pickup_date_time;
        public $total_amount;
        public $payment_mode;
        public $collection_mode;
        public $end_date_time;
        public $seller_id;
        public $member_id;
        
        public function assign_cart() : int {
            // Create connection
            $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                    
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT id FROM MEMBER_ORDER WHERE order_status='Cart' AND seller_id=" . $this->seller_id . " AND collection_mode='" . $this->collection_mode . "' AND member_id=" . $this->member_id;

            $result = $conn->query($sql);
            $fetched_data = $result->fetch_assoc();

            if(empty($fetched_data['id'])) { // no existing cart
                $sql = "INSERT INTO MEMBER_ORDER (order_status, pickup_location, collection_mode, seller_id, member_id) VALUES ('Cart', '". $this->pickup_location ."', '" . $this->collection_mode ."', " .$this->seller_id. ", ".$this->member_id.");";
                
                $conn->query($sql);
                $return_value = $conn->insert_id;
            } else { // existing cart
                $return_value = $fetched_data['id'];
            }

            // Closes connection
            $conn->close();

            return $return_value;
        }
    }
?>