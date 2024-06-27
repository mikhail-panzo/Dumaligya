<section class="sellers section-p1 container">
    <?php
        echo "<div onclick=\"open_seller(" . $seller['id'] . ")\" style=\"margin: 20px\">";
    ?>
        <table class="seller-container">
            <tr>
                <td width="20%">
                    <?php
                        echo "<img class=\"seller-img\" src=\"../" . $seller['profile_pic_url'] . "\" width='100%'>";
                    ?>
                </td>
                <td>
                    <h2><?php echo get_seller_name($seller['id']) ?></h2>
                    <span>Pickup Location:</span><h3> <?php echo $seller['pickup_location'] ?></h3><br>
                    <span>Description: </span><h3> <?php echo $seller['seller_description'] ?></h3>
                    <br>
                    <span>Schedule:</span><h3><?php echo $seller['seller_schedule'] ?></h3>
                </td>
            </tr>
        </table>
    </div>
</section>