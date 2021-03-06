<?php

add_filter('the_content', 'sharelinkRenderAnnouncementsFilterContent');

function renderAnnouncements() {
    $data = "";

    global $wpdb;
    $result = $wpdb->get_row("select * from " . $wpdb->prefix . "sharelink_options limit 1", ARRAY_A);

    $data = "";
    if ($result["display"] == 0) {
        $data = renderTable($result);
    } else if ($result["display"] == 1) {
        $data = renderList($result);
    }

    return $data;
}

function sharelinkRenderAnnouncementsFilterContent($content) {
    if (strpos($content, '[sharelink-asx]') !== false) {
        return str_replace("[sharelink-asx]", renderAnnouncements(), $content);
    } else {
        return $content;
    }
}

function sharelinkAnnouncementsShortcode($atts) {
    return renderAnnouncements();
}

add_shortcode( 'sharelink-asx', 'sharelinkAnnouncementsShortcode' );

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
        if (!isset($_GET['sl-year'])) {
            $_GET['sl-year'] = $years[0];
        }

        $year = $_GET['sl-year'];

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
                $_SERVER["REQUEST_URI"] = str_replace("&sl-year=".$year,"",$_SERVER["REQUEST_URI"]);
                $_SERVER["REQUEST_URI"] = str_replace("?sl-year=".$year,"",$_SERVER["REQUEST_URI"]);

            }
        }

        if (strpos($_SERVER["REQUEST_URI"], "?") !== false) {
            $req = $_SERVER["REQUEST_URI"]."&sl-year=";
        } else {
            $req = $_SERVER["REQUEST_URI"]."?sl-year=";
        }


        $content .= "<script>function runYear(v) { document.location.href = '".$req."' + v; }</script>";
        $content .= "<select class=\"sl-year\" onchange=\"runYear(this.value)\">";
        foreach ($years as $year) {
            $content .= "<option value=\"" . $year . "\"";
            if ($year == $_GET['sl-year']) {
                $content .= " selected=\"selected\"";
            }
            $content .= ">" . $year . "</option>";
        }
        $content .= "</select><br /><br />";
    } else {
        $year_limit = "";
    }

    $results = $wpdb->get_results("select * from " . $wpdb->prefix . "sharelink " . $year_limit . " order by date desc, id desc" . getLimits($options["pagination"], $options["perpage"]), ARRAY_A);

    foreach ($results as $result) {
        $content .= "<tr>";
        $content .= "<td>" . date($options["dateformat"], strtotime($result["date"])) . "</td>";
        $content .= "<td>" . $result["title"] . "</td>";
        $content .= "<td><a target=\"_new\" href=\"" . WP_CONTENT_URL . "/sharelink/" . $result["file"] . "\">Download</a></td>";
        $content .= "</tr>";
    }

    $content .= "</tbody>";
    $content .= "</table>";

    if ($options["pagination"] == 1) {
        $total = $wpdb->get_var( "SELECT COUNT(*) FROM " . $wpdb->prefix . "sharelink $year_limit" );
        $content .= renderPagination($total, $options["perpage"], $options["byyear"]);
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
        if (!isset($_GET['sl-year'])) {
            $_GET['sl-year'] = $years[0];
        }

        $year = $_GET['sl-year'];

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
                $_SERVER["REQUEST_URI"] = str_replace("&sl-year=".$year,"",$_SERVER["REQUEST_URI"]);
                $_SERVER["REQUEST_URI"] = str_replace("?sl-year=".$year,"",$_SERVER["REQUEST_URI"]);

            }
        }

        if (strpos($_SERVER["REQUEST_URI"], "?") !== false) {
            $req = $_SERVER["REQUEST_URI"]."&sl-year=";
        } else {
            $req = $_SERVER["REQUEST_URI"]."?sl-year=";
        }


        $content .= "<script>function runYear(v) { document.location.href = '".$req."' + v; }</script>";
        $content .= "<select class=\"sl-year\" onchange=\"runYear(this.value)\">";
        foreach ($years as $year) {
            $content .= "<option value=\"" . $year . "\"";
            if ($year == $_GET['sl-year']) {
                $content .= " selected=\"selected\"";
            }
            $content .= ">" . $year . "</option>";
        }
        $content .= "</select><br /><br />";
    } else {
        $year_limit = "";
    }

    $results = $wpdb->get_results("select * from " . $wpdb->prefix . "sharelink " . $year_limit . " order by date desc" . getLimits($options["pagination"], $options["perpage"]), ARRAY_A);

    if (isset($options["bymonth"]) && $options["bymonth"]!=0) {
        $month = "";
    }

    foreach ($results as $result) {
        if (isset($options["bymonth"]) && $options["bymonth"]!=0) {
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
        $total = $wpdb->get_var( "select count(*) from " . $wpdb->prefix . "sharelink $year_limit" );
        $content .= renderPagination($total, $options["perpage"], $options["byyear"]);
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

    $results = $wpdb->get_results("select * from " . $wpdb->prefix . "sharelink order by date desc", ARRAY_A);
    foreach ($results as $result) {
        $year = date("Y", strtotime($result["date"]));

        if (!in_array($year, $years)) {
            $years[] = $year;
        }
    }

    if (count($years) == 0) {
        $years[] = date('Y');
    }

    return $years;
}

function renderPagination($total, $page, $byyear) {
   $pages = $total / $page;

   $sep = "?"; // default separator is a question mark for first page by default

    if (strpos($pages, ".") !== false) {
        list($pages, $float) = explode(".", $pages);
        $pages = $pages + 1;
    }

    // Remove a trailing slash as it confuses this code (added by Craig)
    $_SERVER["REQUEST_URI"] = rtrim($_SERVER["REQUEST_URI"], '/');

    if (strpos($_SERVER["REQUEST_URI"], "?section=") !== false) {
        $_SERVER["REQUEST_URI"] = preg_replace("/\?section=[0-9]+/", "", $_SERVER["REQUEST_URI"]);
        $sep = "?";
    }

    if (strpos($_SERVER["REQUEST_URI"], "&section=") !== false) {
        $_SERVER["REQUEST_URI"] = preg_replace("/\&section=[0-9]+/", "", $_SERVER["REQUEST_URI"]);
        $sep = "&";
    }


    if ($byyear == 1) {
        $years = renderByYear();

        if (strpos($_SERVER["REQUEST_URI"], "?sl-year=") !== false) {
            foreach ($years as $year) {
                $_SERVER["REQUEST_URI"] = str_replace("?sl-year=" . $year, "", $_SERVER["REQUEST_URI"]);
            }
        }

        if (strpos($_SERVER["REQUEST_URI"], "&sl-year=") !== false) {
            foreach ($years as $year) {
                $_SERVER["REQUEST_URI"] = str_replace("&sl-year=" . $year, "", $_SERVER["REQUEST_URI"]);
            }
        }

        if (!isset($_GET['sl-year'])) {
            $yr = "&sl-year=" . $years[0];
        } else {
            $yr = "&sl-year=" . $_GET['sl-year'];
        }
    } else {
        $yr = "";
    }

    $_SERVER["REQUEST_URI"] = str_replace("&&","",$_SERVER["REQUEST_URI"]);

	if (strpos($_SERVER["REQUEST_URI"],"&") !== false && strpos($_SERVER["REQUEST_URI"],"?") === false) {
	    $_SERVER["REQUEST_URI"] = str_replace("&","?",$_SERVER["REQUEST_URI"]);
	}

    $current_page = 1;
    if (isset($_GET['section'])) {
        $current_page = (int)$_GET['section'];

        if ($current_page > $pages || $current_page < 1) {
            $current_page = 1;
        }
    }


    $start = 0;
    if (!isset($content)) {
        $content = "";
    }
    $content .= "<div class=\"sl-pagination\">";
    while ($start < $pages) {
        $is_active = $current_page == ($start + 1);
        $content .= "<a ";
        if ($is_active) {
            $content .= 'class="active" ';
        }
        $content .= "href=\"" . $_SERVER["REQUEST_URI"] . "" . $sep . "section=" . ($start + 1) . "" . $yr . "\">" . ($start + 1) . "</a> &nbsp;";
        $start++;
    }
    $content .= "</div>";

    return $content;
}
