<?php namespace F13\Email\Views\Admin;

class Admin
{
    public $label_all_wordpress_plugins;
    public $label_plugins_by_f13;

    public function __construct($params = array())
    {
        foreach ($params as $k => $v) {
            $this->{$k} = $v;
        }

        $this->label_all_wordpress_plugins = __('All WordPress Plugins', 'f13-google-maps');
        $this->label_plugins_by_f13 = __('Plugins by F13', 'f13-google-maps');
    }

    public function f13_settings()
    {
        $response = wp_remote_get('https://pluginlist.f13.dev');
        $body     = wp_remote_retrieve_body( $response );
        $v = '<div class="wrap">';
            $v .= '<h1>'.$this->label_plugins_by_f13.'</h1>';
            $v .= '<div id="f13-plugins">'.$body.'</div>';
            $v .= '<a href="'.admin_url('plugin-install.php').'?s=f13dev&tab=search&type=author">'.$this->label_all_wordpress_plugins.'</a>';
        $v .= '</div>';

        return $v;
    }

    public function _email_settings_container($content)
    {
        $v = '<div class="wrap">';
            $v .= '<h1>Email Settings</h1>';
            $v .= '<ul class="f13-email-tabs">';
                $v .= '<li><a class="f13-email-ajax '.($this->tab == 'settings' ? 'selected' : '').'" data-target="f13-email-settings-container" data-action="'.admin_url('admin-ajax.php?action=f13-email-admin&tab=settings').'" href="'.admin_url('admin.php?page=f13-email&tab=settings').'">Settings</a></li>';
                $v .= '<li><a class="f13-email-ajax '.($this->tab == 'contact-forms' ? 'selected' : '').'" data-target="f13-email-settings-container" data-action="'.admin_url('admin-ajax.php?action=f13-email-admin&tab=contact-forms').'" href="'.admin_url('admin.php?page=f13-email&tab=contact-forms').'">Contact forms</a></li>';
                $v .= '<li><a class="f13-email-ajax '.($this->tab == 'logs' ? 'selected' : '').'" data-target="f13-email-settings-container" data-action="'.admin_url('admin-ajax.php?action=f13-email-admin&tab=logs').'" href="'.admin_url('admin.php?page=f13-email&tab=logs').'">Logs</a></li>';
            $v .= '</ul>';

            $v .= '<div class="f13-email-settings-container" id="f13-email-settings-container">';
                $v .= $content;
            $v .= '</div>';

        $v .= '</div>';

        return $v;
    }

    public function email_settings()
    {
        if (!current_user_can('administrator')) {
            return '<div class="f13-error">'.__('Only administrators can access this form!', 'f13-email').'</div>';
        }
        switch ($this->tab) {
            case 'settings':
                $p = new Settings(array(
                    'data' => $this->data,
                    'kill' => $this->kill,
                    'msg' => $this->msg,
                ));
                $v = $p->page();
                break;
            case 'contact-forms':
                $p = new Contact_form(array(
                    'data' => $this->data,
                    'kill' => $this->kill,
                    'msg' => $this->msg,
                ));
                $v = $p->page();
                break;
            case 'contact-form-new':
                $p = new Contact_form(array(
                    'data' => $this->data,
                    'kill' => $this->kill,
                    'msg' => $this->msg,
                ));
                $v = $p->new_form();
                break;
            case 'contact-form-edit':
                $p = new Contact_form(array(
                    'data' => $this->data,
                    'kill' => $this->kill,
                    'msg' => $this->msg,
                ));
                $v = $p->edit_form();
                break;
            case 'logs':
                $p = new Logs(array(
                    'data' => $this->data,
                    'kill' => $this->kill,
                    'msg' => $this->msg,
                ));
                $v = $p->page();
                break;
            default:
                $v = '<h2>'.__('Unknown tab', 'f13-email').'</h2>';
        }

        return ($this->container) ? $this->_email_settings_container($v) : $v;
    }
}