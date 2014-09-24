<div class="wrap sharelink">
    <h2><img src="<?php echo plugins_url('images/icon-sharelink-32px.png', __FILE__); ?>" /> Share Link for Wordpress > License Information</h2>
    <div class="clear"></div>
    <p>If you move servers or change domain names please <a href="http://harmonicnewmedia.com" target="_blank">contact us</a> to organise an updated license key.</p>
        <fieldset>
            <legend>License Details</legend>
            <?php
                global $wpdb;
                $result = $wpdb->get_row("select * from ".$wpdb->prefix."sharelink_settings limit 1", ARRAY_A);
            ?>
            <label>License Key</label><span><?php echo $result["license"]; ?></span><br />
        </fieldset>

</div>