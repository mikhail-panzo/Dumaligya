<?php $id = $category['id'] ?>

<li>
    <span class="category-name"><?php echo $category['category_name'] ?></span><br><br>
    <?php echo "<button class=\"btn\" onclick=\"rename(".$id.")\">Rename</button>" ?>
    <form class="delete_form" action="../../controllers/categories_controller.php" onsubmit="return validateForm();" method="POST">
                <?php
                    echo "<input type=\"hidden\" name=\"category_id\" value=".$id.">";
                ?>
                <button class="btn" type="submit" name="delete_cat">Delete</button><br><br>
    </form>
</li>
<script>

        function validateForm() {
            return confirm("All products under this category will be deleted. Are you sure you want to delete this category?");
        }
</script>