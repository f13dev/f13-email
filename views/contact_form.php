<?php namespace F13\Email\Views;

class Contact_form
{
    public function __construct($params = array())
    {
        foreach ($params as $k => $v) {
            $this->{$k} = $v;
        }
    }

    public function _container($content)
    {
        $v = '<div id="f13-contact-form-'.$this->data->id.'" style="position: relative;">';
            $v .= $content;
        $v .= '</div>';

        return $v;
    }

    public function form()
    {
        $v = '<div class="inner">';
            $v .= '<p>'.$this->data->title.'</p>';
            $v .= $this->msg;

            $v .= '<form method="post" class="f13-form f13-email-ajax-form" data-action="'.admin_url('admin-ajax.php').'" data-target="f13-contact-form-'.$this->data->id.'">';
                $v .= '<input type="hidden" name="action" value="f13-contact-form-submit">';
                $v .= '<input type="hidden" name="form" value="'.$this->data->id.'">';
                $v .= '<input type="hidden" name="_wpnonce" value="">';
                $v .= '<input type="hidden" name="sub" value="1">';

                foreach ($this->data->fields as $field) {
                    $required = ($field->required) ? ' <span style="color:#990000">*</span>' : '';
                    $id = 'field-'.$field->sort;
                    $error = (array_key_exists($id, $this->errors)) ? 'f13-field-error' : '';
                    if ($field->type == 'text' || $field->type == 'number' || $field->type == 'email' || $field->type == 'date') {
                        $v .= '<label for="'.$id.'">'.esc_attr($field->title).$required.'</label>';
                        $v .= '<input type="'.$field->type.'" name="'.$id.'" id="'.$id.'" value="'.esc_html(filter_input(INPUT_POST, $id)).'" class="'.$error.'">';
                    } else
                    if ($field->type == 'textarea') {
                        $v .= '<label for="'.$id.'">'.esc_attr($field->title).$required.'</label>';
                        $v .= '<textarea id="'.$id.'" name="'.$id.'" class="'.$error.'">'.esc_html(filter_input(INPUT_POST, $id)).'</textarea>';
                    } else
                    if ($field->type == 'dropdown') {
                        $v .= '<label for="'.$id.'">'.esc_attr($field->title).$required.'</label>';
                        $v .= '<select name="'.$id.'" id="'.$id.'" class="'.$error.'">';
                            $v .= '<option></option>';
                            $options = explode('|', $field->options);
                            foreach ($options as $option) {
                                $option = esc_attr($option);
                                $v .= '<option value="'.trim($option).'" '.(filter_input(INPUT_POST, $id) == trim($option) ? 'selected="selected"' : '').'>'.trim($option).'</option>';
                            }
                        $v .= '</select>';
                    } else
                    if ($field->type == 'checkbox') {
                        $v .= '<fieldset class="f13-email-checkbox '.$error.'">';
                            $v .= '<input type="checkbox" name="'.$id.'" id="'.$id.'" '.(filter_input(INPUT_POST, $id) == $field->title ? 'checked="checked"' : '').' value="'.$field->title.'">';
                            $v .= '<label for="'.$id.'">'.esc_attr($field->title).$required.'</label>';
                        $v .= '</fieldset>';
                    } else
                    if ($field->type == 'radio') {
                        $v .= '<fieldset class="f13-email-checkbox '.$error.'">';
                            if (!empty($field->title)) {
                                $v .= '<legend>'.esc_attr($field->title).$required.'</legend>';
                            }
                            $options = explode('|', $field->options);
                            foreach ($options as $key => $option) {
                                $option = esc_attr($option);
                                $v .= '<div>';
                                    $v .= '<input type="radio" name="'.$id.'" id="'.$id.'-'.$key.'" value="'.trim($option).'" '.(esc_attr(trim(filter_input(INPUT_POST, $id))) == trim($option) ? 'checked="checked"' : '' ).'>';
                                    $v .= '<label for="'.$id.'-'.$key.'">'.trim($option).'</label>';
                                $v .= '</div>';
                            }
                        $v .= '</fieldset>';
                    }
                }

                $v .= '<input type="submit" value="Submit">';

            $v .= '</form>';
        $v .= '</div>';

        return ($this->container) ? $this->_container($v) : $v;
    }
}