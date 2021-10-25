<?php namespace F13\Email\Controllers;

class Admin
{
    public function __construct()
    {
        $this->request_method = ($_SERVER['REQUEST_METHOD'] === 'POST') ? INPUT_POST : INPUT_GET;

        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function admin_menu()
    {
        global $menu;
        $exists = false;
        foreach ($menu as $item) {
            if (strtolower($item[0]) == strtolower('F13 Admin')) {
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            add_menu_page( 'F13 Settings', 'F13 Admin', 'manage_options', 'f13-settings', array($this, 'f13_settings'), 'dashicons-embed-generic', 4);
            add_submenu_page( 'f13-settings', 'Plugins', 'Plugins', 'manage_options', 'f13-settings', array($this, 'f13_settings'));
        }
        add_submenu_page( 'f13-settings', 'Email Settings', 'Email', 'manage_options', 'f13-email', array($this, 'f13_email_settings'));
    }

    public function f13_email_settings()
    {
        $tab = filter_input($this->request_method, 'tab');
        if (empty($tab)) {
            $tab = 'settings';
        }

        $this->msg = '';
        $this->kill = false;

        $submit = filter_input($this->request_method, 'submit');
        $sub = filter_input($this->request_method, 'sub');
        if ($submit || $sub) {
            if ($tab == 'contact-form-new') {
                $this->process_contact_form_new();
            } else
            if ($tab == 'contact-form-edit') {
                $this->process_contact_form_edit();
            } else
            if ($tab == 'contact-forms' && filter_input($this->request_method, 'del')) {
                $this->process_contact_form_delete();
            }
        }

        $data = array();
        switch ($tab) {
            case 'settings':
                $data = array();
                break;
            case 'contact-forms':
                $m = new \F13\Email\Models\Contact_form();
                $data = $m->select_forms();
                break;
            case 'contact-form-new':
                $data = array();
                break;
            case 'contact-form-edit':
                $m = new \F13\Email\Models\Contact_form();
                $id = filter_input($this->request_method, 'id');
                $data = $m->select_form($id);
                break;
            case 'logs':
                $data = array();
                break;
        }

        $v = new \F13\Email\Views\Admin\Admin(array(
            'data' => $data,
            'kill' => $this->kill,
            'msg' => $this->msg,
            'tab' => $tab,
        ));

        echo $v->email_settings();
    }

    public function process_contact_form_delete()
    {
        $id = filter_input($this->request_method, 'del');
        // Check token and capability

        $m = new \F13\Email\Models\Contact_form();
        $m->delete_form($id);
        $this->msg = '<div class="f13-success">'.__('Form deleted.', 'f13-email').'</div>';
    }

    public function process_contact_form_edit()
    {
        $id = filter_input($this->request_method, 'id');
        // Check token and capability

        $title = filter_input($this->request_method, 'title');
        $success = filter_input($this->request_method, 'success');
        $field_title = filter_input(INPUT_POST, 'field_title', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $field_type = filter_input(INPUT_POST, 'field_type', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $field_required = filter_input(INPUT_POST, 'required', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $field_options = filter_input(INPUT_POST, 'option', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        // Validate
        if (empty($title)) {
            $this->msg = '<div class="f13-error">'.__('Please enter a form title.', 'f13-email').'</div>';
        } else
        if (empty($success)) {
            $this->msg = '<div class="f13-error">'.__('Please enter a success message.', 'f13-email').'</div>';
        } else {
            foreach ($field_title as $ftitle) {
                if (empty($ftitle)) {
                    $this->msg = '<div class="f13-error">'.__('Please enter a title for each field.', 'f13-email').'</div>';
                    break;
                }
            }
        }

        if (empty($this->msg)) {
            $m = new \F13\Email\Models\Contact_form();
            $settings = array(
                'title' => $title,
                'success' => $success,
                'enable' => 1,
            );

            $m->update_form($id, $settings);

            $m->delete_fields($id);

            foreach ($field_title as $key => $field) {
                $settings = array(
                    'form_id' => $id,
                    'title' => $field,
                    'type' => $field_type[$key],
                    'slug' => '',
                    'required' => ($field_required[$key] == '1' ? '1' : '0'),
                    'options' => $field_options[$key],
                    'sort' => $key,
                );

                $m->insert_field($settings);
            }

            $this->msg = '<div class="f13-success">'.__('Form updated.', 'f13-email').'</div>';
        }
    }

    public function process_contact_form_new()
    {
        // Check token if false, set error and kill
        $title = filter_input($this->request_method, 'title');
        $success = filter_input($this->request_method, 'success');
        $field_title = filter_input(INPUT_POST, 'field_title', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $field_type = filter_input(INPUT_POST, 'field_type', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $field_required = filter_input(INPUT_POST, 'required', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $field_options = filter_input(INPUT_POST, 'option', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        // Validate
        if (empty($title)) {
            $this->msg = '<div class="f13-error">'.__('Please enter a form title.', 'f13-email').'</div>';
        } else
        if (empty($success)) {
            $this->msg = '<div class="f13-error">'.__('Please enter a success message.', 'f13-email').'</div>';
        } else {
            foreach ($field_title as $ftitle) {
                if (empty($ftitle)) {
                    $this->msg = '<div class="f13-error">'.__('Please enter a title for each field.', 'f13-email').'</div>';
                    break;
                }
            }
        }

        if (empty($this->msg)) {
            $m = new \F13\Email\Models\Contact_form();

            $settings = array(
                'title' => $title,
                'success' => $success,
                'enable' => 1,
            );

            $form_id = $m->insert_form($settings);

            foreach ($field_title as $key => $field) {
                $settings = array(
                    'form_id' => $form_id,
                    'title' => $field,
                    'type' => $field_type[$key],
                    'slug' => '',
                    'required' => ($field_required =='1' ? '1' : '0'),
                    'options' => $field_options[$key],
                    'sort' => $key,
                );

                $m->insert_field($settings);
            }

            $this->msg = '<div class="f13-success">'.__('Form created.', 'f13-email').'</div>';
            $this->kill = true;
        }

    }

    public function f13_settings()
    {
        $v = new \F13\Email\Views\Admin\Admin();

        echo $v->f13_settings();
    }

    public function register_settings()
    {
        register_setting('f13-email-settings-group', 'f13_email_logs_enable');
        register_setting('f13-email-settings-group', 'f13_email_smtp_enable');
        register_setting('f13-email-settings-group', 'f13_email_smtp_protocol');
        register_setting('f13-email-settings-group', 'f13_email_smtp_host');
        register_setting('f13-email-settings-group', 'f13_email_smtp_username');
        register_setting('f13-email-settings-group', 'f13_email_smtp_password');
        register_setting('f13-email-settings-group', 'f13_email_smtp_port');
    }
}