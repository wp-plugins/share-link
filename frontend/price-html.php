<?php
add_filter('the_content','renderHtml');

function renderHtml($content) {
    $sharelink = new ShareLink();
    $details = $sharelink->getLicenseDetails();

    $key = $details["license"];

    $matches = Array();
    $pattern = "/\[sharelink-(.*)\]/";
    $match_count = preg_match_all($pattern, $content, $matches);

    if ($match_count != 0) {
        foreach ($matches[1] as $param) {
            $items = trim($param);

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
            $content = str_replace($replace,$script,$content);
            unset($div);
        }

        return $content;
    } else {
        return $content;
    }
}

