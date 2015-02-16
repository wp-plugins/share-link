<?php

/**
 * Share Link Wordpress Plugin Class
 *
 * @author Chris Darby
 * @version 1.1
 */
class ShareLink extends ShareLinkCommon {

    private $location = SHARELINK_SOURCE;

    /**
     * Update license details in the settings table
     *
     * @param string $stock Three letter ASX stock code
     * @param string $license MD5 random string supplied by ShareLink
     * @return int
     */
    public function updateLicenseDetails($stock, $license) {
        return $this->updateRecord($this->prefix . "sharelink_settings", Array("stock" => $stock, "license" => $license));
    }

    /**
     * Returns an array of current license details from the settings table
     *
     * @return array
     */
    public function getLicenseDetails() {
        $details = $this->resultCurrent("select stock, license from " . $this->prefix . "sharelink_settings limit 1");

        return $details;
    }

    /**
     * Generates the url to send to the share link server
     *
     * @return string URL including license and domain keys
     */
    public function generateUrl() {
        $licenseDetails = $this->getLicenseDetails();
        $license = $licenseDetails["license"];

        $url = $this->location . $license . "/announcements";

        return $url;
    }

    /**
     * Returns the XML feed from the server
     *
     * @return object Simple XML object
     */
    public function feedFromServer() {
        $url = $this->generateUrl();

        $json = $this->returnWithCurl($url);
        $json_feed = json_decode($json);

        $return = Array();

        foreach ($json_feed as $json_item) {
            $json_item->url = $this->createUrlFriendlyFilename($json_item->title, $json_item->date, $json_item->link);

            $return[] = $json_item;
        }

        if ($return !== false) {
            return $return;
        } else {
            return false;
        }
    }

    /**
     * Return data usin Curl
     *
     * @param type $url
     * @return string
     */
    public function returnWithCurl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $data = curl_exec($ch);

        curl_close($ch);
        return $data;
    }

    /**
     * Registers Sharelink installation with server
     *
     * @return boolean
     */
    public function registerWithServer() {
        $url = $this->generateUrl();
        $result = $this->returnWithCurl($url);

        if ($result == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns a url friendly file name based on the title and date
     *
     * @param string $title The title of the announcement
     * @param string $date MySQL Date Stamp
     * @return string The sanatized URL
     */
    private function createUrlFriendlyFilename($title, $date, $file) {
        $date = date("Ymd", strtotime($date));
        $string = str_replace(" ", "-", $title);
        $string = preg_replace("/[^-A-Za-z0-9]/", "", $string);
        $string = strtolower($string);

        $arr = explode("/", $file);
        $str = $arr[count($arr) - 1];
        $str = $this->shuffleString($str);

        return $date . "-" . $string . "-" . $str . ".pdf";
    }

    /**
     * Adds a downloaded article to the database
     *
     * @param type $title
     * @param type $file
     * @param type $date
     * @return boolean
     */
    public function addArticleToDatabase($title, $file, $friendly, $date) {
        $destination_path = ABSPATH . "wp-content/sharelink/" . $friendly;

        if (!$this->checkIfArticleExists($friendly)) {
            if ($this->downloadFile($file, $destination_path)) {

                #################################
                # INSERT NEW ANNOUNCEMENT TO DB #
                #################################

                $insert = Array(
                    "title" => $title,
                    "file" => $friendly,
                    "date" => $date
                );


                return $this->createRecord($this->prefix . "sharelink", $insert);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Download a file from ASX to local location
     *
     * @param string $source
     * @param string $destination
     * @return boolean
     */
    public function downloadFile($source, $destination) {
        $source_content = $this->returnWithCurl($source);

        $destination_file = fopen($destination, "w");

        fwrite($destination_file, $source_content);
        fclose($destination_file);

        if ($source_content === false) {
            return false;
        } else {
            return true;
        }

        return false;
    }


    /**
     * Creates a numerical value for the given string
     *
     * @param string $string
     * @return string
     */
    public function shuffleString($string) {
        $key = "abcdefghijklmnopqrstuvwxyz0123456789";
        $cov = "586795485685412356488475986698541123";

        $str = "";
        $index = 0;

        for ($i = 0; $i < strlen($key); $i++) {
            for ($a = 0; $a < strlen($string); $a++) {
                if ($key[$i] == $string[$a]) {
                    $str .= $cov[$i];
                }
            }
        }

        return $str;
    }

    /**
     * Checks if an article already exists on the clients website
     *
     * @param string $title
     * @param string $date
     * @return boolean
     */
    public function checkIfArticleExists($file) {
        $result = $this->resultCurrent("select id from " . $this->prefix . "sharelink where file = '" . addslashes($file) . "'");

        if (!empty($result)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns an array of the Share Link settings
     *
     * @return array
     */
    public function getSettings() {
        return $this->resultCurrent("select * from " . $this->prefix . "sharelink limit 1");
    }

    /**
     * Save Share Link settings to database
     *
     * @return int Affected Rows
     */

    public function saveSettings() {
        $update = Array(
            "display" => $_POST["display-type"],
            "format" => $_POST["date-format"],
            "byyear" => $_POST["announcements-by-year"],
            "pagination" => $_POST["pagination"],
            "bymonth" => $_POST["by-month"],
            "bymonthheader" => $_POST["by-month-header"],
            "perpage" => $_POST["announcements-per-page"]
        );

        return $this->updateRecord($this->prefix . "sharelink_options", $update);
    }

    /**
     * Returns a list of articles
     *
     * @param int $year
     * @param int $page
     * @return array
     */
    public function listArticles($year = null, $page = null) {
        $settings = $this->getSettings();

        if ($settings["byyear"] == 1) {
            if ($year == null) {
                $year = date("Y");
            }

            $where = " where date like '%" . $year . "%'";
        }

        if ($settings["paginations"] == 1) {
            $perpage = $settings["perpage"];

            if ($page == null) {
                $page = 1;
            }

            $from = ($page - 1) * $perpage;
            $limit = "limit " . $from . "," . $perpage;
        }

        $result = $this->resultArray("select * from " . $this->prefix . "sharelink $where order by date $limit");
        return $result;
    }

    /**
     * Outputs the formatted display based on the stored settings
     *
     * @param int $year
     * @param int $page
     * @return string
     */
    public function displayArticles($year = null, $page = null) {
        $settings = $this->getSettings();
        $listing = $this->listArticles($year, $page);

        return $listing;
    }


}

?>
