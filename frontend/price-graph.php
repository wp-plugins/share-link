<?php
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