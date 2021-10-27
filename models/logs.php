<?php namespace F13\Email\Models;

class Logs
{
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function insert_log($settings)
    {
        $fields = array(
            'address' => '%s',
            'body' => '%s',
            'sent' => '%s',
        );

        $insert = array_intersect_key($settings, $fields);

        return $this->wpdb->insert(F13_EMAIL_DB_LOGS, $insert, $fields);
    }

    public function select_email_logs($page)
    {
        $limit = 10;
        $start = ($page - 1) * $limit;

        $sql = "SELECT db.id, db.address, db.body, db.sent
                FROM ".F13_EMAIL_DB_LOGS." db
                ORDER BY db.sent DESC
                LIMIT %d, %d;";

        $return = new \stdClass();
        $return->results = $this->wpdb->get_results($this->wpdb->prepare($sql, $start, $limit));

        $sql = "SELECT count(*)
                FROM ".F13_EMAIL_DB_LOGS.";";

        $return->total_results = $this->wpdb->get_var($sql);

        $return->pages = ($return->total_results) ? $return->total_results / $limit : 0;
        $return->results_from = ($return->total_results) ? $start + 1 : 0;
        $return->results_to = ($return->results_from + $limit < $return->total_results) ? $return->results_from + $limit : $return->total_results;
        $return->page = (int) $page;

        return $return;
    }
}