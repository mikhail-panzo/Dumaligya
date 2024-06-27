<?php
    class User {
        // Properties
        public $user_name;
        public $first_name;
        public $last_name;
        public $email;
        public $contact_number;
        public $user_password;
        public $government_pic_url;
        public $birth_date;
        public $sex;
        public $seller_id;
        public $member_id;

        // Methods
        public function upload_government_pic($file, $root_dir) : string {
            $file_name = "images/user_government_pics/" . $this->user_name . "_" . $file['name'];
            echo $file['tmp_name'] . "<br>";
            echo $file_name . "<br>";
            if(move_uploaded_file($file['tmp_name'], $root_dir . $file_name)) {
                $this->government_pic_url = $file_name;
                return $file_name;
            }
            
            return "";
        }

        // inserts the data into the database while getting the id
        public function insert_data() : int {
            // Create connection
            $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "INSERT INTO USER (user_name, first_name, last_name, email, contact_number, user_password, government_pic_url, birth_date, sex) VALUES ('".$this->user_name."', '".$this->first_name."', '".$this->last_name."', '" .$this->email. "', '".$this->contact_number."', '".$this->user_password."', '".$this->government_pic_url."', '" .$this->birth_date. "', '" . $this->sex . "');";
            
            if ($conn->query($sql) == true) {
                $value = $conn->insert_id;
            } else {
                $value = 0;
            }

            // Closes connection
            $conn->close();
            return $value;
        }

        public function get_data($user_id) : bool {
            // Create connection
            $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
            // Check connection
            if ($conn->connect_error) {
                echo "Connection failed: " . $conn->connect_error;
            }

            $sql = "SELECT * FROM USER WHERE id=" . $user_id;

            $result = $conn->query($sql);
            $fetched_data = $result->fetch_assoc();

            if(empty($fetched_data)) {
                $value = false;
            } else {
                $value = true;

                $this->user_name = $fetched_data['user_name'];
                $this->first_name = $fetched_data['first_name'];
                $this->last_name = $fetched_data['last_name'];
                $this->email = $fetched_data['email'];
                $this->contact_number = $fetched_data['contact_number'];
                $this->government_pic_url = $fetched_data['government_pic_url'];
                $this->birth_date = $fetched_data['birth_date'];
                $this->sex = $fetched_data['sex'];
                $this->seller_id = $fetched_data['seller_id'];
                $this->member_id = $fetched_data['member_id'];
            }

            // Closes connection
            $conn->close();
            return $value;
        }

        public function get_data_by_seller_id($seller_id) : bool {
            // Create connection
            $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
            // Check connection
            if ($conn->connect_error) {
                echo "Connection failed: " . $conn->connect_error;
            }

            $sql = "SELECT * FROM USER WHERE seller_id=" . $seller_id;

            $result = $conn->query($sql);
            $fetched_data = $result->fetch_assoc();

            if(empty($fetched_data)) {
                $value = false;
            } else {
                $value = true;

                $this->user_name = $fetched_data['user_name'];
                $this->first_name = $fetched_data['first_name'];
                $this->last_name = $fetched_data['last_name'];
                $this->email = $fetched_data['email'];
                $this->contact_number = $fetched_data['contact_number'];
                $this->government_pic_url = $fetched_data['government_pic_url'];
                $this->birth_date = $fetched_data['birth_date'];
                $this->sex = $fetched_data['sex'];
                $this->seller_id = $fetched_data['seller_id'];
                $this->member_id = $fetched_data['member_id'];
            }

            // Closes connection
            $conn->close();
            return $value;
        }

        public function get_data_by_member_id($member_id) : bool {
            // Create connection
            $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
            // Check connection
            if ($conn->connect_error) {
                echo "Connection failed: " . $conn->connect_error;
            }

            $sql = "SELECT * FROM USER WHERE member_id=" . $member_id;
           
            $result = $conn->query($sql);
            $fetched_data = $result->fetch_assoc();

            if(empty($fetched_data)) {
                $value = false;
            } else {
                $value = true;

                $this->user_name = $fetched_data['user_name'];
                $this->first_name = $fetched_data['first_name'];
                $this->last_name = $fetched_data['last_name'];
                $this->email = $fetched_data['email'];
                $this->contact_number = $fetched_data['contact_number'];
                $this->government_pic_url = $fetched_data['government_pic_url'];
                $this->birth_date = $fetched_data['birth_date'];
                $this->sex = $fetched_data['sex'];
                $this->seller_id = $fetched_data['seller_id'];
                $this->member_id = $fetched_data['member_id'];
            }

            // Closes connection
            $conn->close();
            return $value;
        }

        public function get_seller_id($user_id) : bool {
            // Create connection
            $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT seller_id FROM USER WHERE id=" . $user_id;

            $result = $conn->query($sql);
            $fetched_data = $result->fetch_assoc();

            if(empty($fetched_data)) {
                $value = false;
            } else {
                $value = true;

                $this->seller_id = $fetched_data['seller_id'];
            }

            // Closes connection
            $conn->close();
            return $value;
        }
        
        public function get_member_id($user_id) : bool {
            // Create connection
            $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT member_id FROM USER WHERE id=" . $user_id;

            $result = $conn->query($sql);
            $fetched_data = $result->fetch_assoc();

            if(empty($fetched_data)) {
                $value = false;
            } else {
                $value = true;

                $this->member_id = $fetched_data['member_id'];
            }

            // Closes connection
            $conn->close();
            return $value;
        }    
    }
?>