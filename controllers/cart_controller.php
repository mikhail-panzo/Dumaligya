<?php
    require '../models/global_constants.php';
    require '../controllers/model_controllers/product_model_controller.php';
    require '../controllers/model_controllers/seller_model_controller.php';
    require '../controllers/model_controllers/checkout_model_controller.php';
    require '../models/member_model.php';
    require '../models/order_model.php';

    session_start();

    if(isset($_POST['add_cart'])) { // if controller was called from cart
        // search for existing order with the same seller and collection mode, and is at cart
        $order = new Order();
        if(isset($_SESSION['member_id']) && isset($_SESSION['user_id'])) {
            $order->member_id = $_SESSION['member_id'];
        } else {
            require '../models/reset_to_guest.php';
        }
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $order->seller_id = get_seller_id_from_product($product_id);
        $order->collection_mode = get_product_collection_mode($product_id);
        if($order->collection_mode == 'Delivery') {
            $member = new Member();
            $member->get_data($_SESSION['member_id']);
            $order->pickup_location = $member->user_address;
        } else {
            $order->pickup_location = get_pickup_location($order->seller_id);
        }
        
        $order_id = $order->assign_cart();
        checkout_items($order_id, $product_id, $quantity);

        $_SESSION['add_cart_message'] = "Added to Cart";
        header('Location: /product?product_number=' . $product_id);
        exit();
    } else if(isset($_POST['buy_now'])) { // buy now
        // search for existing order with the same seller and collection mode, and is at cart
        $order = new Order();
        if(isset($_SESSION['member_id']) && isset($_SESSION['user_id'])) {
            $order->member_id = $_SESSION['member_id'];
        } else {
            require '../models/reset_to_guest.php';
        }
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $order->seller_id = get_seller_id_from_product($product_id);
        $order->collection_mode = get_product_collection_mode($product_id);
        if($order->collection_mode == 'Delivery') {
            $member = new Member();
            $member->get_data($_SESSION['member_id']);
            $order->pickup_location = $member->user_address;
        } else {
            $order->pickup_location = get_pickup_location($order->seller_id);
        }

        $order_id = $order->assign_cart();
        checkout_items($order_id, $product_id, $quantity);

        header('Location: /checkout?order_number=' . $order_id);
        exit();
    }
?>