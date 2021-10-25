<?php namespace F13\Email\Views\Admin;

class Contact_form
{
    public function __construct($params = array())
    {
        foreach ($params as $k => $v) {
            $this->{$k} = $v;
        }
    }

    public function page()
    {
        $v = '<a class="f13_email_header_btn f13_email_new" href="'.admin_url('admin.php?page=f13-email&tab=contact-form-new').'" title="'.__('New contact form', 'f13-email').'"></a>';
        $v .= '<h2>Contact forms</h2>';

        $v .= $this->msg;

        $v .= '<table class="form-table">';
            $v .= '<tr valign="top">';
                $v .= '<th scope="column">'.__('Form', 'f13-email').'</th>';
                $v .= '<th scope="column">'.__('Fields', 'f13-email').'</th>';
                $v .= '<th scope="column">'.__('Shortcode', 'f13-email').'</th>';
                $v .= '<th scope="column">'.__('Actions', 'f13-email').'</th>';
            $v .= '</tr>';

            foreach ($this->data as $form) {
                $v .= '<tr valign="top">';
                    $v .= '<td>'.$form->title.'</td>';
                    $v .= '<td>'.$form->fields.'</td>';
                    $v .= '<td>[contact-form id='.$form->id.']</td>';
                    $v .= '<td>';
                        $v .= '<a href="'.admin_url('admin.php?page=f13-email&tab=contact-form-edit&id='.$form->id).'">Edit</a> | ';
                        $v .= '<a href="'.admin_url('admin.php?page=f13-email&tab=contact-forms&sub=1&del='.$form->id).'" onclick="return confirm(\'Are you sure you want to delete this item?\');">Delete</a>';
                    $v .= '</td>';
                $v .= '</tr>';
            }
        $v .= '</table>';

        return $v;
    }

    public function edit_form()
    {
        $v = '<a class="f13_email_header_btn f13_email_close" href="'.admin_url('admin.php?page=f13-email&tab=contact-forms').'" title="'.__('Close (unsaved settings will be lost)', 'f13-email').'"></a>';
        $v .= '<h2>Edit contact form</h2>';

        $v .= $this->msg;

        if ($this->kill) {
            return $v;
        }

        $v .= '<form method="post" action="'.admin_url('admin.php?page=f13-email&tab=contact-form-new').'">';
            $v .= '<input type="hidden" name="page" value="f13-email">';
            $v .= '<input type="hidden" name="tab" value="contact-form-edit">';
            $v .= '<input type="hidden" name="id" value="'.$this->data->id.'">';
            $v .= '<input type="hidden" name="submit" value="1">';
            $v .= '<input type="hidden" name="_wpnonce" value="'.wp_create_nonce('f13-email-forms-edit'.$this->data->id).'">';

            $v .= '<table class="form-table">';

                $v .= '<tr valign="top">';
                    $v .= '<th scope="row">'.__('Form title', 'f13-email').'</th>';
                    $v .= '<td>';
                        $v .= '<input type="text" name="title" id="title" value="'.esc_attr($this->data->title).'" style="width: 100%;">';
                    $v .= '</td>';
                $v .= '</tr>';

                $v .= '<tr valign="top">';
                    $v .= '<th scope="row">'.__('Success message', 'f13-email').'</th>';
                    $v .= '<td>';
                        $v .= '<input type="text" name="success" id="success" value="'.esc_attr($this->data->success).'" style="width: 100%;">';
                    $v .= '</td>';
                $v .= '</tr>';

                $v .= '<tr valign="top">';
                    $v .= '<th scope="row">'.__('Fields', 'f13-email').'</th>';
                    $v .= '<td>';
                        $v .= '<div class="f13-email-form-fields" id="f13-email-form-fields">';

                            foreach ($this->data->fields as $field) {
                                $v .= $this->field($field->title, $field->type, $field->required, $field->options);
                            }

                        $v .= '</div>';
                        $v .= '<span class="f13-email-add-field">'.__('Add a field', 'f13-email').'</span>';
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

    public function new_form()
    {
        $v = '<a class="f13_email_header_btn f13_email_close" href="'.admin_url('admin.php?page=f13-email&tab=contact-forms').'" title="'.__('Close (unsaved settings will be lost)', 'f13-email').'"></a>';
        $v .= '<h2>New contact form</h2>';

        $v .= $this->msg;

        if ($this->kill) {
            return $v;
        }

        $v .= '<form method="post" action="'.admin_url('admin.php?page=f13-email&tab=contact-form-new').'">';
            $v .= '<input type="hidden" name="page" value="f13-email">';
            $v .= '<input type="hidden" name="tab" value="contact-form-new">';
            $v .= '<input type="hidden" name="submit" value="1">';
            $v .= '<input type="hidden" name="_wpnonce" value="'.wp_create_nonce('f13-email-forms-new').'">';

            $v .= '<table class="form-table">';

                $v .= '<tr valign="top">';
                    $v .= '<th scope="row">'.__('Form title', 'f13-email').'</th>';
                    $v .= '<td>';
                        $v .= '<input type="text" name="title" id="title" value="'.esc_attr(filter_input(INPUT_POST, 'title')).'" style="width: 100%;">';
                    $v .= '</td>';
                $v .= '</tr>';

                $v .= '<tr valign="top">';
                    $v .= '<th scope="row">'.__('Success message', 'f13-email').'</th>';
                    $v .= '<td>';
                        $v .= '<input type="text" name="success" id="success" value="'.esc_attr(filter_input(INPUT_POST, 'success')).'" style="width: 100%;">';
                    $v .= '</td>';
                $v .= '</tr>';

                $v .= '<tr valign="top">';
                    $v .= '<th scope="row">'.__('Fields', 'f13-email').'</th>';
                    $v .= '<td>';
                        $v .= '<div class="f13-email-form-fields" id="f13-email-form-fields">';

                            $fields = filter_input(INPUT_POST, 'field_title', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

                            if (empty($fields)) {
                                $v .= $this->field();
                            } else {
                                $type = filter_input(INPUT_POST, 'field_type', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
                                $required = filter_input(INPUT_POST, 'required', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
                                $options = filter_input(INPUT_POST, 'option', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

                                $option = 0;
                                foreach ($fields as $key => $field_title) {
                                    $field_options = '';
                                    if ($type[$key] == 'radio' || $type[$key] == 'dropdown') {
                                        $field_options = $options[$option];
                                        $option++;
                                    }

                                    $v .= $this->field($field_title, $type[$key], $required[$key], $options[$key]);
                                }
                            }

                        $v .= '</div>';
                        $v .= '<span class="f13-email-add-field">'.__('Add a field', 'f13-email').'</span>';
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

    public function field($field_title = '', $field_type = '', $required = '', $options = '')
    {
        $v = '<div class="f13-email-form-field">';

            $v .= '<div style="display: inline-block; width: 40%;">';
                $v .= '<label>'.__('Field name', 'f13-email').'</label><br>';
                $v .= '<input type="text" name="field_title[]" style="width: 100%;" value="'.esc_attr($field_title).'">';
            $v .= '</div>';

            $v .= '<div style="display: inline-block; width: 25%;">';
                $v .= '<label>'.__('Type', 'f13-email').'</label><br>';
                $v .= '<select name="field_type[]" style="margin-bottom: 3px; width: 100%; border-left: 0;" class="f13-email-form-field-type">';
                    $v .= '<option value="checkbox" '.(esc_attr($field_type) == 'checkbox' ? 'selected="selected"' : '').'>'.__('Checkbox', 'f13-email').'</option>';
                    $v .= '<option value="date" '.(esc_attr($field_type) == 'date' ? 'selected="selected"' : '').'>'.__('Date', 'f13-email').'</option>';
                    $v .= '<option value="dropdown" '.(esc_attr($field_type) == 'dropdown' ? 'selected="selected"' : '').'>'.__('Dropdown', 'f13-email').'</option>';
                    $v .= '<option value="email" '.(esc_attr($field_type) == 'email' ? 'selected="selected"' : '').'>'.__('Email', 'f13-email').'</option>';
                    $v .= '<option value="number" '.(esc_attr($field_type) == 'number' ? 'selected="selected"' : '').'>'.__('Number', 'f13-email').'</option>';
                    $v .= '<option value="radio" '.(esc_attr($field_type) == 'radio' ? 'selected="selected"' : '').'>'.__('Radio', 'f13-email').'</option>';
                    $v .= '<option value="text" '.(esc_attr($field_type) == 'text' ? 'selected="selected"' : '').'>'.__('Text', 'f13-email').'</option>';
                    $v .= '<option value="textarea" '.(esc_attr($field_type) == 'textarea' ? 'selected="selected"' : '').'>'.__('Text area', 'f13-email').'</option>';
                $v .= '</select>';
            $v .= '</div>';

            $v .= '<div style="display: inline-block; width: 20%">';
                $v .= '<label>'.__('Required', 'f13-admin').'</label><br>';
                $v .= '<select name="required[]" style="margin-bottom: 3px; width: 100%; border-left: 0;" class="f13-email-form-required">';
                    $v .= '<option value="0" '.(esc_attr($required) == '0' ? 'selected="selected"' : '').'>'.__('No', 'f13-email').'</option>';
                    $v .= '<option value="1" '.(esc_attr($required) == '1' ? 'selected="selected"' : '').'>'.__('Yes', 'f13-email').'</option>';
                $v .= '</select>';
            $v .= '</div>';

            $v .= '<div style="display: inline-block; width: 15%;">';
                $v .= '<span class="f13-email-remove-field" title="'.__('Remove field', 'f13-email').'"></span>';
                $v .= '<span class="f13-email-move-field" title="'.__('Reorder field', 'f13-email').'"></span>';
            $v .= '</div>';

            $v .= '<div class="f13-email-form-field-options" '.($field_type == 'dropdown' || $field_type == 'radio' ? '' : 'style="display: none"' ).'>';
                $v .= '<div class="f13-email-form-field-option">';
                    $v .= '<label>'.__('Options (separate with | pipe)', 'f13-email').'</label><br>';
                    $v .= '<input type="text" name="option[]" style="width: 100%" value="'.esc_attr($options).'">';
                $v .= '</div>';
            $v .= '</div>';

        $v .= '</div>';

        return $v;
    }
}