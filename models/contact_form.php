<?php namespace F13\Email\Models;

class Contact_form
{
    public $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function delete_field($id)
    {
        $sql = "DELETE FROM ".F13_EMAIL_CONTACT_FORM_FIELDS."
                WHERE id = %d;";

        return $this->wpdb->query($this->wpdb->prepare($sql, $id));
    }

    public function delete_fields($form_id)
    {
        $sql = "DELETE FROM ".F13_EMAIL_CONTACT_FORM_FIELDS."
                WHERE form_id = %d;";

        return $this->wpdb->query($this->wpdb->prepare($sql, $form_id));
    }

    public function delete_form($id)
    {
        $sql = "DELETE FROM ".F13_EMAIL_CONTACT_FORM."
                WHERE id = %d;";

        $this->delete_fields($id);

        return $this->wpdb->query($this->wpdb->prepare($sql, $id));
    }

    public function insert_field($settings)
    {
        $fields = array(
            'form_id' => '%d',
            'title' => '%s',
            'type' => '%s',
            'slug' => '%s',
            'required' => '%d',
            'options' => '%s',
            'sort' => '%d',
        );

        $insert = array_intersect_key($settings, $fields);

        return $this->wpdb->insert(F13_EMAIL_CONTACT_FORM_FIELDS, $insert, $fields);
    }

    public function insert_form($settings)
    {
        $fields = array(
            'title' => '%s',
            'success' => '%s',
            'enable' => '%d',
        );

        $insert = array_intersect_key($settings, $fields);

        $this->wpdb->insert(F13_EMAIL_CONTACT_FORM, $insert, $fields);

        return $this->wpdb->insert_id;
    }

    public function select_field($id)
    {
        $sql = "SELECT db.id, db.form_id, db.title, db.type, db.slug, db.required, db.options, db.sort
                FROM ".F13_EMAIL_CONTACT_FORM_FIELDS." db
                WHERE db.id = %d;";
        return $this->wpdb->get_row($this->wpdb->prepare($sql, $id));
    }

    public function select_fields($form_id)
    {
        $sql = "SELECT db.id, db.form_id, db.title, db.type, db.slug, db.required, db.options, db.sort
                FROM ".F13_EMAIL_CONTACT_FORM_FIELDS." db
                WHERE db.form_id = %d;";

        return $this->wpdb->get_results($this->wpdb->prepare($sql, $form_id));
    }

    public function select_form($id)
    {
        $sql = "SELECT db.id, db.title, db.success, db.enable
                FROM ".F13_EMAIL_CONTACT_FORM." db
                WHERE db.id = %d;";

        $data = $this->wpdb->get_row($this->wpdb->prepare($sql, $id));

        $data->fields = $this->select_fields($id);

        return $data;
    }

    public function select_forms()
    {
        $sql = "SELECT db.id, db.title, db.success, db.enable,
                    (SELECT COUNT(*) FROM ".F13_EMAIL_CONTACT_FORM_FIELDS." WHERE form_id = db.id) fields
                FROM ".F13_EMAIL_CONTACT_FORM." db";

        return $this->wpdb->get_results($sql);

    }

    public function update_field($id, $settings)
    {
        $db_fields = array(
            'form_id' => '%d',
            'title' => '%s',
            'type' => '%s',
            'slug' => '%s',
            'required' => '%d',
            'options' => '%s',
            'sort' => '%d',
        );
        $data = stripslashes_deep(array_intersect_key($settings, $db_fields));

        $format = array();
        foreach ($data as $field => $value) {
            $format[$field] = $db_fields[$field];
        }

        $where = array( 'id' => $id);

        $where_format = array( '%d' );

        return $this->wpdb->update(F13_EMAIL_CONTACT_FORM_FIELDS, $data, $where, $format, $where_format);
    }

    public function update_form($id, $settings)
    {
        $db_fields = array(
            'title' => '%s',
            'success' => '%s',
            'enable' => '%d',
        );
        $data = stripslashes_deep(array_intersect_key($settings, $db_fields));

        $format = array();
        foreach ($data as $field => $value) {
            $format[$field] = $db_fields[$field];
        }

        $where = array( 'id' => $id);

        $where_format = array( '%d' );

        return $this->wpdb->update(F13_EMAIL_CONTACT_FORM, $data, $where, $format, $where_format);
    }
}