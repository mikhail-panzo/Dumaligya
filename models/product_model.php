<?php
    class Product {
        // Properties
        public $user_name;
        public $product_type;
        public $collection_mode;
        public $price;
        public $quantity;
        public $product_name;
        public $product_description;
        public $product_pic_url;
        public $seller_id;
        public $category_id;

        // Methods
        public function upload_product_pic($file, $root_dir) : string {
            $file_name = "images/product_pics/" . $this->user_name . "_" . $file['name'];
            echo $file['tmp_name'] . "<br>";
            echo $file_name . "<br>";
            if(move_uploaded_file($file['tmp_name'], $root_dir . $file_name)) {
                $this->product_pic_url = $file_name;
                return $file_name;
            }
            
            return "";
        }

        // inserts the data into the database while getting the id
        public function insert_data() : bool {
            // Create connection
            $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "INSERT INTO PRODUCT (product_type, collection_mode, price, quantity, product_name, product_description, product_pic_url, category_id, seller_id) VALUES ('".$this->product_type."', '".$this->collection_mode."', ".$this->price.", " .$this->quantity. ", '".$this-> product_name."', '".$this->product_description."', '".$this->product_pic_url."', ".$this-> category_id.", ".$this-> seller_id.")";
            
            if ($conn->query($sql) == true) {
                $value = true;
            } else {
                $value = false;
            }

            // Closes connection
            $conn->close();
            return $value;
        }

        public function get_data($product_id) : bool {
            // Create connection
            $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT product_type, collection_mode, price, quantity, product_name, product_description, product_pic_url, category_id, seller_id FROM PRODUCT WHERE id=" . $product_id;

            $result = $conn->query($sql);
            $fetched_data = $result->fetch_assoc();

            if(empty($fetched_data)) {
                $value = false;
            } else {
                $value = true;

                $this->product_type = $fetched_data['product_type'];
                $this->collection_mode = $fetched_data['collection_mode'];
                $this->price = $fetched_data['price'];
                $this->quantity = $fetched_data['quantity'];
                $this->product_name = $fetched_data['product_name'];
                $this->product_description = $fetched_data['product_description'];
                $this->product_pic_url = $fetched_data['product_pic_url'];
                $this->seller_id = $fetched_data['seller_id'];
                $this->category_id = $fetched_data['category_id'];
            }

            // Closes connection
            $conn->close();
            return $value;
        }
    }
?>