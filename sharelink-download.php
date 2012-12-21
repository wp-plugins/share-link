<?php

if (isset($_GET["download-sharelink"])) {
    $sharelink = new ShareLink();
    $feed = $sharelink->feedFromServer();
    
    foreach ($feed as $item) {
        $title = $item->title;
        $file = $item->link;
        $friendly = $item->url;
        $date = $item->date;
    
        if ($sharelink->addArticleToDatabase($title, $file, $friendly, $date)) {
            echo $title." added<br />";
        } else {
            echo $title." not added<br />";
        }
    }
    
    die("Download complete");
}
