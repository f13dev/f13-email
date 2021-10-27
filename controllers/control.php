<?php namespace F13\Email\Controllers;

class Control
{
    public function __construct()
    {
        $this->request_method = ($_SERVER['REQUEST_METHOD'] === 'POST') ? INPUT_POST : INPUT_GET;

        add_shortcode('contact-form', array($this, 'contact_form'));
    }

    public function contact_form($atts = array())
    {
        extract(shortcode_atts(array('id' => ''), $atts));

        if (empty($id)) {
            $id = filter_input($this->request_method, 'form');
        }

        $m = new \F13\Email\Models\Contact_form();
        $data = $m->select_form($id);

        $container = (defined('DOING_AJAX') && DOING_AJAX) ? false : true;

        $msg = '';
        $errors = array();
        $sub = (int) filter_input($this->request_method, 'sub');
        if ($sub) {

            $template = '';

            foreach ($data->fields as $field) {
                $id = 'field-'.$field->sort;
                $value = esc_attr(trim(filter_input($this->request_method, $id)));

                $template .= "<strong>".trim(esc_attr($field->title)).":</strong> ".$value."<br>";

                if ($field->required && empty($value)) {
                    $msg = '<div class="f13-error">'.__('Please complete the field', 'f13-email').': '.esc_attr($field->title).'</div>';
                    $errors[$id] = sprintf(__('%s is a required field', 'f13-email'), esc_attr($field->title));
                }



                if ($field->type == 'email' && !empty($value) && !is_email($value)) {
                    $errors[$id] = sprintf(__('%s is not a valid email address', 'f13-email'), esc_attr($field->title));
                    $reply_to = $value;
                }
                if (($field->type == 'dropdown' || $field->type == 'radio') && !empty($value)) {
                    $options = explode('|', $field->options);
                    $match = false;
                    foreach ($options as $option) {
                        if ($value == trim(esc_attr($option))) {
                            $match = true;
                            break;
                        }
                    }
                    if (!$match) {
                        $errors[$id] = sprintf(__('Please select a valid option for %s', 'f13-email'), esc_attr($field->title));
                    }
                }
                if ($field->type == 'checkbox' && !empty($value) && $value != esc_attr($field->title)) {
                    $errors[$id] = sprintf(__('Please select a valid option for %s', 'f13-email'), esc_attr($field->title));
                }
                if ($field->type == 'date' && !empty($value) && !\DateTime::createFromFormat('Y-m-d', $value)) {
                    $errors[$id] = sprintf(__('%s is not a valid date', 'f13-email'), esc_attr($field->title));
                }
                if ($field->type == 'number' && !empty($value) && !is_numeric($value)) {
                    $errors[$id] = sprintf(__('%s is not a valid number', 'f13-email'), esc_attr($field->title));
                }
            }

            $recaptcha = apply_filters('f13_recaptcha_validate', '');
            if (!empty($recaptcha)) {
                $errors['recaptcha'] = __('Please complete the captcha verification.');
            }

            if (!empty($errors)) {
                $msg = '<div class="f13-error" style="text-align: left; padding: 10px 20px;" role="alert" aria-live="notice">';
                    $msg .= __('<strong>Error:</strong> the following fields require attention -', 'f13-email');
                    $msg .= '<ul style="margin-bottom: 0px;">';
                        foreach ($errors as $key => $value) {
                            $msg .= '<li>'.$value.'</li>';
                        }
                    $msg .= '</ul>';
                $msg .= '</div>';
            } else {
                $headers = array('Content-Type: text/html; charset=UTF-8');
                if (isset($replay_to)) {
                    $headers[] = 'Reply-To: '.$reply_to;
                }
                if (wp_mail( 'jv@f13dev.com', 'Contact from blog', $template, $headers )) {
                    return '<div class="f13-success" role="alert" aria-live="notice">'.trim(esc_attr($data->success)).'</div>';
                }

                // Implement token system so refrshing doesn't resend email.
                $msg = '<div class="f13-error" role="alert" aria-live="notice">'.__('There was an error sending this message via email.', 'f13-email').'</div>';
            }
        }

        if (empty($data)) {
            return '<div class="f13-error" role="alert" aria-live="notice">'.__('Form ID not found.', 'f13-email').'</div>';
        }

        $v = new \F13\Email\Views\Contact_form(array(
            'container' => $container,
            'data' => $data,
            'errors' => $errors,
            'msg' => $msg,
        ));

        return $v->form();
    }

    public function generate_form($id = 0)
    {
        $submit = filter_input($this->request_method, 'submit');
        if ($submit) {
            $id = filter_input($this->request_method, 'id');
            // Check nonce against form id
        }

        $m = new \F13\Email\Models\Contact_form();
        $form = $m->select_form($id);
        //$fields = $m->select_fields($id);     This can be done by select_form appending it to the object

        $msg = '';
        $errors = array();
        $container = true;
        if ($submit) {
            $container = false;

            foreach ($form->fields as $field) {
                if ($field->required) {
                    $field = filter_input($this->request_method, $field->slug);
                    switch ($field->type) {
                        case 'number':
                            // validate number
                            break;
                        case 'text':
                            // validate text
                            break;
                        case 'date':
                            // validate date
                            break;
                        case 'checkbox':
                            // validae checkbox
                            break;
                        case 'dropdown':
                            // validate dropdown
                            break;
                        case 'radio':
                            // validate radio buttons
                            break;
                    }
                }
            }

            if (empty($errors)) {
                // Send email
                // Add to logs (should be automatic from sending)
                // return success message
                return '<div class="f13-contact-success">'.__('Your message has been sent!', 'f13-contact').'</div>';
            }                                               // Allow custom messages to be set for form
        }

        $v = new \F13\Email\Views\Contact_form(array(
            'container' => $container,
            'errors' => $errors,
            'form' => $form,
            'msg' => $msg,
        ));
    }
}