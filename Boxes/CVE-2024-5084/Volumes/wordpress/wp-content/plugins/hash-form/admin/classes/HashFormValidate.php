<?php

defined('ABSPATH') || die();

class HashFormValidate {

    public static function validate($values) {
        $errors = array();
        self::sanitize_entries($values);

        if (!isset($values['form_id']) || !isset($values['item_meta'])) {
            $errors['form'] = esc_html__('There was a problem with your submission. Please try again.', 'hash-form');
            return $errors;
        }

        if (HashFormHelper::is_admin_page() && is_user_logged_in() && (!isset($values['hashform_submit_entry_' . $values['form_id']]) || !wp_verify_nonce($values['hashform_submit_entry_' . $values['form_id']], 'hashform_submit_entry_nonce') )) {
            $errors['form'] = esc_html__('Nounce Error', 'hash-form');
        }

        $fields = HashFormFields::get_form_fields($values['form_id']);

        foreach ($fields as $field) {
            self::validate_field($field, $errors, $values);
        }

        return $errors;
    }

    public static function validate_field($field, &$errors, $values) {
        $field_id = $field->id;
        if ($field->type == 'captcha') {
            $value = isset($values['g-recaptcha-response']) ? $values['g-recaptcha-response'] : '';
        } else {
            $value = isset($values['item_meta'][$field_id]) ? $values['item_meta'][$field_id] : '';
        }

        if (!is_array($value)) {
            $value = trim($value);

            if ($field->required == '1' && empty($value)) {
                $errors['field' . $field_id] = HashFormFields::get_error_msg($field, 'blank');
            }
        }

        self::validate_field_types($errors, $field, $value);
    }

    public static function validate_field_types(&$errors, $field, $value) {
        $field_obj = HashFormFields::get_field_object($field);
        $args['errors'] = $errors;
        $args['value'] = $value;
        $args['id'] = $field->id;

        $new_errors = $field_obj->validate($args);

        if (!empty($new_errors)) {
            $errors = array_merge($errors, $new_errors);
        }
    }

    public static function sanitize_entries($values) {
        $sanitize_method = array(
            'hashform_action' => 'sanitize_title',
            'form_id' => 'absint',
            'form_key' => 'sanitize_title',
            'ip' => 'sanitize_title',
            'delivery_status' => 'rest_sanitize_boolean',
            'ip' => 'sanitize_title',
            'user_id' => 'absint',
            'status' => 'sanitize_title',
            'g-recaptcha-response' => 'sanitize_title'
        );
        return self::sanitize_request($sanitize_method, $values);
    }

    public static function sanitize_request($sanitize_method, $values) {
        $temp_values = $values;
        foreach ($temp_values as $k => $val) {
            if (isset($sanitize_method[$k])) {
                $values[$k] = call_user_func($sanitize_method[$k], $val);
            }
        }

        return $values;
    }

}
