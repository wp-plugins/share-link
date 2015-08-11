<?php
add_filter('the_content','renderHtml');

function shareLinkGenerateHtmlEmbed($type, $div = '') {
    $sharelink = new ShareLink();
    $details = $sharelink->getLicenseDetails();
    $key = $details["license"];

    if (empty($div)) {
        $div = "sharelink-" . $type;
    }

    $url = SHARELINK_SOURCE.$key."/html?type=".$type;
    $url = $url."&div=".$div;

    $script = "<script type=\"text/javascript\" src=\"".$url."\"></script><div id=\"".$div."\"></div>";

    return $script;
}

function renderHtml($content) {
    $matches = Array();
    $pattern = "/\[sharelink-(.*)\]/";
    $match_count = preg_match_all($pattern, $content, $matches);

    if ($match_count != 0) {
        foreach ($matches[1] as $param) {
            if ($param == "graph" || $param == "graph-3" || $param == "asx") {
                continue;
            }

            $items = trim($param);

            if (strpos($items," ") !== false) {
                list($type,$div) = explode(" ",$details);
            } else {
                $type = $items;
            }

            if (!isset($div)) {
                $div = '';
            }

            $script = shareLinkGenerateHtmlEmbed($type, $div);
            $replace = "[sharelink-".$param."]";
            $content = str_replace($replace,$script,$content);
            unset($div);
        }

        return $content;
    } else {
        return $content;
    }
}

function shareShortcodeHtml($type, $atts) {
    $atts = shortcode_atts( array(
        'div' => '',
    ), $atts );

    $res = shareLinkGenerateHtmlEmbed($type, $atts['div']);

    return $res;
}

function shareShortcodeHtmlBox($atts) {
    return shareShortcodeHtml('box', $atts);
}
function shareShortcodeHtmlStrip($atts) {
    return shareShortcodeHtml('strip', $atts);
}
function shareShortcodeHtmlTable($atts) {
    return shareShortcodeHtml('table', $atts);
}

add_shortcode( 'sharelink-box', 'shareShortcodeHtmlBox' );
add_shortcode( 'sharelink-strip', 'shareShortcodeHtmlStrip' );
add_shortcode( 'sharelink-table', 'shareShortcodeHtmlTable' );

