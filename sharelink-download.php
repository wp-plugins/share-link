<?php

if (isset($_GET["download-sharelink"])) {
    function sortFeedHelper($a, $b) {
        // Reverse sort all entries, so newest appear at the top
        // in the RSS feed. Also, new entries get processed first.
        return strcmp($b->date, $a->date);
    }

    $sharelink = new ShareLink();
    $feed = $sharelink->feedFromServer();

    usort($feed, 'sortFeedHelper');

    $license_details = $sharelink->getLicenseDetails();

    $rss_body = '<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel>
 <title>ASX Announcments for ' . htmlspecialchars($license_details['stock']) . '</title>
 <description>ASX Announcments for ' . htmlspecialchars($license_details['stock']) . '</description>
 <link>http://' . $_SERVER['HTTP_HOST'] . '/</link>
 <lastBuildDate>' . date(DATE_RSS) . '</lastBuildDate>
 <pubDate>' . date(DATE_RSS) . '</pubDate>
 <ttl>1800</ttl>
';

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

        // Add the RSS entry as well.
        $item_timestamp = strtotime($date);
        $pdf_url = sprintf("http://%s/wp-content/sharelink/%s", $_SERVER['HTTP_HOST'], $item->url);
        $entry_body = '<p><a href="' . htmlspecialchars($pdf_url) . '">' . htmlspecialchars($title) . '</a></p>';
        $rss_body .= ' <item>
  <title>' . htmlspecialchars($title) . '</title>
  <description>' . htmlspecialchars($title) . '</description>
  <content:encoded><![CDATA['  . $entry_body . ']]></content:encoded>
  <link>' . htmlspecialchars($pdf_url) . '</link>
  <guid>' . htmlspecialchars($friendly) . '</guid>
  <pubDate>' . date(DATE_RSS, $item_timestamp) . '</pubDate>
 </item>
';
    }

    $rss_body .= '</channel>
</rss>';

    // Write out to the cache.
    $doWritePath = WP_CONTENT_DIR . "/sharelink/do-announcements.txt";
    if (file_exists($doWritePath)) {
        $rss_cache = WP_CONTENT_DIR . "/sharelink/announcements.xml";
        file_put_contents($rss_cache, $rss_body);
    }

    die("Download complete");
}
