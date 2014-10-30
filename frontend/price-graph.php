<?php
add_filter('the_content','renderGraph3');
add_filter('the_content','renderGraph');

function renderGraph($content) {
    $sharelink = new ShareLink();
    $details = $sharelink->getLicenseDetails();

    $key = $details["license"];
    $url = SHARELINK_SOURCE.$key."/graph?version=1";

    $matches = Array();
    $pattern = "/\[sharelink-graph(.*)\]/";
    $match_count = preg_match($pattern, $content, $matches);

    if ($match_count != 0) {
        $param = $matches[1];
        $symbol = trim($matches[1]);

        if ($symbol == "") {
            $symbol = $details["symbol"];
        }

        $url = $url."&symbol=".$symbol;

        $script = "<script type=\"text/javascript\" src=\"".$url."\"></script><div id=\"sharelink-graph-".$symbol."\" style=\"height: 200px;\"></div>";

        $replace = "[sharelink-graph".$param."]";
        return str_replace($replace,$script,$content);
    } else {
        return $content;
    }
}

function renderGraph3($content) {
    $sharelink = new ShareLink();
    $details = $sharelink->getLicenseDetails();

    $key = $details["license"];
    $url = SHARELINK_SOURCE.$key."/graph?version=3";

    $matches = Array();
    $pattern = "/\[sharelink-graph-3(.*)\]/";
    $match_count = preg_match($pattern, $content, $matches);

    if ($match_count != 0) {
        $param = $matches[1];
        $symbol = trim($matches[1]);

        if ($symbol == "") {
            $symbol = $details["symbol"];
        }

        $url = $url."&symbol=".$symbol;

        $script = "<script type=\"text/javascript\" src=\"".$url."\"></script><div id=\"sharelink-graph-".$symbol."\" style=\"height: 360px; width: 600px;\"></div>";

        $replace = "[sharelink-graph-3".$param."]";
        return str_replace($replace,$script,$content);
    } else {
        return $content;
    }
}