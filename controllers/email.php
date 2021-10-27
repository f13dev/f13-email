<?php namespace F13\Email\Controllers;

class Email
{
    public function __construct()
    {
        add_action('phpmailer_init', array($this, 'phpmailer_smtp'));
    }

    public function phpmailer_smtp($phpmailer)
    {
        $enable = esc_attr(get_option('f13_email_smtp_enable'));
        if ($enable) {
            $host = esc_attr(get_option('f13_email_smtp_host'));
            $user = esc_attr(get_option('f13_email_smtp_username'));
            $pass = esc_attr(get_option('f13_email_smtp_password'));
            $port = esc_attr(get_option('f13_email_smtp_port'));
            $prot = esc_attr(get_option('f13_email_smtp_protocol'));

            $phpmailer->SetFrom($user, get_bloginfo('name'));
            $phpmailer->Host        = $host;
            $phpmailer->Port        = $port;
            $phpmailer->Username    = $user;
            $phpmailer->Password    = $pass;
            $phpmailer->SMTPAuth    = true;
            $phpmailer->SMTPSecure  = $prot;
            $phpmailer->IsSMTP();
        }

        $logs = esc_attr(get_option('f13_email_logs_enable'));
        if ($logs) {
            $settings = array(
                'address' => json_encode($phpmailer->getAllRecipientAddresses()),
                'body' => $phpmailer->Body,
                'sent' => date('Y-m-d H:i:s'),
            );

            $m = new \F13\Email\Models\Logs();
            $m->insert_log($settings);
        }

        return $phpmailer;
    }
}
