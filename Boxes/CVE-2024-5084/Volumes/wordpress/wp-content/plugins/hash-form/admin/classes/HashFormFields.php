<?php

defined('ABSPATH') || die();

class HashFormFields {

    public function __construct() {
        self::include_field_class();
        add_action('wp_ajax_hashform_insert_field', array($this, 'create'));
        add_action('wp_ajax_hashform_delete_field', array($this, 'destroy'));
        add_action('wp_ajax_hashform_import_options', array($this, 'import_options'));
        //add_action('wp_ajax_hashform_duplicate_field', array($this, 'duplicate'));
    }

    public static function get_form_fields($form_id) {
        global $wpdb;
        $form_id = absint($form_id);
        $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}hashform_fields WHERE form_id=%d ORDER BY field_order", $form_id);
        $results = $wpdb->get_results($query);
        foreach ($results as $value) {
            foreach ($value as $key => $val) {
                $value->$key = maybe_unserialize($val);
            }
        }
        return $results;
    }

    public static function create() {
        if (!current_user_can('manage_options')) {
            return;
        }

        check_ajax_referer('hashform_ajax', 'nonce');
        $field_type = HashFormHelper::get_post('field_type', 'sanitize_text_field');
        $form_id = HashFormHelper::get_post('form_id', 'absint', 0);
        self::include_new_field($field_type, $form_id);
        wp_die();
    }

    public static function destroy() {
        if (!current_user_can('manage_options')) {
            return;
        }

        check_ajax_referer('hashform_ajax', 'nonce');
        $field_id = HashFormHelper::get_post('field_id', 'absint', 0);
        self::destroy_row($field_id);
        wp_die();
    }

    public static function include_new_field($field_type, $form_id) {
        $field_values = self::setup_new_field_vars($field_type, $form_id);
        $field_id = HashFormFields::create_row($field_values);
        if (!$field_id) {
            return false;
        }
        $field = self::get_field_vars($field_id);
        $field_array = self::covert_field_obj_to_array($field);
        $field_obj = HashFormFields::get_field_class($field_array['type'], $field_array);
        $field_obj->load_single_field();
    }

    public static function setup_new_field_vars($type = '', $form_id = '') {
        global $wpdb;
        $sql = $wpdb->prepare("SELECT field_order FROM {$wpdb->prefix}hashform_fields WHERE form_id=%d ORDER BY field_order DESC", $form_id);
        $field_count = $wpdb->get_var($sql);
        $values = self::get_default_field($type);
        $values['field_key'] = HashFormHelper::get_unique_key('hashform_fields', 'field_key');
        $values['form_id'] = $form_id;
        $values['field_order'] = $field_count + 1;
        return $values;
    }

    public static function covert_field_obj_to_array($field) {
        $field_array = json_decode(wp_json_encode($field), true);
        $field_options = $field_array['field_options'];
        unset($field_array['field_options']);
        return array_merge($field_array, $field_options);
    }

    public static function get_default_field($type) {
        $field_obj = HashFormFields::get_field_class($type);
        return $field_obj->get_new_field_defaults();
    }

    public static function import_options() {
        if (!current_user_can('manage_options')) {
            return;
        }

        $field_id = HashFormHelper::get_post('field_id', 'absint');
        $field = self::get_field_vars($field_id);
        if (!in_array($field->type, array('radio', 'checkbox', 'select'))) {
            return;
        }

        $field_array = self::covert_field_obj_to_array($field);
        $field_array['type'] = $field->type;
        $field_array['value'] = $field->default_value;

        $opts = htmlspecialchars_decode(HashFormHelper::get_post('opts', 'esc_html'));
        $opts = explode("\n", rtrim($opts, "\n"));
        $opts = array_map('trim', $opts);

        foreach ($opts as $opt_key => $opt) {
            $opts[$opt_key] = array(
                'label' => $opt
            );
        }

        $field_array['options'] = $opts;
        $field_obj = HashFormFields::get_field_class($field_array['type'], $field_array);
        $field_obj->show_single_option();
        wp_die();
    }

    public static function field_selection() {
        return array(
            'name' => array(
                'name' => esc_html__('Name', 'hash-form'),
                'icon' => 'hfi hfi-name',
            ),
            'email' => array(
                'name' => esc_html__('Email', 'hash-form'),
                'icon' => 'hfi hfi-email',
            ),
            'phone' => array(
                'name' => esc_html__('Phone', 'hash-form'),
                'icon' => 'hfi hfi-phone',
            ),
            'url' => array(
                'name' => esc_html__('Website/URL', 'hash-form'),
                'icon' => 'hfi hfi-url',
            ),
            'address' => array(
                'name' => esc_html__('Address', 'hash-form'),
                'icon' => 'hfi hfi-address',
            ),
            'text' => array(
                'name' => esc_html__('Text', 'hash-form'),
                'icon' => 'hfi hfi-text',
            ),
            'textarea' => array(
                'name' => esc_html__('Text Area', 'hash-form'),
                'icon' => 'hfi hfi-textarea',
            ),
            'select' => array(
                'name' => esc_html__('Dropdown', 'hash-form'),
                'icon' => 'hfi hfi-select',
            ),
            'checkbox' => array(
                'name' => esc_html__('Checkboxes', 'hash-form'),
                'icon' => 'hfi hfi-check',
            ),
            'radio' => array(
                'name' => esc_html__('Radio Buttons', 'hash-form'),
                'icon' => 'hfi hfi-radio',
            ),
            'image_select' => array(
                'name' => esc_html__('Image Selector', 'hash-form'),
                'icon' => 'hfi hfi-image-select',
            ),
            'number' => array(
                'name' => esc_html__('Number', 'hash-form'),
                'icon' => 'hfi hfi-number',
            ),
            'range_slider' => array(
                'name' => esc_html__('Range Slider', 'hash-form'),
                'icon' => 'hfi hfi-range-slider',
            ),
            'star' => array(
                'name' => esc_html__('Star', 'hash-form'),
                'icon' => 'hfi hfi-stars',
            ),
            'spinner' => array(
                'name' => esc_html__('Spinner', 'hash-form'),
                'icon' => 'hfi hfi-quantity',
            ),
            'date' => array(
                'name' => esc_html__('Date', 'hash-form'),
                'icon' => 'hfi hfi-date',
            ),
            'time' => array(
                'name' => esc_html__('Time', 'hash-form'),
                'icon' => 'hfi hfi-time',
            ),
            'upload' => array(
                'name' => esc_html__('Upload', 'hash-form'),
                'icon' => 'hfi hfi-upload',
            ),
            'user_id' => array(
                'name' => esc_html__('User ID', 'hash-form'),
                'icon' => 'hfi hfi-user-id',
            ),
            'hidden' => array(
                'name' => esc_html__('Hidden', 'hash-form'),
                'icon' => 'hfi hfi-hidden',
            ),
            'heading' => array(
                'name' => esc_html__('Heading', 'hash-form'),
                'icon' => 'hfi hfi-heading',
            ),
            'paragraph' => array(
                'name' => esc_html__('Paragraph', 'hash-form'),
                'icon' => 'hfi hfi-paragraph',
            ),
            'separator' => array(
                'name' => esc_html__('Separator', 'hash-form'),
                'icon' => 'hfi hfi-divider-dash',
            ),
            'spacer' => array(
                'name' => esc_html__('Spacer', 'hash-form'),
                'icon' => 'hfi hfi-spacer',
            ),
            'image' => array(
                'name' => esc_html__('Image', 'hash-form'),
                'icon' => 'hfi hfi-image',
            ),
            'html' => array(
                'name' => esc_html__('HTML', 'hash-form'),
                'icon' => 'hfi hfi-html',
            ),
            'captcha' => array(
                'name' => esc_html__('reCAPTCHA', 'hash-form'),
                'icon' => 'hfi hfi-recaptcha',
            )
        );
    }

    public static function create_row($values, $return = true) {
        global $wpdb, $hashform_duplicate_ids;

        $new_values = array();
        $key = isset($values['field_key']) ? sanitize_text_field($values['field_key']) : sanitize_text_field($values['name']);

        $new_values['field_key'] = sanitize_text_field(HashFormHelper::get_unique_key('hashform_fields', 'field_key'));
        $new_values['name'] = sanitize_text_field($values['name']);
        $new_values['description'] = sanitize_text_field($values['description']);
        $new_values['type'] = sanitize_text_field($values['type']);
        $new_values['field_order'] = isset($values['field_order']) ? absint($values['field_order']) : '';
        $new_values['required'] = $values['required'] ? true : false;
        $new_values['form_id'] = isset($values['form_id']) ? absint($values['form_id']) : '';
        $new_values['created_at'] = sanitize_text_field(current_time('mysql'));

        $new_values['options'] = is_array($values['options']) ? HashFormHelper::sanitize_array($values['options']) : sanitize_text_field($values['options']);

        $new_values['field_options'] = HashFormHelper::sanitize_array($values['field_options'], HashFormHelper::get_field_options_sanitize_rules());

        if (isset($values['default_value'])) {
            $field_obj = HashFormFields::get_field_class($new_values['type']);
            $new_values['default_value'] = $field_obj->sanitize_value($new_values['default_value']);
        }

        self::preserve_format_option_backslashes($new_values);

        foreach ($new_values as $key => $val) {
            if (is_array($val)) {
                $new_values[$key] = serialize($val);
            }
        }

        $query_results = $wpdb->insert($wpdb->prefix . 'hashform_fields', $new_values);
        $new_id = 0;
        if ($query_results) {
            $new_id = $wpdb->insert_id;
        }

        if (!$return) {
            return false;
        }

        if ($query_results) {
            if (isset($values['id'])) {
                $hashform_duplicate_ids[$values['id']] = $new_id;
            }
            return $new_id;
        } else {
            return false;
        }
    }

    public static function update_form_fields($id, $values) {
        global $wpdb;
        $all_fields = self::get_form_fields($id);

        foreach ($all_fields as $fid) {
            $field_id = absint($fid->id);
            if ($field_id && (isset($values['hf-form-submitted']) && in_array($field_id, $values['hf-form-submitted']))) {
                $values['edited'][] = $field_id;
            }

            $field_array[$field_id] = $fid;
        }

        if (isset($values['edited'])) {
            foreach ($values['edited'] as $field_id) {
                $default_field_cols = HashFormHelper::get_form_fields_default();

                if (isset($field_array[$field_id])) {
                    $field = $field_array[$field_id];
                } else {
                    $field = self::get_field_vars($field_id);
                }

                if (!$field) {
                    continue;
                }

                //updating the fields
                $field_obj = self::get_field_object($field);
                $update_options = $field_obj->get_default_field_options();
                foreach ($update_options as $opt => $default) {
                    $field->field_options[$opt] = isset($values['field_options'][$opt . '_' . absint($field_id)]) ? $values['field_options'][$opt . '_' . absint($field_id)] : $default;
                }

                $new_field = array(
                    'field_options' => $field->field_options,
                    'default_value' => isset($values['default_value_' . absint($field_id)]) ? $values['default_value_' . absint($field_id)] : '',
                );

                foreach ($default_field_cols as $col => $default) {
                    $default = ( $default === '' ) ? $field->{$col} : $default;
                    $new_field[$col] = isset($values['field_options'][$col . '_' . absint($field->id)]) ? $values['field_options'][$col . '_' . absint($field->id)] : $default;
                }

                if (is_array($new_field['options']) && isset($new_field['options']['000'])) {
                    unset($new_field['options']['000']);
                }

                self::update_fields($field_id, $new_field);
            }
        }
    }

    public static function update_fields($id, $values) {
        global $wpdb;

        $values['required'] = $values['required'] ? true : false;

        $values['options'] = serialize(is_array($values['options']) ? HashFormHelper::sanitize_array($values['options']) : sanitize_text_field($values['options']));

        $values['field_options'] = serialize(HashFormHelper::sanitize_array($values['field_options'], HashFormHelper::get_field_options_sanitize_rules()));

        if (isset($values['default_value'])) {
            $field_obj = HashFormFields::get_field_class($values['type']);
            $values['default_value'] = serialize($field_obj->sanitize_value($values['default_value']));
        }

        $query_results = $wpdb->update($wpdb->prefix . 'hashform_fields', $values, array('id' => $id));
        return $query_results;
    }

    public static function duplicate_fields($old_form_id, $form_id) {
        global $wpdb;

        $query = $wpdb->prepare("SELECT hfi.*, hfm.name AS form_name 
            FROM {$wpdb->prefix}hashform_fields hfi 
            LEFT OUTER JOIN {$wpdb->prefix}hashform_forms hfm 
            ON hfi.form_id = hfm.id 
            WHERE hfi.form_id=%d 
            ORDER BY 'field_order'", $old_form_id
        );
        $fields = $wpdb->get_results($query);

        foreach ((array) $fields as $field) {
            $values = array();
            self::fill_field($values, $field, $form_id);
            self::create_row($values);
        }
    }

    public static function fill_field(&$values, $field, $form_id) {
        global $wpdb;
        $values['field_key'] = HashFormHelper::get_unique_key('hashform_fields', 'field_key');
        $values['form_id'] = $form_id;
        $cols_array = array('name', 'description', 'type', 'field_order', 'field_options', 'options', 'default_value', 'required');
        foreach ($cols_array as $col) {
            $values[$col] = maybe_unserialize($field->{$col});
        }
    }

    private static function preserve_format_option_backslashes(&$values) {
        if (isset($values['field_options']['format'])) {
            $values['field_options']['format'] = self::preserve_backslashes($values['field_options']['format']);
        }
    }

    public static function preserve_backslashes($value) {
        // If backslashes have already been added, don't add them again
        if (strpos($value, '\\\\') === false) {
            $value = addslashes($value);
        }

        return $value;
    }

    public static function destroy_row($field_id) {
        global $wpdb;
        $field = self::get_field_vars($field_id);
        if (!$field) {
            return false;
        }

        $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'hashform_entry_meta WHERE field_id=%d', absint($field_id));
        $wpdb->query($query);

        $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'hashform_fields WHERE id=%d', absint($field_id));
        return $wpdb->query($query);
    }

    public static function get_field_vars($field_id) {
        if (empty($field_id))
            return;
        global $wpdb;
        $query = $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'hashform_fields WHERE id=%d', absint($field_id)); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        $results = $wpdb->get_row($query);
        if (empty($results)) {
            return $results;
        }

        self::prepare_options($results);
        return wp_unslash($results);
    }

    private static function prepare_options(&$results) {
        $results->field_options = maybe_unserialize($results->field_options);
        $results->options = maybe_unserialize($results->options);
        $results->default_value = maybe_unserialize($results->default_value);
    }

    public static function get_option($field, $option) {
        return is_array($field) ? self::get_option_in_array($field, $option) : self::get_option_in_object($field, $option);
    }

    public static function get_option_in_array($field, $option) {
        if (isset($field[$option])) {
            $this_option = $field[$option];
        } elseif (isset($field['field_options']) && is_array($field['field_options']) && isset($field['field_options'][$option])) {
            $this_option = $field['field_options'][$option];
        } else {
            $this_option = '';
        }
        return $this_option;
    }

    public static function get_option_in_object($field, $option) {
        return isset($field->field_options[$option]) ? $field->field_options[$option] : '';
    }

    public static function get_error_msg($field, $error) {
        $field_name = $field->name ? $field->name : '';
        $max_length = intval(HashFormFields::get_option($field, 'max'));

        $defaults = array(
            'invalid' => sprintf(esc_html__('%s is invalid.', 'hash-form'), $field_name),
            'blank' => sprintf(esc_html__('%s is required.', 'hash-form'), $field_name),
            'max_char' => sprintf(esc_html__('%s characters only allowed.', 'hash-form'), $max_length),
        );
        $msg = HashFormFields::get_option($field, $error);
        $msg = empty($msg) ? $defaults[$error] : $msg;
        return $msg;
    }

    public static function get_field_object($field) {
        if (!is_object($field)) {
            $field = self::get_field_vars($field);
        }
        return self::get_field_class($field->type, $field);
    }

    public static function get_field_class($field_type, $field = 0) {
        $class = self::get_field_type_class($field_type);
        $field_obj = new $class($field, $field_type);
        return $field_obj;
    }

    private static function get_field_type_class($field_type = '') {
        $type_classes = array(
            'text' => 'HashFormFieldText',
            'textarea' => 'HashFormFieldTextarea',
            'select' => 'HashFormFieldSelect',
            'radio' => 'HashFormFieldRadio',
            'checkbox' => 'HashFormFieldCheckbox',
            'image_select' => 'HashFormFieldImageSelect',
            'number' => 'HashFormFieldNumber',
            'phone' => 'HashFormFieldPhone',
            'url' => 'HashFormFieldUrl',
            'email' => 'HashFormFieldEmail',
            'user_id' => 'HashFormFieldUserID',
            'html' => 'HashFormFieldHTML',
            'hidden' => 'HashFormFieldHidden',
            'captcha' => 'HashFormFieldCaptcha',
            'name' => 'HashFormFieldName',
            'heading' => 'HashFormFieldHeading',
            'paragraph' => 'HashFormFieldParagraph',
            'image' => 'HashFormFieldImage',
            'spacer' => 'HashFormFieldSpacer',
            'range_slider' => 'HashFormFieldRangeSlider',
            'address' => 'HashFormFieldAddress',
            'star' => 'HashFormFieldStar',
            'separator' => 'HashFormFieldSeparator',
            'spinner' => 'HashFormFieldSpinner',
            'date' => 'HashFormFieldDate',
            'time' => 'HashFormFieldTime',
            'upload' => 'HashFormFieldUpload',
        );
        if ($field_type) {
            return isset($type_classes[$field_type]) ? $type_classes[$field_type] : '';
        } else {
            return $type_classes;
        }
    }

    public static function include_field_class() {
        $classes = self::get_field_type_class();
        include HASHFORM_PATH . 'admin/classes/fields/HashFormFieldType.php';
        foreach ($classes as $class) {
            include HASHFORM_PATH . 'admin/classes/fields/' . $class . '.php';
        }
    }

    public static function show_fields($fields) {
        foreach ($fields as $field) {
            $field_obj = HashFormFields::get_field_class($field['type'], $field);
            $field_obj->show_field();
        }
    }

}

new HashFormFields();
