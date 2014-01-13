<?php

/*
  Plugin Name: Share Link
  Plugin URI: http://www.sharelink.com.au
  Description: Share Link wordpress plugin
  Author: Harmonic New Media
  Version: 1.1
  Author URI: http://www.harmonicnewmedia.com
 */

include("classes/common.php");
include("classes/sharelink.php");
$sharelink = new ShareLink();

$source = "http://data.sharelink.com.au/";

/**
 * Install Share Link to wordpress
 */
function installShareLink() {
    global $wpdb;
    mysql_query("drop table ".$wpdb->prefix."sharelink_settings");
    mysql_query("drop table ".$wpdb->prefix."sharelink_options");
    mysql_query("drop table ".$wpdb->prefix."sharelink");

    $settings_table = "create table if not exists " . $wpdb->prefix . "sharelink_settings (id int not null auto_increment, stock char(3), license char(32), level char(10), status int, primary key(id))";
    $options_table = "create table if not exists " . $wpdb->prefix . "sharelink_options (id int not null auto_increment, widgetlimit int, widgetdate char(10), byyear int, bymonth int, dateformat varchar(20), pagination int, perpage int, monthheader varchar(20), display int, primary key(id))";
    $documents_table = "create table if not exists " . $wpdb->prefix . "sharelink (id int not null auto_increment, date datetime, title varchar(200), file varchar(200), primary key(id))";

    mysql_query($settings_table);
    mysql_query($options_table);
    mysql_query($documents_table);
}

register_activation_hook(__FILE__, 'installShareLink');

if (is_admin()) {

    // Install the plugin from form
    if (isset($_POST["install-plugin"])) {
        $license = addslashes($_POST["license-key"]);

        if ($license == "" || strlen($license) != 32) {
            echo "<div class=\"error\">Your license key is not valid, please try again</div>";
        } else {
            $url = $source . $license . "/verify";

            $result = file_get_contents($url);

            $json = json_decode($result);
            $level = "";

            if (isset($json->has_announcements)) {
                $level = "bronze";
            } else if (isset($json->has_graph) && !isset($json->has_announcements)) {
                $level = "silver";
            } else if (!isset($json->has_graph) && !isset($json->has_announcements) && isset($json->has_share)) {
                $level = "gold";
            }

            mysql_query("insert into ".$wpdb->prefix."sharelink_settings (stock,license,level,status) values ('".$json->symbol."','".$license."','".$level."',1)");
        }
    }

    // Checks if the plugin is installed
    function is_installed() {
        global $wpdb;

        $num = 0;
        $get = mysql_query("select id from ".$wpdb->prefix."sharelink_settings limit 1");
        if ($get !== false) {
            $num = mysql_num_rows($get);
        }

        if ($num == 0) {
            return false;
        } else {
            return true;
        }
    }

    function sharelink_launch() {
        add_menu_page('Share Link', 'Share Link', '10', 'sharelink-admin.php', 'sharelink_admin', plugins_url('images/icon-sharelink-16px-colour.png', __FILE__));
        if (is_installed()) {
            add_submenu_page('sharelink-admin.php', 'License', 'License', 'administrator', 'license', 'sharelink_license');
            add_submenu_page('sharelink-admin.php', 'Help', 'Help', 'administrator', "help", 'sharelink_help');
        }
    }

    function sharelink_admin() {
        if (is_installed()) {
            include("sharelink-admin.php");
        } else {
            include("sharelink-install.php");
        }
    }

    function sharelink_license() {
        include("sharelink-license.php");
    }

    function sharelink_help() {
        include("sharelink-help.php");
    }

    add_action('admin_menu', 'sharelink_launch');

    function admin_register_head() {
        echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . plugins_url('css/sharelink-admin.css', __FILE__) . "\" />";
    }

    add_action('admin_head', 'admin_register_head');
} else {
    include("frontend/announcements.php");
    include("frontend/price-graph.php");
    include("frontend/price-html.php");
}

include("widgets/widget.php");
