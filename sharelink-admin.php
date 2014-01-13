<div class="wrap sharelink">
    <h2><img src="<?php echo plugins_url('images/icon-sharelink-32px.png', __FILE__); ?>" /> Share Link for Wordpress</h2>
    <div class="clear"></div>
    <?php
        global $wpdb;

        if (isset($_POST["update"])) {
            $display = $_POST["display"];
            $dateformat = $_POST["dateformat"];
            $byyear = $_POST["byyear"];
            $bymonth = $_POST["bymonth"];
            $monthheader = $_POST["monthheader"];
            $pagination = $_POST["pagination"];
            $perpage = $_POST["perpage"];
            $widgetdate = $_POST["widgetdate"];
            $widgetlimit = $_POST["widgetlimit"];

            $check = mysql_query("select * from ".$wpdb->prefix."sharelink_options");
	    $result = mysql_num_rows($check);
            if ($result == 0) {
		mysql_query("insert into ".$wpdb->prefix."sharelink_options (id) values (1)");
	    }
            mysql_query("update ".$wpdb->prefix."sharelink_options set widgetlimit = '".$widgetlimit."', widgetdate = '".$widgetdate."', byyear = '".$byyear."', dateformat = '".$dateformat."', bymonth = '".$bymonth."', display = '".$display."', monthheader = '".$monthheader."', perpage = '".$perpage."', pagination = '".$pagination."'");

echo mysql_error();

            echo "<div class=\"updated\">Settings have been saved</div>";
        }

        $get = mysql_query("select * from ".$wpdb->prefix."sharelink_options limit 1");
        $num = mysql_num_rows($get);

        if ($num == 0) {
            mysql_query("insert into ".$wpdb->prefix."sharelink_options (widgetlimit,widgetdate,byyear,bymonth,display,pagination,monthheader,dateformat,perpage) values (3,'d/m/Y',0,0,0,0,0,'d/m/Y',10)");
            $get = mysql_query("select * from ".$wpdb->prefix."sharelink_options limit 1");
        }

        $result = mysql_fetch_array($get);

    ?>

    <form action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="post">
        <input type="hidden" name="update" />

        <?php if (!is_writable(WP_CONTENT_DIR)) { ?>
            <div class="error">Wordpress Content directory is not writable</div>
        <?php } ?>

        <?php if (!is_writable(WP_CONTENT_DIR."/sharelink")) { ?>
            <div class="error">Sharelink Content directory is not writable</div>
        <?php } ?>

        <fieldset>
            <legend>Display Settings</legend>

            <div class="form-header">
                This page helps you set up the way Share Link will present ASX Announcements on your website.<br /><br />
                Modify the options below to best suit your design, then add CSS styles to your template to customize further.
            </div>
            <label>Display Type</label>
            <select name="display" size="1">
                <option value="0" <?php if ($result["display"] == 0) { ?>selected="selected"<?php } ?>>Table Structure</option>
                <option value="1" <?php if ($result["display"] == 1) { ?>selected="selected"<?php } ?>>Unordered List</option>

            </select> <span>Should the ASX announcements be display in a table or unordered list?</span><br />

            <label>Date Format</label>
            <select name="dateformat" size="1">
                <option value="d/m/Y" <?php if ($result["dateformat"] == "d/m/Y") { ?>selected="selected"<?php } ?>><?php echo date("d/m/Y"); ?></option>
                <option value="d/m/y" <?php if ($result["dateformat"] == "d/m/y") { ?>selected="selected"<?php } ?>><?php echo date("d/m/y"); ?></option>
                <option value="d M Y" <?php if ($result["dateformat"] == "d M Y") { ?>selected="selected"<?php } ?>><?php echo date("d M Y"); ?></option>
                <option value="d F Y" <?php if ($result["dateformat"] == "d F Y") { ?>selected="selected"<?php } ?>><?php echo date("d F Y"); ?></option>
                <option value="jS F Y" <?php if ($result["dateformat"] == "jS F Y") { ?>selected="selected"<?php } ?>><?php echo date("jS F Y"); ?></option>
                <option value="jS M Y" <?php if ($result["dateformat"] == "jS M Y") { ?>selected="selected"<?php } ?>><?php echo date("jS M Y"); ?></option>
                <option value="l, jS F Y" <?php if ($result["dateformat"] == "l, jS F Y") { ?>selected="selected"<?php } ?>><?php echo date("l, jS F Y"); ?></option>
            </select><br />

            <label>Widget Date Format</label>
            <select name="widgetdate" size="1">
                <option value="d/m/Y" <?php if ($result["widgetdate"] == "d/m/Y") { ?>selected="selected"<?php } ?>><?php echo date("d/m/Y"); ?></option>
                <option value="d/m/y" <?php if ($result["widgetdate"] == "d/m/y") { ?>selected="selected"<?php } ?>><?php echo date("d/m/y"); ?></option>
                <option value="d M Y" <?php if ($result["widgetdate"] == "d M Y") { ?>selected="selected"<?php } ?>><?php echo date("d M Y"); ?></option>
                <option value="d F Y" <?php if ($result["widgetdate"] == "d F Y") { ?>selected="selected"<?php } ?>><?php echo date("d F Y"); ?></option>
                <option value="jS F Y" <?php if ($result["widgetdate"] == "jS F Y") { ?>selected="selected"<?php } ?>><?php echo date("jS F Y"); ?></option>
                <option value="jS M Y" <?php if ($result["widgetdate"] == "jS M Y") { ?>selected="selected"<?php } ?>><?php echo date("jS M Y"); ?></option>
                <option value="l, jS F Y" <?php if ($result["widgetdate"] == "l, jS F Y") { ?>selected="selected"<?php } ?>><?php echo date("l, jS F Y"); ?></option>
            </select><br />

            <label>Display Announcements By Year</label>
            <select name="byyear" size="1">
                <option value="0" <?php if ($result["byyear"] == 0) { ?>selected="selected"<?php } ?>>No</option>
                <option value="1" <?php if ($result["byyear"] == 1) { ?>selected="selected"<?php } ?>>Yes</option>
            </select> <span>Allow user to group/filter announcements by year</span><br />

            <label>Display By Month</label>
            <select name="bymonth" size="1">
                <option value="0" <?php if ($result["bymonth"] == 0) { ?>selected="selected"<?php } ?>>No</option>
                <option value="1" <?php if ($result["bymonth"] == 1) { ?>selected="selected"<?php } ?>>Yes</option>
            </select> <span>Allow user to group/filter announcements by month</span><br />

            <label>Display By Month Header</label>
            <select name="monthheader" size="1">
                <option value="F Y" <?php if ($result["monthheader"] == "F Y") { ?>selected="selected"<?php } ?>><?php echo date("F Y"); ?></option>
                <option value="F y" <?php if ($result["monthheader"] == "F y") { ?>selected="selected"<?php } ?>><?php echo date("F y"); ?></option>
                <option value="M Y" <?php if ($result["monthheader"] == "M Y") { ?>selected="selected"<?php } ?>><?php echo date("M Y"); ?></option>
                <option value="F" <?php if ($result["monthheader"] == "F") { ?>selected="selected"<?php } ?>><?php echo date("F"); ?></option>
                <option value="M" <?php if ($result["monthheader"] == "M") { ?>selected="selected"<?php } ?>><?php echo date("M"); ?></option>
            </select><br />

            <label>Include Pagination</label>
            <select name="pagination" size="1">
                <option value="0" <?php if ($result["pagination"] == 0) { ?>selected="selected"<?php } ?>>No</option>
                <option value="1" <?php if ($result["pagination"] == 1) { ?>selected="selected"<?php } ?>>Yes</option>
            </select> <span>Seperate announcements into multiple pages (works by year, by month and number of announcements)</span><br />

            <label>Announcements Per Page</label>
            <input type="text" name="perpage" size="5" value="<?php echo $result["perpage"]; ?>" /><span>The number of announcements to display per page</span><br />

            <label>Announcements Per Widget</label>
            <input type="text" name="widgetlimit" size="5" value="<?php echo $result["widgetlimit"]; ?>" /><span>The number of announcements to display on the widget</span><br />
        </fieldset>

        <input type="submit" value="Update Settings" class="button-primary" />

    </form>
</div>

