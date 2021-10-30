<?php
/*
Plugin Name: F13 Email
Plugin URI: https://f13.dev/wordpress-plugin-email/
Description: Use SMTP for sending emails, store email logs and build custom contact forms
Version: 1.0.0
Author: f13dev
Author URI: http://f13.dev
Text Domain: f13-email
License: GPLv3
*/

namespace F13\Email;

if (!function_exists('get_plugins')) require_once(ABSPATH.'wp-admin/includes/plugin.php');
if (!defined('F13_EMAIL')) define('F13_EMAIL', get_plugin_data(__FILE__, false, false));
if (!defined('F13_EMAIL_PATH')) define('F13_EMAIL_PATH', realpath(plugin_dir_path( __FILE__ )));
if (!defined('F13_EMAIL_URL')) define('F13_EMAIL_URL', plugin_dir_url(__FILE__));

global $wpdb;
if (!defined('F13_EMAIL_CONTACT_FORM')) define('F13_EMAIL_CONTACT_FORM', $wpdb->base_prefix.'f13_email_contact_form');
if (!defined('F13_EMAIL_CONTACT_FORM_FIELDS')) define('F13_EMAIL_CONTACT_FORM_FIELDS', $wpdb->base_prefix.'f13_email_contact_form_fields');
if (!defined('F13_EMAIL_DB_LOGS')) define('F13_EMAIL_DB_LOGS', $wpdb->base_prefix.'f13_email_logs');

register_activation_hook(__FILE__, array('\F13\Email\Plugin', 'install'));

class Plugin
{
    public function init()
    {
        spl_autoload_register(__NAMESPACE__.'\Plugin::loader');
        add_action('wp_enqueue_scripts', array($this, 'enqueue'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue') );

        $c = new Controllers\Control();
        $e = new Controllers\Email();

        if (defined('DOING_AJAX') && DOING_AJAX) {
            $x = new Controllers\Ajax();
        }

        if (is_admin()) {
            $a = new Controllers\Admin();
        }
    }

    public static function loader($name)
    {
        $name = trim(ltrim($name, '\\'));
        if (strpos($name, __NAMESPACE__) !== 0) {
            return;
        }
        $file = str_replace(__NAMESPACE__, '', $name);
        $file = ltrim(str_replace('\\', DIRECTORY_SEPARATOR, $file), DIRECTORY_SEPARATOR);
        $file = plugin_dir_path(__FILE__).strtolower($file).'.php';

        if ($file !== realpath($file) || !file_exists($file)) {
            wp_die('Class not found: '.htmlentities($name));
        } else {
            require_once $file;
        }
    }

    public static function install()
    {
        $c = new Controllers\Install();
        return $c->database();
    }

    public function admin_enqueue()
    {
        wp_enqueue_style('f13-email-admin', F13_EMAIL_URL.'css/f13-email-admin.css', array(), F13_EMAIL['Version']);
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'f13-email-admin', F13_EMAIL_URL.'js/f13-email-admin.js', array('jquery', 'jquery-ui-sortable'), F13_EMAIL['Version'] );
        wp_enqueue_script( 'f13-email-ajax', F13_EMAIL_URL.'js/f13-email-ajax.js', array('jquery'), F13_EMAIL['Version'] );
    }

    public function enqueue()
    {
        wp_enqueue_style('f13-email', F13_EMAIL_URL.'css/f13-email.css', array(), F13_EMAIL['Version']);
        wp_enqueue_script( 'f13-email-ajax', F13_EMAIL_URL.'js/f13-email-ajax.js', array('jquery'), F13_EMAIL['Version'] );
    }
}

$p = new Plugin();
$p->init();
