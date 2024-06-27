<?php
    $user = new User();
    $member = new Member();
    $user->get_data_by_member_id($review['member_id']);
    $member->get_data($review['member_id']);
    $user_name = $user->first_name . " " . $user->last_name;
?>
<div class="review-single">
    <div>
        <img src="../<?php echo $member->profile_pic_url?>" alt="" class="review-img">
        <h4 class="review-name"><?php echo $user_name ?></h4>
    </div>

    <div class="review-data">
        <div class="review-rating">
            <span class="star<?php echo $count ?>"><i class="fas fa-star fa-xl"></i></span>
            <span class="star<?php echo $count ?>"><i class="fas fa-star fa-xl"></i></span>
            <span class="star<?php echo $count ?>"><i class="fas fa-star fa-xl"></i></span>
            <span class="star<?php echo $count ?>"><i class="fas fa-star fa-xl"></i></span>
            <span class="star<?php echo $count ?>"><i class="fas fa-star fa-xl"></i></span>
        </div>
        <script>
            window.addEventListener("load", function() {
                setRating(<?php echo $review['score'] ?>, <?php echo $count ?>);
            });
        </script>
        <p class="review-desc"><?php echo $review['review_message'] ?></p>

        <span class="review-date"><?php
            $reviewDateTime = new DateTime($review['review_date_time']);
            $formattedDateTime = $reviewDateTime->format('l - F j, Y (g:i A)');
            echo $formattedDateTime;
        ?></span>
    </div>
</div>