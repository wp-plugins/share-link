<?php
add_filter('the_content','renderGraph3');
add_filter('the_content','renderGraph');

function sharelinkGenerateGraphEmbed($symbol = '', $version = '1') {
    $sharelink = new ShareLink();
    $details = $sharelink->getLicenseDetails();

    if ($symbol == "") {
        $symbol = $details["stock"];
    }

    $key = $details["license"];
    $url = SHARELINK_SOURCE.$key."/graph?version=" . $version;
    $url = $url."&symbol=".$symbol;

    $script = "<script type=\"text/javascript\" src=\"".$url."\"></script><div id=\"sharelink-graph-".$symbol."\" style=\"height: 200px;\"></div>";

    return $script;
}

function renderGraph($content) {
    $matches = Array();
    $pattern = "/\[sharelink-graph(.*)\]/";
    $match_count = preg_match_all($pattern, $content, $matches);

    if ($match_count != 0) {
        foreach ($matches[1] as $param) {
            $symbol = trim($param);

            $script = sharelinkGenerateGraphEmbed($symbol, '1');

            $replace = "[sharelink-graph".$param."]";
            $content = str_replace($replace,$script,$content);
        }

        return $content;
    } else {
        return $content;
    }
}

function renderGraph3($content) {
    $matches = Array();
    $pattern = "/\[sharelink-graph-3(.*)\]/";
    $match_count = preg_match_all($pattern, $content, $matches);

    if ($match_count != 0) {
        foreach ($matches[1] as $param) {
            $symbol = trim($param);

            $script = sharelinkGenerateGraphEmbed($symbol, '3');

            $replace = "[sharelink-graph-3".$param."]";
            $content = str_replace($replace,$script,$content);
        }

        return $content;
    } else {
        return $content;
    }
}

function shareShortcodeGraph($version, $atts) {
    $atts = shortcode_atts( array(
        'symbol' => ''
    ), $atts );

    $res = sharelinkGenerateGraphEmbed($atts['symbol'], $version);

    return $res;
}

function shareShortcodeGraph1($atts) {
    return shareShortcodeGraph('1', $atts);
}
function shareShortcodeGraph3($atts) {
    return shareShortcodeGraph('3', $atts);
}

add_shortcode( 'sharelink-graph', 'shareShortcodeGraph1' );
add_shortcode( 'sharelink-graph-3', 'shareShortcodeGraph3' );