<?php namespace F13\Email\Controllers;

class Install
{
    public function database()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $sql = "CREATE TABLE ".F13_EMAIL_CONTACT_FORM." (
            id INT(8) NOT NULL AUTO_INCREMENT,
            title VARCHAR(256),
            success VARCHAR(256),
            enable TINYINT(1) NOT NULL DEFAULT '1',
            PRIMARY KEY (id)
        ) ".$charset_collate.";";

        dbDelta($sql);

        $sql = "CREATE TABLE ".F13_EMAIL_CONTACT_FORM_FIELDS." (
            id INT(8) NOT NULL AUTO_INCREMENT,
            form_id INT(8) NOT NULL DEFAULT '0',
            title VARCHAR(256),
            type VARCHAR(32),
            slug VARCHAR(256),
            required TINYINT(1) NOT NULL DEFAULT '0',
            options TEXT,
            sort INT(8) NOT NULL DEFAULT '0',
            PRIMARY KEY (id)
        ) ".$charset_collate.";";

        dbDelta($sql);
    }
}