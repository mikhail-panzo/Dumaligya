<?php
class Member {
    // Properties
    public $user_name;
    public $user_address;
    public $bio;
    public $profile_pic_url;

    // Methods
    public function upload_profile_pic($file, $root_dir) : string {
        $file_name = "images/member_profile_pics/" . $this->user_name . "_" . $file['name'];
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

        $sql = "INSERT INTO MEMBER_USER (profile_pic_url, user_address, bio) VALUES ('".$this->profile_pic_url."', '".$this->user_address."', '".$this->bio."');";
        
        if ($conn->query($sql) == true) {
            $value = $conn->insert_id;
        } else {
            $value = 0;
        }

        // Closes connection
        $conn->close();
        return $value;
    }

    public function get_data($member_id) : bool {
        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
            
        // Check connection
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
        }

        $sql = "SELECT * FROM MEMBER_USER WHERE id=" . $member_id;

        $result = $conn->query($sql);
        $fetched_data = $result->fetch_assoc();

        if(empty($fetched_data)) {
            $value = false;
        } else {
            $value = true;

            $this->user_address = $fetched_data['user_address'];
            $this->bio = $fetched_data['bio'];
            $this->profile_pic_url = $fetched_data['profile_pic_url'];
        }

        // Closes connection
        $conn->close();
        return $value;
    }
}

?>