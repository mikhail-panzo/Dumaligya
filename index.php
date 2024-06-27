<?php
    session_start();

    // start the session with a guest user if user type is not set
    if(empty($_SESSION["user_type"])){
        $_SESSION["user_type"] = "Guest"; 
    }

    // if the user is a guest, load product php
    if($_SESSION["user_type"] == "Guest") {
        header("Location: /products");
        exit();
    }

    // if the user is a seller, set cookie for seller pages
    if($_SESSION["user_type"] == "Seller") {
        setcookie("user_type", "seller", 0, "/");
        header("Location: /dashboard");
    } else {
        setcookie("user_type", "member", 0, "/");
        header("Location: /products");
    }
?>