<?php namespace F13\Email\Views\Admin;

class Logs
{
    public function __construct($params = array())
    {
        foreach ($params as $k => $v) {
            $this->{$k} = $v;
        }
    }

    public function page()
    {
        $v = '<h2>'.__('Email logs', 'f13-email').'</h2>';
        $v .= '<table class="widefat" style="margin-bottom: 20px; width: 100%;" >';
            $v .= '<thead>';
                $v .= '<tr>';
                    $v .= '<th style="width: 7%;">'.__('ID', 'f13-email').'</th>';
                    $v .= '<th style="width: 13%">'.__('To', 'f13-email').'</th>';
                    $v .= '<th style="width: 62%">'.__('Body', 'f13-email').'</th>';
                    $v .= '<th style="width: 18%">'.__('Timestamp', 'f13-email').'</th>';
                $v .= '</tr>';
            $v .= '</thead>';
            $v .= '<tbody>';
                $even = false;
                foreach ($this->data->results as $log) {
                    $v .= '<tr '.($even ? 'style="background: #f6f6f6;"' : '').'>';
                        $address = (object) json_decode($log->address);
                        $v .= '<td>'.$log->id.'</td>';
                        $v .= '<td>';
                            foreach ($address as $email => $sent) {
                                $v .= $email.' ('.$sent.'), ';
                            }
                        $v .= '</td>';
                        $v .= '<td>';
                            $v .= '<div id="collapse-'.$log->id.'" data-open="expand-'.$log->id.'" class="f13-email-body-collapse f13-email-body">'.wp_strip_all_tags($log->body).'</div>';
                            $v .= '<div id="expand-'.$log->id.'" data-open="collapse-'.$log->id.'" class="f13-email-body-expand f13-email-body">'.wp_filter_post_kses(nl2br($log->body)).'</div>';
                        $v .= '</td>';
                        $v .= '<td>'.$log->sent.'</td>';
                    $v .= '</tr>';
                    $even = !$even;
                }
            $v .= '</tbody>';
            $v .= '<tfoot>';
                $v .= '<tr>';
                    $v .= '<td colspan="4">';
                        if ($this->data->page > 1) {
                            $v .= '<a class="f13-email-prev f13-email-ajax" data-target="f13-email-settings-container" data-action="'.admin_url('admin-ajax.php?action=f13-email-admin&tab=logs&p='.($this->data->page - 1)).'" style="float: left;" href="'.admin_url('admin.php?page=f13-email&tab=logs&p='.($this->data->page - 1)).'"><span class="dashicons dashicons-controls-back"></span></a>';
                        }
                        if ($this->data->page < $this->data->pages) {
                            $v .= '<a class="f13-email-next f13-email-ajax" data-target="f13-email-settings-container" data-action="'.admin_url('admin-ajax.php?action=f13-email-admin&tab=logs&p='.($this->data->page + 1)).'" style="float: right;" href="'.admin_url('admin.php?page=f13-email&tab=logs&p='.($this->data->page + 1)).'"><span class="dashicons dashicons-controls-forward"></span></a>';
                        }
                        $v .= '<div style="width: 100%; text-align: center;">'.sprintf(__('Showing %d to %d of %d results', 'f13-email'), $this->data->results_from, $this->data->results_to, $this->data->total_results).'</div>';
                    $v .= '</td>';
                $v .= '</tr>';
            $v .= '</tfoot>';
        $v .= '</table>';

        return $v;
    }
}