<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- shared head code -->
    <?php require 'templates/head.php' ?>
    <!-- load product -->
    <?php
        require '../models/global_constants.php';
        require '../controllers/model_controllers/seller_model_controller.php';
        require '../controllers/model_controllers/product_model_controller.php';
        
        // if the product page was requested without product number
        if(!isset($_GET['product_number'])) {
            header('Location: /orders?type=Completed');
            exit();
        }

        if(!isset($_SESSION['member_id'])) {
            require '../models/reset_to_guest.php';
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

        // Load the review
        $sql = "SELECT * FROM REVIEW WHERE product_id=" . $_GET['product_number'] . " AND member_id=" . $_SESSION['member_id'];
        $result = $conn->query($sql);
        $review = $result->fetch_assoc();

        // Closes connection
        $conn->close();

        if(empty($fetched_data['product_name'])) {
            header('Location: /orders?type=Completed');
        }
        
    ?>
</head>

<body>

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

        <div class="details-price">
            <span class="product-price">&#8369; <?php echo $fetched_data['price'] ?></span>
        </div>

        <p class="short-desc"><?php echo $fetched_data['product_description'] ?></p>
    </div>
</section>

<!-- review -->
<section>
    <style>
        .rating {
        color: #ddd;
        }

        .star {
        cursor: pointer;
        }

        .selected {
        color: gold;
        }
    </style>
    <form method="post" action="../controllers/review_controller.php">
        <input type="hidden" name="product_id" value="<?php echo $fetched_data['id']?>">
        <input type="hidden" id="score" name="score" value="5">
        <div class="rating">
            <label>Set Rating:</label>
            <span class="star" onclick="setRating(1)"><i class="fas fa-star"></i></span>
            <span class="star" onclick="setRating(2)"><i class="fas fa-star"></i></span>
            <span class="star" onclick="setRating(3)"><i class="fas fa-star"></i></span>
            <span class="star" onclick="setRating(4)"><i class="fas fa-star"></i></span>
            <span class="star" onclick="setRating(5)"><i class="fas fa-star"></i></span>
        </div>

        <label for="message">Review:</label>
        <textarea id="message" name="review_message" maxlength="1000" required><?php if(!empty($review)) echo $review['review_message'] ?></textarea>
        <br>
        <button type="submit" name="<?php if(!empty($review)) echo 'update'; else echo 'add' ?>_review">
            <?php
                if(!empty($review))
                    echo 'Update Review';
                else
                    echo 'Add Review';
            ?>
        </button>
    </form>
</section>

<?php include 'templates/footer.php' ?>
<script>
    window.addEventListener("load", function() {
        setRating(<?php if(!empty($review)) echo $review['score']; else echo 5 ?>);
    });

    function setRating(rating) {
        const stars = document.getElementsByClassName('star');
        
        for (let i = 0; i < stars.length; i++) {
            if (i < rating) {
            stars[i].classList.add('selected');
            } else {
            stars[i].classList.remove('selected');
            }
        }
        
        document.getElementById('score').value = rating;
    }

    
</script>
</body>
</html>