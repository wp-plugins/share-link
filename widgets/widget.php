<?php

/**
 * Plugin Widget Structure
 *
 * @version 1.0
 * @author Harmonic New Media
 */
class SharelinkWidget extends WP_Widget {

    /**
     * Initiate the plugin widget
     */
    function SharelinkWidget() {
        //parent::WP_Widget(false, $name = 'Share Link' );
        $widget_ops = array('classname' => 'Share Link', 'description' => __("A summary list of most recent ASX Announcements"));
        $this->WP_Widget('sharelink', __('Share Link'), $widget_ops);
    }

    /**
     * Create the widget form
     *
     * @param object $instance
     */
    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => ''));
        $title = $instance['title'];

        // echo $form;
    }

    /**
     * Update form parameters
     *
     * @param object $new_instance
     * @param object $old_instance
     * @return object
     */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        return $instance;
    }

    /**
     * Build the widget on the front end
     *
     * @param array $args
     * @param object $instance
     */
    function widget($args, $instance) {
        extract($args, EXTR_SKIP);

        echo $before_widget;

        if (!isset($content)) {
            $content = "";
        }
        $content .= "<div class=\"sl-announcements-widget\"><ul>";


        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);

        if (empty($title)) {
            $title = 3;
        }


        global $wpdb;
        $options = $wpdb->get_row("select * from ".$wpdb->prefix."sharelink_options limit 1", ARRAY_A);

        $results = $wpdb->get_results("select * from " . $wpdb->prefix . "sharelink order by date desc, id desc limit ".$options["widgetlimit"], ARRAY_A);

        foreach ($results as $result) {
            $content .= "<li>";
            $content .= "<span class=\"sl-date\">" . date($options["widgetdate"], strtotime($result["date"])) . "</span> ";
            $content .= "<span class=\"sl-title\"><a target=\"_new\" href=\"" . WP_CONTENT_URL . "/sharelink/" . $result["file"] . "\">" . $result["title"] . "</a></span>";
            $content .= "</li>";
        }

        $content .= "</ul>";
        $content .= "</div>";

        echo $content;

        echo $after_widget;
    }

}

// Register widget
add_action('widgets_init', create_function('', 'return register_widget("SharelinkWidget");'));
