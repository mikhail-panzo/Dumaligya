<section id="product1" class="section-p1">
    <div class="pro-container">
        <?php
            echo "<div class=\"pro\" onclick=\"open_product(" . $product['id'] . ")\">";
        ?>
            <?php
                echo "<img src=\"../" . $product['product_pic_url'] . "\">";
            ?>
            <div class="des">
                <span><?php
                    echo $product['product_type'] . " > " . get_category_name($product['category_id']);
                ?></span>
                <h5><?php echo $product['product_name'] ?></h5>
                <h4>&#8369 <?php echo $product['price'] ?></h4>
                <h5>Stock remaining: <?php echo $product['quantity'] ?></h5>
            </div>
        </div>
    </div>
</section>