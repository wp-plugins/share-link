<?php
/**
 * Common functions class
 * 
 * @author Christopher Darby
 * @version 1.0
 */
class ShareLinkCommon {

    public $prefix;

    /**
     * Prepares the class for use
     */
    public function __construct() {
        global $wpdb;

        $this->prefix = $wpdb->prefix;
    }

    /**
     * Returns a result array based on the given query
     *
     * @param string $query
     * @return array
     */
    protected function resultArray($query) {
        $get = mysql_query($query);
        $return = Array();

        while ($result = mysql_fetch_assoc($get)) {
            $return[] = $result;
        }

        return $return;
    }

    /**
     * Updates a database record based on the given table and conditions
     *
     * @param string $table
     * @param array $data
     * @param array $conditions
     * @return array
     */
    protected function updateRecord($table, $data, $conditions = null) {
        $query = " update " . $table . " set ";

        foreach ($data as $key => $val) {
            $query .= " $key = '" . addslashes($val) . "', ";
        }

        $query = substr($query, 0, strlen($query) - 1);

        if (!empty($conditions)) {
            $query .= " where ";

            foreach ($conditions as $key => $val) {
                if (strpos($key, "like") !== false) {
                    $query .= $key . " '" . addslashes($val) . "',";
                } else if (strpos($key, "like") !== false) {
                    $query .= $key . " '" . addslashes($val) . "',";
                } else if (strpos($key, "like") !== false) {
                    $query .= $key . " '" . addslashes($val) . "',";
                } else {
                    $query .= $key . " = '" . addslashes($val) . "',";
                }
            }

            $query = substr($query, 0, strlen($query) - 1);
        }

        @mysql_query($query);
        return mysql_affected_rows();
    }

    /**
     * Insert a new database record based on the given table and array
     *
     * @param string $table
     * @param array $data
     * @return array
     */
    protected function createRecord($table, $data) {
        $query = " insert into " . $table . " (";

        foreach ($data as $key => $val) {
            $query .= $key . ",";
        }

        $query = substr($query, 0, strlen($query) - 1);

        $query .= ") values (";

        foreach ($data as $key => $val) {
            $query .= "'" . addslashes($val) . "',";
        }

        $query = substr($query, 0, strlen($query) - 1);
        $query .= ")";

        @mysql_query($query);
        return mysql_insert_id();
    }

    /**
     * Returns a single result array based on the given query
     *
     * @param string $query
     * @return array
     */
    protected function resultCurrent($query) {
        $result = $this->resultArray($query);
        return $result[0];
    }

    /**
     * Deletes a record from the database
     *
     * @param string $query
     * @return array
     */
    protected function deleteRecord($table, $conditions) {
        $query = "delete from ".$table;
        
        if (!empty($conditions)) {
            $query .= " where ";

            foreach ($conditions as $key => $val) {
                if (strpos($key, "like") !== false) {
                    $query .= $key . " '" . addslashes($val) . "',";
                } else if (strpos($key, "like") !== false) {
                    $query .= $key . " '" . addslashes($val) . "',";
                } else if (strpos($key, "like") !== false) {
                    $query .= $key . " '" . addslashes($val) . "',";
                } else {
                    $query .= $key . " = '" . addslashes($val) . "',";
                }
            }

            $query = substr($query, 0, strlen($query) - 1);
        }

        @mysql_query($query);
        return mysql_affected_rows();
    }

}