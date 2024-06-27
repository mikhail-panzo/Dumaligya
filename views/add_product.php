<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- shared head code -->
    <?php require 'templates/head.php' ?>
    <!-- category data loading -->
    <?php
        require '../models/global_constants.php';

        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Adds the new category
        $sql = "SELECT id, category_name FROM CATEGORY WHERE seller_id=" . $_SESSION['seller_id'] . " ORDER BY category_name";
        
        $result = $conn->query($sql);
        $fetched_data = $result->fetch_all(MYSQLI_ASSOC);

        if(empty($fetched_data)) { // If there are no categories
            $_SESSION['no_category_fail_message'] = "Add at least one category first";
            header("Location: /categories");
            exit();
        }

        // Closes connection
        $conn->close();
    ?>
    <style>        
        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <!-- navbar -->
    <?php require 'templates/navbar.php' ?>

    <section class="register section--lg">
        <div class="register-seller-container container grid">
            <div class="seller-register">
            <h2>ADD PRODUCT</h2>
    <?php
        if(isset($_SESSION['add_product_fail_message'])) {
            echo "<p class=\"error-message\">" . $_SESSION['add_product_fail_message'] . "</p>";
            unset($_SESSION['add_product_fail_message']);
        }
    ?>
    <form class="form grid" id="form" action="../../controllers/product_controller.php" method="POST" enctype="multipart/form-data">
        <label for="product_pic">Product Image:</label>
        <input type="file" id="product_pic" name="product_pic" accept="image/*" required>

        <label for="product_type">Product Type:</label>
        <select class="form-input-select" id="product_type" name="product_type" required>
            <option value="Apparel">Apparel</option>
            <option value="Food and Beverages">Food and Beverages</option>
            <option value="Others">Others</option>
        </select>

        <label for="category">Category:</label>
        <select class="form-input-select" id="category" name="category_id" required>
            <?php
                foreach($fetched_data as $category) {
                    echo "<option value='" . $category['id'] . "'>";
                    echo $category['category_name'];
                    echo "</option>";
                }
            ?>
        </select>

        <label for="collection_mode">Collection Mode:</label>
        <select class="form-input-select" id="collection_mode" name="collection_mode" required>
            <option value="Delivery">Delivery</option>
            <option value="Pickup">Pickup</option>
        </select>

        <label for="price">Price:</label>
        <input class="form-input-number" type="number" step="0.01" id="price" name="price" min="0.01" required>

        <label for="quantity">Quantity:</label>
        <input class="form-input-number" type="number" step="1" id="quantity" name="quantity"  min="0" required>
        
        <label for="product_name">Product Name:</label>
        <input class="form-input-text" type="text" id="product_name" name="product_name" maxlength="255" required>

        <label for="product_description">Product Description:</label>
        <textarea class="textarea" id="product_description" name="product_description" rows="4" cols="50" maxlength="1000"></textarea>


        <button class=" btn btn-small" type="submit" name="add_product">Add Product</button>
    </form>
            </div>
        </div>
    </section>
    
    <script src="../scripts/unload_warning.js"></script>
</body>
</html>