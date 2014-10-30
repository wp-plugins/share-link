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
        global $wpdb;

        return $wpdb->get_results($query, ARRAY_A);
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
        global $wpdb;

        return $wpdb->update($table, $data, $conditions);
    }

    /**
     * Insert a new database record based on the given table and array
     *
     * @param string $table
     * @param array $data
     * @return array
     */
    protected function createRecord($table, $data) {
        global $wpdb;

        $wpdb->insert($table, $data);

        return $wpdb->insert_id;
    }

    /**
     * Returns a single result array based on the given query
     *
     * @param string $query
     * @return array
     */
    protected function resultCurrent($query) {
        $result = $this->resultArray($query);
        if (count($result) > 0) {
            return $result[0];
        } else {
            return array();
        }
    }

    /**
     * Deletes a record from the database
     *
     * @param string $query
     * @return array
     */
    protected function deleteRecord($table, $conditions) {
        global $wpdb;

        return $wpdb->delete($table, $conditions);
    }

}