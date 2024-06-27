<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- shared head code -->
    <?php require 'templates/head.php' ?>
    <!-- category data loading -->
    <?php
        require '../models/global_constants.php';

        if(!isset($_SESSION['seller_id'])) {
            require '../models/reset_to_guest.php';
        }

        if(!isset($_GET['product_number'])) {
            header('Location: /dashboard');
            exit();
        }

        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
                
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Load the product
        $sql = "SELECT * FROM PRODUCT WHERE id=" . $_GET['product_number'] ." AND seller_id=" . $_SESSION['seller_id'];
        $result = $conn->query($sql);
        $product = $result->fetch_assoc();

        // Closes connection
        $conn->close();

        if(empty($product)) {
            header('Location: /dashboard');
            exit();
        }
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
            <h2>EDIT PRODUCT</h2>
    <?php
        if(isset($_SESSION['edit_product_fail_message'])) {
            echo "<p class=\"error-message\">" . $_SESSION['edit_product_fail_message'] . "</p>";
            unset($_SESSION['edit_product_fail_message']);
        }
    ?>
    <form class="form grid" id="form" action="../../controllers/product_controller.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?php echo $product['id'] ?>">

        <label for="product_pic">Change Product Image:</label>
        <input type="file" id="product_pic" name="product_pic" accept="image/*">

        <label for="collection_mode">Collection Mode:</label>
        <select class="form-input-select" id="collection_mode" name="collection_mode" required>
            <option value="Delivery">Delivery</option>
            <option value="Pickup">Pickup</option>
        </select>

        <label for="price">Price:</label>
        <input class="form-input-number" type="number" step="0.01" id="price" name="price" min="0.01" value="<?php echo $product['price'] ?>" required>

        <label for="quantity">Quantity:</label>
        <input class="form-input-number" type="number" step="1" id="quantity" name="quantity"  min="0" value="<?php echo $product['quantity'] ?>" required>
        

        <label for="product_description">Product Description:</label>
        <textarea class="textarea" id="product_description" name="product_description" rows="4" cols="50" maxlength="1000"><?php echo $product['product_description'] ?></textarea>


        <button class=" btn btn-small" type="submit" name="edit_product">Edit Product</button>
    </form>
            </div>
        </div>
    </section>
    
    <script src="../scripts/unload_warning.js"></script>
</body>
</html>