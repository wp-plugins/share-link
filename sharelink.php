<?php

/*
  Plugin Name: Share Link
  Plugin URI: http://www.sharelink.com.au
  Description: Share Link wordpress plugin
  Author: Harmonic New Media
  Version: 1.2.1
  Author URI: http://www.harmonicnewmedia.com
 */

define("SHARELINK_SOURCE", "http://data.sharelink.com.au/");

include("classes/common.php");
include("classes/sharelink.php");

include("sharelink-download.php");

$sharelink = new ShareLink();

/**
 * Install Share Link to wordpress
 */
function installShareLink() {
    global $wpdb;

    $settings_table = "create table if not exists " . $wpdb->prefix . "sharelink_settings (id int not null auto_increment, stock char(3), license char(32), level char(10), status int, primary key(id))";
    $options_table = "create table if not exists " . $wpdb->prefix . "sharelink_options (id int not null auto_increment, widgetlimit int, widgetdate char(10), byyear int, bymonth int, dateformat varchar(20), pagination int, perpage int, monthheader varchar(20), display int, primary key(id))";
    $documents_table = "create table if not exists " . $wpdb->prefix . "sharelink (id int not null auto_increment, created timestamp not null default current_timestamp, date datetime, title varchar(200), file varchar(200), primary key(id))";

    $wpdb->query($settings_table);
    $wpdb->query($options_table);
    $wpdb->query($documents_table);

    $directory = WP_CONTENT_DIR . "/sharelink";
    if (!file_exists($directory)) {
        mkdir($directory,0755);
    }
}

register_activation_hook(__FILE__, 'installShareLink');

if (is_admin()) {

    // Install the plugin from form
    if (isset($_POST["install-plugin"])) {
        $license = addslashes($_POST["license-key"]);

        if ($license == "" || strlen($license) != 32) {
            echo "<div class=\"error\">Your license key is not valid, please try again</div>";
        } else {
            $url = SHARELINK_SOURCE . $license . "/verify";

            $result = file_get_contents($url);

            if ($result === false) {
                echo "<div class=\"error\">There was an error checking the license. Please try again later.</div>";
            } else {
                $json = json_decode($result);
                $level = "";

                if (isset($json->has_announcements)) {
                    $level = "bronze";
                } else if (isset($json->has_graph) && !isset($json->has_announcements)) {
                    $level = "silver";
                } else if (!isset($json->has_graph) && !isset($json->has_announcements) && isset($json->has_share)) {
                    $level = "gold";
                }

                installShareLink();

                $wpdb->query("insert into ".$wpdb->prefix."sharelink_settings (stock,license,level,status) values ('".$json->symbol."','".$license."','".$level."',1)");
            }
        }
    }

    // Checks if the plugin is installed
    function is_installed() {
        global $wpdb;

        $wpdb->hide_errors();
        $num = $wpdb->get_var("select count(*) from ".$wpdb->prefix."sharelink_settings limit 1");
        $wpdb->show_errors();

        return $num != 0;
    }

    function sharelink_launch() {
        add_menu_page('Share Link', 'Share Link', 'administrator', 'sharelink-admin.php', 'sharelink_admin', plugins_url('images/icon-sharelink-16px-colour.png', __FILE__));
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
        wp_register_style('admin-style', plugins_url('css/sharelink-admin.css', __FILE__));
        wp_enqueue_style('admin-style');
    }

    add_action('admin_head', 'admin_register_head');
} else {
    include("frontend/announcements.php");
    include("frontend/price-graph.php");
    include("frontend/price-html.php");
}

include("widgets/widget.php");
