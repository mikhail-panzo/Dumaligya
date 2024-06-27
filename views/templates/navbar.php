<?php
    if(!isset($_SESSION['user_type'])) {
        require '../models/reset_to_guest.php';
    } else {
        $user_type = $_SESSION['user_type'];
        if($user_type == "Seller") {
            include 'navbar_templates/seller_navbar.php';
        } else if($user_type == "Member") { 
            include 'navbar_templates/member_navbar.php';
        } else {
            include 'navbar_templates/guest_navbar.php';
        }
    }
?>