<?php
    function insert_member($member_data) : int {
        $member = new Member();

        $member->user_address = $member_data['user_address'];
        $member->bio = $member_data['bio'];
        $member->profile_pic_url = $member_data['profile_pic_url'];

        return $member->insert_data();
    }
?>