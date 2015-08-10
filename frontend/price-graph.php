<?php
add_filter('the_content','renderGraph3');
add_filter('the_content','renderGraph');

function renderGraph($content) {
    $sharelink = new ShareLink();
    $details = $sharelink->getLicenseDetails();

    $matches = Array();
    $pattern = "/\[sharelink-graph(.*)\]/";
    $match_count = preg_match_all($pattern, $content, $matches);

    if ($match_count != 0) {
        foreach ($matches[1] as $param) {
            $symbol = trim($param);

            if ($symbol == "") {
                $symbol = $details["stock"];
            }

            $key = $details["license"];
            $url = SHARELINK_SOURCE.$key."/graph?version=1";
            $url = $url."&symbol=".$symbol;

            $script = "<script type=\"text/javascript\" src=\"".$url."\"></script><div id=\"sharelink-graph-".$symbol."\" style=\"height: 200px;\"></div>";

            $replace = "[sharelink-graph".$param."]";
            $content = str_replace($replace,$script,$content);
        }

        return $content;
    } else {
        return $content;
    }
}

function renderGraph3($content) {
    $sharelink = new ShareLink();
    $details = $sharelink->getLicenseDetails();

    $matches = Array();
    $pattern = "/\[sharelink-graph-3(.*)\]/";
    $match_count = preg_match_all($pattern, $content, $matches);

    if ($match_count != 0) {
        foreach ($matches[1] as $param) {
            $symbol = trim($param);

            if ($symbol == "") {
                $symbol = $details["stock"];
            }

            $key = $details["license"];
            $url = SHARELINK_SOURCE.$key."/graph?version=3";
            $url = $url."&symbol=".$symbol;

            $script = "<script type=\"text/javascript\" src=\"".$url."\"></script><div id=\"sharelink-graph-".$symbol."\" style=\"height: 360px; width: 600px;\"></div>";

            $replace = "[sharelink-graph-3".$param."]";
            $content = str_replace($replace,$script,$content);
            unset($url);
        }

        return $content;
    } else {
        return $content;
    }
}