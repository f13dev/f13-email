<?php namespace F13\Email\Controllers;

class Ajax
{
    public function __construct()
    {
        add_action('wp_ajax_f13-email-admin', array($this, 'email_admin'));
        add_action('wp_ajax_f13-contact-form-submit', array($this, 'contact_form_submit'));
        add_action('wp_ajax_nopriv_f13-contact-form-submit', array($this, 'contact_form_submit'));
    }

    public function email_admin() { $c = new Admin(); echo $c->f13_email_settings(); die; }
    public function contact_form_submit() { $c = new Control(); echo $c->contact_form(); die; }
}