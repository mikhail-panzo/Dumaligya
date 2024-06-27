<form class="details-action action" action="../controllers/cart_controller.php" method="POST">
    <?php
        echo "<input type='hidden' name='product_id' value='" . $_GET['product_number'] . "'>";
        echo "<input type='number' name='quantity' class='quantity' step='1' value='1' min='1' max='" . $fetched_data['quantity'] . "'>";
    ?>
    <button type="submit" class="normal" name="add_cart">Add to Cart</button>
    <button type="submit" class="normal" name="buy_now">Buy Now</button>
</form>
<?php
    if(isset($_SESSION['add_cart_message'])) {
        echo "<p>" . $_SESSION['add_cart_message'] . "</p>";
        unset($_SESSION['add_cart_message']);
    }
?>