<?php
    // this php file is for dev mode, resets all mutable global variables
    session_start();
    session_unset();
    setcookie("register_progress", 0, time() - 1, "/register-user");
    setcookie("user_type", "daf", time() - 1, "/");
?>

all mutable global variables reset