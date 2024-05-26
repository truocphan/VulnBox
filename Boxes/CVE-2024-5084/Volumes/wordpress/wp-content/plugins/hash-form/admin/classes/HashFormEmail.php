<?php
defined('ABSPATH') || die();

class HashFormEmail {

    public $form;
    public $entry_id;

    public function __construct($form, $entry_id) {
        $this->form = $form;
        $this->entry_id = $entry_id;
    }

    private function get_form_settings() {
        return $this->form->settings;
    }

    public function send_email() {
        $attachments = array();
        $form_settings = $this->get_form_settings();
        $entry = HashFormEntry::get_entry_vars($this->entry_id);
        $metas = $entry->metas;

        $email_to = isset($form_settings['email_to']) ? explode(',', $form_settings['email_to']) : '';
        $email_from = isset($form_settings['email_from']) ? $form_settings['email_from'] : '';
        $email_from_name = isset($form_settings['email_from_name']) ? $form_settings['email_from_name'] : '';
        $email_subject = isset($form_settings['email_subject']) ? $form_settings['email_subject'] : '';
        $reply_to_email = isset($form_settings['reply_to_email']) ? $form_settings['reply_to_email'] : '';
        $reply_to_ar = isset($form_settings['reply_to_ar']) ? $form_settings['reply_to_ar'] : '';

        $settings = HashFormSettings::get_settings();
        $email_template = $settings['email_template'] ? sanitize_text_field($settings['email_template']) : 'template1';
        $header_image = sanitize_text_field($settings['header_image']);
        $email_msg = isset($form_settings['email_message']) ? sanitize_text_field($form_settings['email_message']) : '';
        $email_table = $this->get_entry_rows($email_template);
        $form_title = $this->form->name;
        $file_img_placeholder = HASHFORM_URL . '/img/attachment.png';

        foreach ($metas as $item => $value) {
            $reply_to_email = str_replace('#field_id_' . absint($item), $value['value'], $reply_to_email);
            $email_subject = str_replace('#field_id_' . absint($item), $value['value'], $email_subject);
            $reply_to_ar = str_replace(absint($item), $value['value'], $reply_to_ar);
            $entry_value = maybe_unserialize($value['value']);
            $entry_type = maybe_unserialize($value['type']);
            if (is_array($entry_value)) {
                if ($entry_type == 'name') {
                    $entry_value = implode(' ', array_filter($entry_value));
                } else {
                    $entry_value = implode(',<br>', array_filter($entry_value));
                }
            }
            if ($entry_type == 'upload' && trim($entry_value)) {
                $files_arr = explode(',', $entry_value);
                $upload_value = '';
                foreach ($files_arr as $file) {
                    $file_info = pathinfo($file);
                    $file_name = $file_info['basename'];
                    $file_label = $file_info['filename'];
                    $file_extension = $file_info['extension'];
                    $upload_dir = wp_upload_dir();
                    $attachments[] = $upload_dir['basedir'] . HASHFORM_UPLOAD_DIR . '/' . $file_name;

                    $upload_value .= '<a href="' . esc_url($file) . '">';
                    $upload_value .= '<img src="' . esc_url(in_array($file_extension, array('jpg', 'jpeg', 'png', 'gif', 'bmp')) ? $file : $file_img_placeholder) . '">';
                    $upload_value .= '<label>' . esc_html($file_label) . '</label>';
                    $upload_value .= '</a>';
                }
                $entry_value = $upload_value;
            }
            $email_msg = str_replace('#field_id_' . $item, $entry_value, $email_msg);
        }

        $email_msg = str_replace('#form_title', $form_title, $email_msg);
        $email_msg = str_replace('#form_details', $email_table, $email_msg);
        $email_message = empty($email_msg) ? '' : wpautop($email_msg);

        ob_start();
        include(HASHFORM_PATH . 'admin/settings/email-templates/' . $email_template . '.php');
        $email_message = ob_get_clean();

        $head = array();
        $head[] = 'Content-Type: text/html; charset=UTF-8';
        $head[] = 'From: ' . esc_html($email_from_name) . ' <' . esc_html($email_from) . '>';
        if ($reply_to_email) {
            $head[] = 'Reply-To: ' . esc_html($reply_to_email);
        }

        $recipients = array();

        foreach ($email_to as $row) {
            $recipients[] = (trim($row) == '[admin_email]') ? get_option('admin_email') : $row;
        }

        if (!empty($attachments)) {
            $mail = wp_mail($recipients, $email_subject, $email_message, $head, $attachments);
        } else {
            $mail = wp_mail($recipients, $email_subject, $email_message, $head);
        }

        if ($mail) {
            if (isset($form_settings['enable_ar']) && $form_settings['enable_ar'] == 'on') {
                $attachments = isset($attachments) ? $attachments : array();
                $from_ar = isset($form_settings['from_ar']) ? trim($form_settings['from_ar']) : '';
                $from_ar_name = isset($form_settings['from_ar_name']) && ($form_settings['from_ar_name'] != '') ? esc_html($form_settings['from_ar_name']) : esc_html__('No Name', 'hash-form');
                $email_subject = isset($form_settings['email_subject_ar']) && ($form_settings['email_subject_ar'] != '') ? esc_html($form_settings['email_subject_ar']) : esc_html__('New Form Submission', 'hash-form');
                $email_message = wpautop(isset($form_settings['email_message_ar']) ? esc_html($form_settings['email_message_ar']) : '');
                $settings = HashFormSettings::get_settings();
                $header_image = $settings['header_image'];

                ob_start();
                include(HASHFORM_PATH . 'admin/settings/email-templates/template1.php');
                $form_html = ob_get_clean();

                $from_ar = ($from_ar == '[admin_email]') ? get_option('admin_email') : esc_attr($from_ar);

                $head = array();
                $head[] = 'Content-Type: text/html; charset=UTF-8';
                $head[] = 'From: ' . esc_html($from_ar_name) . ' <' . esc_html($from_ar) . '>';
                wp_mail($reply_to_ar, $email_subject, $form_html, $head, $attachments);
            }
            $redirect_url = '';

            if ($form_settings['confirmation_type'] == 'show_page') {
                $redirect_url = get_permalink($form_settings['show_page_id']);
            } else if ($form_settings['confirmation_type'] == 'redirect_url') {
                $redirect_url = $form_settings['redirect_url_page'];
            }

            if (!empty($redirect_url)) {
                return wp_send_json(array(
                    'status' => 'redirect',
                    'message' => esc_url($redirect_url)
                ));
            }

            return wp_send_json(array(
                'status' => 'success',
                'message' => esc_html($form_settings['confirmation_message'])
            ));
        } else {
            return false;
        }
    }

    public function get_entry_rows($email_template) {
        $settings = HashFormSettings::get_settings();
        $entry = HashFormEntry::get_entry_vars($this->entry_id);
        $entry_rows = '';
        $file_img_placeholder = HASHFORM_URL . '/img/attachment.png';
        $count = 0;
        foreach ($entry->metas as $id => $value) {
            $count++;
            $title = $value['name'];
            $entry_value = maybe_unserialize($value['value']);
            $entry_type = $value['type'];
            if (is_array($entry_value)) {
                if ($entry_type == 'name') {
                    $entry_value = implode(' ', array_filter($entry_value));
                } else {
                    $entry_value = implode(',<br>', array_filter($entry_value));
                }
            }

            if ($entry_type == 'upload' && $entry_value) {
                $files_arr = explode(',', $entry_value);
                $upload_value = '';
                foreach ($files_arr as $file) {
                    $file_info = pathinfo($file);
                    $file_name = $file_info['basename'];
                    $file_extension = $file_info['extension'];

                    $upload_value .= '<div style="margin-bottom: 10px;padding-bottom: 10px;border-bottom: 1px solid #EEE;">';
                    $upload_value .= '<div><a href="' . esc_url($file) . '" target="_blank">';
                    if (in_array($file_extension, array('jpg', 'jpeg', 'png', 'gif', 'bmp'))) {
                        $upload_value .= '<img style="width:150px" src="' . esc_url($file) . '">';
                    } else {
                        $upload_value .= '<img style="width: 40px;border: 1px solid #666;border-radius: 6px;padding: 4px;" src="' . esc_url($file_img_placeholder) . '">';
                    }
                    $upload_value .= '</a></div>';
                    $upload_value .= '<label><a href="' . esc_url($file) . '" target="_blank">';
                    $upload_value .= esc_html($file_name) . '</a></label>';
                    $upload_value .= '</div>';
                }
                $entry_value = $upload_value;
            }
            $entry_rows .= call_user_func('HashFormEmail::' . $email_template, $title, $entry_value, $count);
        }
        return $entry_rows;
    }

    public static function template1($title, $entry_value, $count) {
        ob_start();
        ?>
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; margin-bottom: 25px">
            <tbody>
                <tr>
                    <th style="font-family: sans-serif; font-size: 14px; vertical-align: top;text-align:left; line-height: 18px;" valign="top"><?php echo esc_html($title); ?></th>
                </tr>
                <tr>
                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding: 10px 0 0 0; line-height: 18px;" valign="top"><?php echo esc_html($entry_value); ?></td>
                </tr>
            </tbody>
        </table>
        <?php
        $form_html = ob_get_clean();
        return $form_html;
    }

    public static function template2($title, $entry_value, $count) {
        ob_start();
        $border_style = '';

        if ($count == 1) {
            $border_style = 'border-top: 5px solid #4183D7;';
        }

        if ($count % 2 == 0) {
            $style = 'background: #f9f9ff;';
        } else {
            $style = 'background: #FFFFFF;';
        }
        ?>
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;padding: 25px; <?php echo $border_style . $style; ?>">
            <tbody>
                <tr>
                    <th style="font-family: sans-serif; font-size: 14px; vertical-align: top;text-align:left; line-height: 18px;" valign="top"><?php echo esc_html($title); ?></th>
                </tr>
                <tr>
                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding: 10px 0 0 0; line-height: 18px;" valign="top"><?php echo esc_html($entry_value); ?></td>
                </tr>
            </tbody>
        </table>
        <?php
        $form_html = ob_get_clean();
        return $form_html;
    }

    public static function template3($title, $entry_value, $count) {
        ob_start();
        ?>
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background:#FFF; margin-bottom: 25px">
            <tbody>
                <tr>
                    <th style="font-family: sans-serif; font-size: 14px; vertical-align: top;text-align:left; line-height: 18px;background: #000;color: #FFF;text-transform: uppercase;padding: 10px 20px;" valign="top"><?php echo esc_html($title); ?></th>
                </tr>
                <tr>
                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding: 10px 0 0 0; line-height: 18px;padding: 20px !important;" valign="top"><?php echo esc_html($entry_value); ?></td>
                </tr>
            </tbody>
        </table>
        <?php
        $form_html = ob_get_clean();
        return $form_html;
    }

}
