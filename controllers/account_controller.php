<?php
    require '../models/global_constants.php';
    require '../models/member_model.php';

    session_start();

    if(isset($_POST['member_reedit'])) { // if this controller is called from reedit in member account
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Check if user and member sessions are available
        if(isset($_SESSION['member_id']) && isset($_SESSION['user_id'])) {
            $member_id = $_SESSION['member_id'];
            $user_id = $_SESSION['user_id'];
        } else {
            include '../models/reset_to_guest.php';
        }

        $picture = $_FILES['profile_pic'];
        if(!empty($picture['name'])) {
            $member = new Member();
            $member->user_name = $_SESSION['user_name'];

            $profile_pic_url = $member->upload_profile_pic($picture, "../");

            $sql = "UPDATE MEMBER_USER SET profile_pic_url='" . $profile_pic_url . "' WHERE id=" . $member_id;
            $conn->query($sql);
        }

        // Update User
        $sql = "UPDATE USER SET first_name = '" . $_POST['first_name'] . "', last_name = '" . $_POST['last_name'] . "', email = '" . $_POST['email'] . "',contact_number = '" . $_POST['contact_number'] . "',birth_date = '" . $_POST['birth_date'] . "', sex = '" . $_POST['sex'] . "'
        WHERE id=" . $user_id;
        
        $conn->query($sql);

        // Update Member
        $sql = "UPDATE MEMBER_USER SET user_address = '" . $_POST['user_address'] . "', bio = '" . $_POST['bio'] . "' WHERE id=" . $member_id;
        
        $conn->query($sql);

        // Closes connection
        $conn->close();

        header("Location: /account");
    } else if(isset($_POST['cancel'])) { //if the cancel button was pressed
        header("Location: /account");
    }
?>