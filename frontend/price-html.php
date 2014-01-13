<?php
add_filter('the_content','renderHtml');

function renderHtml($content) {
    $sharelink = new ShareLink();
    $details = $sharelink->getLicenseDetails();

    $key = $details["license"];

    $matches = Array();
    $pattern = "/\[sharelink-(.*)\]/";
    $match_count = preg_match($pattern, $content, $matches);

    if ($match_count != 0) {
        $param = $matches[1];

        $items = trim($matches[1]);

        if (strpos($items," ") !== false) {
            list($type,$div) = explode(" ",$details);
        } else {
            $type = $items;
        }

        $url = SHARELINK_SOURCE.$key."/html?type=".$type;

        if (!isset($div) || $div == "") {
            $div = "sharelink-".$type;
        }

        $url = $url."&div=".$div;

        $script = "<script type=\"text/javascript\" src=\"".$url."\"></script><div id=\"".$div."\"></div>";

        $replace = "[sharelink-".$param."]";
        return str_replace($replace,$script,$content);
    } else {
        return $content;
    }
}

