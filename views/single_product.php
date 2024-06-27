<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- shared head code -->
    <?php require 'templates/head.php' ?>
    <!-- load product -->
    <?php
        require '../models/global_constants.php';
        require '../models/user_model.php';
        require '../models/member_model.php';
        require '../controllers/model_controllers/seller_model_controller.php';
        require '../controllers/model_controllers/product_model_controller.php';
        
        // if the product page was requested without product number
        if(!isset($_GET['product_number'])) {
            header('Location: /');
            exit();
        }

        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Load the product
        $sql = "SELECT * FROM PRODUCT WHERE id=" . $_GET['product_number'];
        $result = $conn->query($sql);
        $fetched_data = $result->fetch_assoc();
        
        if(empty($fetched_data['product_name'])) {
            $conn->close();
            header('Location: /products');
            exit();
        }

        // Load the reviews
        $sql = "SELECT * FROM REVIEW WHERE product_id=" . $_GET['product_number'];
        $result = $conn->query($sql);
        $reviews = $result->fetch_all(MYSQLI_ASSOC);

        $average_review = 0.0;
        $total_review = 0;

        if(!empty($reviews)){
            foreach($reviews as $review) {
                $average_review += $review['score'];
                $total_review ++;
            }
            $average_review = number_format(($average_review / $total_review), 1);
        }
        // Closes connection
        $conn->close();
    ?>
    <style>
        .review-rating {
        color: #ddd;
        }

        .star {
        cursor: pointer;
        }

        .selected {
        color: gold;
        }
    </style>
</head>

<body>
<script>
    function setRating(rating, class_number) {
        const stars = document.getElementsByClassName('star' + class_number);
        
        for (let i = 0; i < stars.length; i++) {
            if (i < rating) {
            stars[i].classList.add('selected');
            } else {
            stars[i].classList.remove('selected');
            }
        }
    }
</script>

<!-- navbar -->
<?php require 'templates/navbar.php' ?>

<!-- breadcrumb -->
<section>
    <a href="products">Products</a> >
    <span id="breadcrumb-link"><?php echo $fetched_data['product_type'] ?></span> >
    <span id="breadcrumb-link"><?php echo get_category_name($fetched_data['category_id']) ?></span>
</section>

<!-- product details -->
<section id="product-details" class="section-p1">
    <div class="single-pro-img">
        <?php
            echo "<img src=\"../" . $fetched_data['product_pic_url'] . "\" width=\"100%\" id=\"mainImg\">";
        ?>
    </div>

    <div class="single-pro-details">
        <h3 class="details-title"><?php echo $fetched_data['product_name'] ?></h3>

        <p class="details-brand">Seller: <span>
            <?php echo get_seller_name($fetched_data['seller_id']) ?>
        </span></p>
        <p class="details-brand"><span><?php echo $fetched_data['collection_mode'] ?></span></p>
        <div class="review-star-count">
            <div class="review-rating">
                <span class="star0"><i class="fas fa-star fa-xl"></i></span>
                <span class="star0"><i class="fas fa-star fa-xl"></i></span>
                <span class="star0"><i class="fas fa-star fa-xl"></i></span>
                <span class="star0"><i class="fas fa-star fa-xl"></i></span>
                <span class="star0"><i class="fas fa-star fa-xl"></i></span>
            </div>
            <script>
                window.addEventListener("load", function() {
                    setRating(<?php echo number_format($average_review) ?>,0);
                });
            </script>

            <div class="review-count">
                <span>(<?php echo $average_review ?>)</span>

                <span><?php echo $total_review ?> review<?php if($total_review > 1) echo 's' ?></span>
            </div>
        </div>

        <div class="details-price">
            <span class="product-price">&#8369; <?php echo $fetched_data['price'] ?></span>
        </div>

        <p class="short-desc"><?php echo $fetched_data['product_description'] ?></p>

        <ul class="product-list">
            <?php
                if($fetched_data['quantity'] == 0) {
                    require 'templates/purchase_action_blocked_out_of_stock.php';
                } else if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'Member') {
                    require 'templates/purchase_action.php';
                } else {
                    require 'templates/purchase_action_blocked.php';
                }
            ?>
            

            <ul class="details-meta">
                <li class="meta-list"><span><?php echo $fetched_data['quantity'] ?> left in stock</span></li>
            </ul>
        </ul>
    </div>
</section>

<!-- reviews -->
<section class="details-review">
    <span class="details-tab section-p1">Reviews (<?php echo $total_review ?>)</span>
    
    <div class="details-tab-content section-p1">
        <div class="reviews-container grid">
            <?php
                $count = 0;
                foreach($reviews as $review){
                    $count++;
                    require 'templates/review_card.php';
                }
            ?>
        </div>
    </div>
   
</section>

<?php include 'templates/footer.php' ?>
</body>
</html>