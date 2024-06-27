<?php
    session_unset();
    $_SESSION['user_type'] = "Guest";
    setcookie("user_type", "0", time() - 1, "/");
    header("Location: /");
    exit();
?>