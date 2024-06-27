<?php
    session_start();

    require '../models/global_constants.php';
    require '../models/product_model.php';
    require '../models/seller_model.php';

    if (isset($_POST['add_product'])) { // check if the controller is called from the add product page

        $product = new Product();
        if(!isset($_SESSION['user_name']) || !isset($_SESSION['seller_id'])) {
            require '../models/reset_to_guest.php';
        }

        // save the product picture
        $product->user_name = $_SESSION['user_name'];
        $file = $_FILES['product_pic'];
        $product_pic_url = $product->upload_product_pic($file, "../");
        $_SESSION['user_data']['product_pic_url'] = $product_pic_url;

        // save the rest of the data
        $product->product_type = $_POST['product_type'];
        $product->collection_mode = $_POST['collection_mode'];
        $product->price = $_POST['price'];
        $product->quantity = $_POST['quantity'];
        $product->product_name = $_POST['product_name'];
        $product->product_description = $_POST['product_description'];
        $product->seller_id = $_SESSION['seller_id'];
        $product->category_id = $_POST['category_id'];

        $value = $product->insert_data();

        if($value == true) {
            header('Location: /dashboard');
        } else {
            $_SESSION['add_product_fail_message'] = "Product not added successfully. Please try again.";
            header('Location: /add-product');
        }

        exit();
    } else if (isset($_POST['edit_product'])) {
        if(!isset($_SESSION['user_name']) || !isset($_SESSION['seller_id'])) {
            require '../models/reset_to_guest.php';
        }

        $product = new Product();

        // save the product picture
        if(!empty($_FILES['product_pic']['name'])) {
            $product->user_name = $_SESSION['user_name'];
            $file = $_FILES['product_pic'];
            $product_pic_url = $product->upload_product_pic($file, "../");
            $set_pic = "product_pic_url='" . $product_pic_url . "', ";
        } else {
            $set_pic = "";
        }

        // Create connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "UPDATE PRODUCT SET ".$set_pic." collection_mode='".$_POST['collection_mode']."', price=".$_POST['price'].", quantity=". $_POST['quantity'] .", product_description='".$_POST['product_description']."' WHERE id=" . $_POST['product_id'];
        
        $value = $conn->query($sql);

        $conn->close();

        if($value == true) {
            header('Location: /dashboard');
        } else {
            $_SESSION['edit_product_fail_message'] = "Product not editted successfully. Please try again.";
            header('Location: /edit-product?product_number=' . $_POST['product_id']);
        }
        exit();
    }
?>