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

    public function email_settings()
    {
        $v = '<div class="wrap">';
            $v .= '<h1>Email Settings</h1>';
            $v .= '<ul class="f13-email-tabs">';
                $v .= '<li><a href="'.admin_url('admin.php?page=f13-email&tab=settings').'" '.($this->tab == 'settings' ? 'class="selected"' : '').'>Settings</a></li>';
                $v .= '<li><a href="'.admin_url('admin.php?page=f13-email&tab=contact-forms').'" '.($this->tab == 'contact-forms' ? 'class="selected"' : '').'>Contact forms</a></li>';
                $v .= '<li><a href="'.admin_url('admin.php?page=f13-email&tab=logs').'" '.($this->tab == 'logs' ? 'class="selected"' : '').'>Logs</a></li>';
            $v .= '</ul>';

            $v .= '<div class="f13-email-settings-container">';
                switch ($this->tab) {
                    case 'settings':
                        $p = new Settings(array(
                            'data' => $this->data,
                            'kill' => $this->kill,
                            'msg' => $this->msg,
                        ));
                        $v .= $p->page();
                        break;
                    case 'contact-forms':
                        $p = new Contact_form(array(
                            'data' => $this->data,
                            'kill' => $this->kill,
                            'msg' => $this->msg,
                        ));
                        $v .= $p->page();
                        break;
                    case 'contact-form-new':
                        $p = new Contact_form(array(
                            'data' => $this->data,
                            'kill' => $this->kill,
                            'msg' => $this->msg,
                        ));
                        $v .= $p->new_form();
                        break;
                    case 'contact-form-edit':
                        $p = new Contact_form(array(
                            'data' => $this->data,
                            'kill' => $this->kill,
                            'msg' => $this->msg,
                        ));
                        $v .= $p->edit_form();
                        break;
                    case 'logs':
                        $p = new Logs(array(
                            'data' => $this->data,
                            'kill' => $this->kill,
                            'msg' => $this->msg,
                        ));
                        $v .= $p->page();
                        break;
                    default:
                        $v .= '<h2>'.__('Unknown tab', 'f13-email').'</h2>';
                }
            $v .= '</div>';
        $v .= '</div>';

        return $v;
    }
}