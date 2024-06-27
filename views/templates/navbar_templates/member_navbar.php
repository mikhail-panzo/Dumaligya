<link rel="stylesheet" href='../styles/dropdown_styles.css'>
<section class="header">
        <a href="/"><img src="../images/logo.png" class="logo"></a> 
        
        <div>
            <ul class="navbar">
                <input type="search" id="search-bar" class="form-input-search">
                <button type="submit" id="search-button"><i class="fa-solid fa-magnifying-glass"></i></button>
                <li><a href="products">Products</a></li>
                <li><a href="sellers">Sellers</a></li>
                <li><a href="cart"><i class="fa-solid fa-cart-shopping"></i> Cart</a></li>
                <li><a href="orders"><i class="fa-solid fa-box-open"></i> Orders</a></li>
                <li>Welcome, <?php
                    if(isset($_SESSION['user_name']))
                        echo $_SESSION['user_name'];
                ?>
                <li class="dropdown_container">
                    <button onclick="trigger_dropdown()" class="fa-solid fa-circle-user fa-xl dropdown_button"></buton>
                    <div id="dropdown" class="dropdown_content">
                        <a href="account">My Account</a>
                        <a href="../../../controllers/logout_controller.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
</section>
<script>
    function trigger_dropdown() {
        document.getElementById("dropdown").style.display = "block";

        // Close the dropdown menu if the user clicks outside of it
        window.addEventListener('mouseup', close_dropdown);

    }
    
    function close_dropdown(event) {
        document.getElementById("dropdown").style.display = "none";
        window.removeEventListener('mouseup', close_dropdown);
    }
</script> 