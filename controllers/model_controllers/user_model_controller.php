<?php
    function insert_user($user_data) : int {
        $user = new User();

        $user->user_name = $user_data['user_name'];
        $user->first_name = $user_data['first_name'];
        $user->last_name = $user_data['last_name'];
        $user->email = $user_data['email'];
        $user->contact_number = $user_data['contact_number'];
        $user->user_password = $user_data['user_password'];
        $user->birth_date = $user_data['birth_date'];
        $user->sex = $user_data['sex'];
        $user->government_pic_url = $user_data['government_pic_url'];

        return $user->insert_data();
    }

    function assign_member_to_user($user_id, $member_id) : bool {
         // Create connection
         $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
            
         // Check connection
         if ($conn->connect_error) {
             echo "Connection failed: " . $conn->connect_error;
         }
 
         $sql = "UPDATE USER SET member_id='". $member_id . "' WHERE id=". $user_id ."";
         
         if ($conn->query($sql) == true) {
             $value = true;
         } else {
             $value = false;
         }
 
         // Closes connection
         $conn->close();
         return $value;
    }

    function assign_seller_to_user($user_id, $seller_id) : bool {
        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
           
        // Check connection
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
        }

        $sql = "UPDATE USER SET seller_id='". $seller_id . "' WHERE id=". $user_id ."";
        
        if ($conn->query($sql) == true) {
            $value = true;
        } else {
            $value = false;
        }

        // Closes connection
        $conn->close();
        return $value;
   }

   function get_user_name($user_id) : string {
        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
            
        // Check connection
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
        }

        $sql = "SELECT first_name, last_name FROM USER WHERE id=" . $user_id;
        
        $result = $conn->query($sql);
        $fetched_data = $result->fetch_assoc();

        // Closes connection
        $conn->close();
        return $fetched_data['first_name'];
   }
?>