<?php

add_filter('the_content', 'renderAnnouncements');

function renderAnnouncements($content) {
    $data = "";

    global $wpdb;
    $get = mysql_query("select * from " . $wpdb->prefix . "sharelink_options limit 1");
    $result = mysql_fetch_array($get);

    $data = "";
    if ($result["display"] == 0) {
        $data = renderTable($result);
    } else if ($result["display"] == 1) {
        $data = renderList($result);
    }

    return str_replace("[sharelink-asx]", $data, $content);
}

function renderTable($options) {
    $content = "";
    $content .= "<div class=\"sl-announcements\">";
    $content .= "<table>";
    $content .= "<thead>";
    $content .= "<tr>";
    $content .= "<th>Date</th>";
    $content .= "<th>Title</th>";
    $content .= "<th></th>";
    $content .= "</tr>";
    $content .= "</thead>";
    $content .= "<tbody>";

    global $wpdb;

    if ($options["byyear"] == 1) {
        $years = renderByYear();
        if (!isset($_GET["year"])) {
            $_GET["year"] = $years[0];
        }

        $year = $_GET["year"];

        $start_date = ($year - 1) . "-12-31 11:59:59";
        $end_date = ($year + 1) . "-01-01 00:00:00";

        $year_limit = " where date > '" . $start_date . "' and date < '" . $end_date . "' ";

        if (strpos($_SERVER["REQUEST_URI"], "section=") !== false) {
            $start = 1;
            $end = 100;
            
            while ($start < $end) {
                $_SERVER["REQUEST_URI"] = str_replace("&section=".$start,"",$_SERVER["REQUEST_URI"]);
                $_SERVER["REQUEST_URI"] = str_replace("?section=".$start,"",$_SERVER["REQUEST_URI"]);
                
                $start++;
            }
        }
        
        if (strpos($_SERVER["REQUEST_URI"], "year=") !== false) {
            foreach ($years as $year) {
                $_SERVER["REQUEST_URI"] = str_replace("&year=".$year,"",$_SERVER["REQUEST_URI"]);
                $_SERVER["REQUEST_URI"] = str_replace("?year=".$year,"",$_SERVER["REQUEST_URI"]);
                
            }
        }
        
        if (strpos($_SERVER["REQUEST_URI"], "?") !== false) {
            $req = $_SERVER["REQUEST_URI"]."&year=";
        } else {
            $req = $_SERVER["REQUEST_URI"]."?year=";
        }
        
        
        $content .= "<script>function runYear(v) { document.location.href = '".$req."' + v; }</script>";
        $content .= "<select class=\"sl-year\" onchange=\"runYear(this.value)\">";
        foreach ($years as $year) {
            $content .= "<option value=\"" . $year . "\"";
            if ($year == $_GET["year"]) {
                $content .= " selected=\"selected\"";
            }
            $content .= ">" . $year . "</option>";
        }
        $content .= "</select><br /><br />";
    } else {
        $year_limit = "";
    }


    $get = mysql_query("select * from " . $wpdb->prefix . "sharelink " . $year_limit . " order by date desc" . getLimits($options["pagination"], $options["perpage"]));

    while ($result = mysql_fetch_array($get)) {
        $content .= "<tr>";
        $content .= "<td>" . date($options["dateformat"], strtotime($result["date"])) . "</td>";
        $content .= "<td>" . $result["title"] . "</td>";
        $content .= "<td><a target=\"_new\" href=\"" . WP_CONTENT_URL . "/sharelink/" . $result["file"] . "\">Download</a></td>";
        $content .= "</tr>";
    }

    $content .= "</tbody>";
    $content .= "</table>";

    if ($options["pagination"] == 1) {
        $content .= renderPagination(mysql_num_rows(mysql_query("select * from " . $wpdb->prefix . "sharelink $year_limit")), $options["perpage"], $options["byyear"]);
    }

    $content .= "</div>";


    return $content;
}

function renderList($options) {
    $content = "";
    $content .= "<div class=\"sl-announcements\"><ul>";

    global $wpdb;

    if ($options["byyear"] == 1) {
        $years = renderByYear();
        if (!isset($_GET["year"])) {
            $_GET["year"] = $years[0];
        }

        $year = $_GET["year"];

        $start_date = ($year - 1) . "-12-31 11:59:59";
        $end_date = ($year + 1) . "-01-01 00:00:00";

        $year_limit = " where date > '" . $start_date . "' and date < '" . $end_date . "' ";

        if (strpos($_SERVER["REQUEST_URI"], "section=") !== false) {
            $start = 1;
            $end = 100;
            
            while ($start < $end) {
                $_SERVER["REQUEST_URI"] = str_replace("&section=".$start,"",$_SERVER["REQUEST_URI"]);
                $_SERVER["REQUEST_URI"] = str_replace("?section=".$start,"",$_SERVER["REQUEST_URI"]);
                
                $start++;
            }
        }
        
        if (strpos($_SERVER["REQUEST_URI"], "year=") !== false) {
            foreach ($years as $year) {
                $_SERVER["REQUEST_URI"] = str_replace("&year=".$year,"",$_SERVER["REQUEST_URI"]);
                $_SERVER["REQUEST_URI"] = str_replace("?year=".$year,"",$_SERVER["REQUEST_URI"]);
                
            }
        }
        
        if (strpos($_SERVER["REQUEST_URI"], "?") !== false) {
            $req = $_SERVER["REQUEST_URI"]."&year=";
        } else {
            $req = $_SERVER["REQUEST_URI"]."?year=";
        }
        
        
        $content .= "<script>function runYear(v) { document.location.href = '".$req."' + v; }</script>";
        $content .= "<select class=\"sl-year\" onchange=\"runYear(this.value)\">";
        foreach ($years as $year) {
            $content .= "<option value=\"" . $year . "\"";
            if ($year == $_GET["year"]) {
                $content .= " selected=\"selected\"";
            }
            $content .= ">" . $year . "</option>";
        }
        $content .= "</select><br /><br />";
    } else {
        $year_limit = "";
    }


    $get = mysql_query("select * from " . $wpdb->prefix . "sharelink " . $year_limit . " order by date desc" . getLimits($options["pagination"], $options["perpage"]));

    if (isset($options["bymonth"])) {
        $month = "";
    }
    
    while ($result = mysql_fetch_array($get)) {
        if (isset($options["bymonth"])) {
           $current = date($options["monthheader"], strtotime($result["date"]));
           
           if ($current != $month) {
               $content .= "<li class=\"sl-month\">".$current."</li>";
               $month = $current;
           }
        }
        
        $content .= "<li>";
        $content .= "<span class=\"sl-date\">" . date($options["dateformat"], strtotime($result["date"])) . "</span> ";
        $content .= "<span class=\"sl-title\"><a target=\"_new\" href=\"" . WP_CONTENT_URL . "/sharelink/" . $result["file"] . "\">" . $result["title"] . "</a></span>";
        $content .= "</li>";
    }

    $content .= "</ul>";

    if ($options["pagination"] == 1) {
        $content .= renderPagination(mysql_num_rows(mysql_query("select * from " . $wpdb->prefix . "sharelink $year_limit")), $options["perpage"], $options["byyear"]);
    }

    $content .= "</div>";


    return $content;
}

function getLimits($pagination, $perpage) {
    if (!isset($_GET["section"])) {
        $_GET["section"] = 1;
    }

    if ($_GET["section"] == 1) {
        $start = 0;
    } else {
        $start = ($_GET["section"] - 1) * $perpage;
    }


    $end = $perpage;

    if ($pagination == 1) {
        return " limit $start,$end";
    } else {
        return "";
    }
}

function renderByYear() {
    global $wpdb;

    $years = Array();

    $get = mysql_query("select * from " . $wpdb->prefix . "sharelink order by date desc");
    while ($result = mysql_fetch_array($get)) {
        $year = date("Y", strtotime($result["date"]));

        if (!in_array($year, $years)) {
            $years[] = $year;
        }
    }

    return $years;
}

function renderPagination($total, $page, $byyear) {
   $pages = $total / $page;

    if (strpos($pages, ".") !== false) {
        list($pages, $float) = explode(".", $pages);
        $pages = $pages + 1;
    }


    if (strpos($_SERVER["REQUEST_URI"], "?section=") !== false) {
        $start = 0;
        while ($start < $pages) {
            $_SERVER["REQUEST_URI"] = str_replace("?section=" . ($start + 1), "", $_SERVER["REQUEST_URI"]);
            $start++;
        }
        $sep = "?";
    }

    if (strpos($_SERVER["REQUEST_URI"], "&section=") !== false) {
        $start = 0;
        while ($start < $pages) {
            $_SERVER["REQUEST_URI"] = str_replace("&section=" . ($start + 1), "", $_SERVER["REQUEST_URI"]);
            $start++;
        }

        $sep = "&";
    }


    if ($byyear == 1) {
        $years = renderByYear();

        if (strpos($_SERVER["REQUEST_URI"], "?year=") !== false) {
            foreach ($years as $year) {
                $_SERVER["REQUEST_URI"] = str_replace("?year=" . $year, "", $_SERVER["REQUEST_URI"]);
            }
        }

        if (strpos($_SERVER["REQUEST_URI"], "&year=") !== false) {
            foreach ($years as $year) {
                $_SERVER["REQUEST_URI"] = str_replace("&year=" . $year, "", $_SERVER["REQUEST_URI"]);
            }
        }

        if (!isset($_GET["year"])) {
            $yr = "&year=" . $years[0];
        } else {
            $yr = "&year=" . $_GET["year"];
        }
    } else {
        $yr = "";
    }

    $_SERVER["REQUEST_URI"] = str_replace("&&","",$_SERVER["REQUEST_URI"]);

	if (strpos($_SERVER["REQUEST_URI"],"&") !== false && strpos($_SERVER["REQUEST_URI"],"?") === false) {
	    $_SERVER["REQUEST_URI"] = str_replace("&","?",$_SERVER["REQUEST_URI"]);
	}


    $start = 0;
    $content .= "<div class=\"sl-pagination\">";
    while ($start < $pages) {
        $content .= "<a href=\"" . $_SERVER["REQUEST_URI"] . "" . $sep . "&section=" . ($start + 1) . "" . $yr . "\">" . ($start + 1) . "</a> &nbsp;";
        $start++;
    }
    $content .= "</div>";

    return $content;
}