<?php namespace F13\Email\Views\Admin;

class Settings
{
    public function __construct($params = array())
    {
        foreach ($params as $k => $v) {
            $this->{$k} = $v;
        }
    }

    public function page()
    {
            $v = '<h2>'.__('General settings', 'f13-email').'</h2>';

            $v .= '<form method="post" action="options.php">';
                $v .= '<input type="hidden" name="option_page" value="'.esc_attr('f13-email-settings-group').'">';
                $v .= '<input type="hidden" name="action" value="update">';
                $v .= '<input type="hidden" id="_wpnonce" name="_wpnonce" value="'.wp_create_nonce('f13-email-settings-group-options').'">';
                do_settings_sections('f13-email-settings-group');
                $v .= '<table class="form-table">';

                    $v .= '<tr valign="top">';
                        $v .= '<th scope="row">'.__('Enable email logs', 'f13-email').'</th>';
                        $v .= '<td>';
                            $v .= '<input type="checkbox" name="f13_email_logs_enable" '.(esc_attr(get_option('f13_email_logs_enable')) ? 'checked="checked"' : '').'>';
                        $v .= '</td>';
                    $v .= '</tr>';

                    $v .= '<tr valign="top">';
                        $v .= '<th scope="row">'.__('Enable SMTP', 'f13-email').'</th>';
                        $v .= '<td>';
                            $v .= '<input type="checkbox" name="f13_email_smtp_enable" id="f13_email_smtp_enable" '.(esc_attr(get_option('f13_email_smtp_enable')) ? 'checked="checked"' : '').'>';
                        $v .= '</td>';
                    $v .= '</tr>';

                    $style = (esc_attr(get_option('f13_email_smtp_enable')) ? '' : 'style="display: none"');

                        $v .= '<tr valign="top" '.$style.' class="f13_email_smtp_setting f13_email_smtp_setting_first">';
                            $v .= '<th scope="row">'.__('SMTP Host', 'f13-email').'</th>';
                            $v .= '<td>';
                                $v .= '<input type="text" name="f13_email_smtp_host" value="'.esc_attr(get_option('f13_email_smtp_host')).'" style="width: 100%">';
                            $v .= '</td>';
                        $v .= '</tr>';

                        $v .= '<tr valign="top" '.$style.' class="f13_email_smtp_setting">';
                            $v .= '<th scope="row">'.__('SMTP Username', 'f13-email').'</th>';
                            $v .= '<td>';
                                $v .= '<input type="text" name="f13_email_smtp_username" value="'.esc_attr(get_option('f13_email_smtp_username')).'" style="width: 100%">';
                            $v .= '</td>';
                        $v .= '</tr>';

                        $v .= '<tr valign="top" '.$style.' class="f13_email_smtp_setting">';
                            $v .= '<th scope="row">'.__('SMTP Password', 'f13-email').'</th>';
                            $v .= '<td>';
                                $v .= '<input type="password" name="f13_email_smtp_password" value="'.esc_attr(get_option('f13_email_smtp_password')).'" style="width: 100%">';
                            $v .= '</td>';
                        $v .= '</tr>';

                        $v .= '<tr valign="top" '.$style.' class="f13_email_smtp_setting">';
                            $v .= '<th scope="row">'.__('SMTP Port', 'f13-email').'</th>';
                            $v .= '<td>';
                                $v .= '<input type="text" name="f13_email_smtp_port" value="'.esc_attr(get_option('f13_email_smtp_port')).'" style="width: 100%">';
                            $v .= '</td>';
                        $v .= '</tr>';

                        $v .= '<tr valign="top" '.$style.' class="f13_email_smtp_setting f13_email_smtp_setting_last">';
                            $v .= '<th scope="row">'.__('SMTP Protocol', 'f13-email').'</th>';
                            $v .= '<td>';
                                $protocol = esc_attr(get_option('f13_email_smtp_protocol'));
                                $v .= '<select name="f13_email_smtp_protocol" style="width: 200px;">';
                                    $v .= '<option value="ssl" '.($protocol == 'ssl' ? 'selected="selected"' : '').'>'.__('SSL', 'f13-email').'</option>';
                                    $v .= '<option value="tls" '.($protocol == 'tls' ? 'selected="selected"' : '').'>'.__('TLS', 'f13-email').'</option>';
                                $v .= '</select>';
                            $v .= '</td>';
                        $v .= '</tr>';

                    $v .= '<tr valign="top">';
                        $v .= '<th scope="row"></th>';
                        $v .= '<td>';
                            $v .= '<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">';
                        $v .= '</td>';
                    $v .= '</tr>';

                $v .= '</table>';
            $v .= '</form>';

        return $v;
    }
}