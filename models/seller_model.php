<?php
class Seller {
    // Properties
    public $user_name;
    public $pickup_location;
    public $seller_description;
    public $seller_schedule;
    public $profile_pic_url;

    // Methods
    public function upload_profile_pic($file, $root_dir) : string {
        $file_name = "images/seller_profile_pics/" . $this->user_name . "_" . $file['name'];
        echo $file['tmp_name'] . "<br>";
        echo $file_name . "<br>";
        if(move_uploaded_file($file['tmp_name'], $root_dir . $file_name)) {
            $this->profile_pic_url = $file_name;
            return $file_name;
        }
        
        return "";
    }

    public function insert_data() : int {
        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
            
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO SELLER_USER (profile_pic_url, pickup_location, seller_description, seller_schedule) VALUES ('".$this->profile_pic_url."', '".$this->pickup_location."', '".$this->seller_description."', '".$this->seller_schedule."');";
        
        if ($conn->query($sql) == true) {
            $value = $conn->insert_id;
        } else {
            $value = 0;
        }

        // Closes connection
        $conn->close();
        return $value;
    }

    public function get_data($seller_id) : bool {
        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
            
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT profile_pic_url, pickup_location, seller_description, seller_schedule FROM SELLER_USER WHERE id=" . $seller_id;

        $result = $conn->query($sql);
        $fetched_data = $result->fetch_assoc();

        if(empty($fetched_data)) {
            $value = false;
        } else {
            $value = true;

            $this->profile_pic_url = $fetched_data['profile_pic_url'];
            $this->pickup_location = $fetched_data['pickup_location'];
            $this->seller_description = $fetched_data['seller_description'];
            $this->seller_schedule = $fetched_data['seller_schedule'];
        }

        // Closes connection
        $conn->close();
        return $value;
    }
}
?>