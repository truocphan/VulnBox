<?php

defined('ABSPATH') || die();

class HashFormImportExport {

    public function __construct() {
        // Process a settings export that generates a .json file of the form settings
        add_action('admin_init', array($this, 'process_settings_export'));
        // Process a settings export that generates a .json file of the form style
        add_action('admin_init', array($this, 'process_style_export'));
        // Process a settings import from a json file
        add_action('admin_init', array($this, 'process_settings_import'));
        // Process a style import from a json file
        add_action('admin_init', array($this, 'process_style_import'));
    }

    public function process_settings_export() {
        $id = HashFormHelper::get_post('hashform_form_id', 'absint');

        if ('export_form' != HashFormHelper::get_post('hashform_imex_action') || !$id) {
            return;
        }

        if (!wp_verify_nonce(HashFormHelper::get_post('hashform_imex_export_nonce'), 'hashform_imex_export_nonce')) {
            return;
        }

        global $wpdb;

        $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}hashform_forms WHERE id=%d", $id);
        $forms = $wpdb->get_results($query);

        foreach ($forms as $form) {
            $form_styles = $form->styles ? unserialize($form->styles) : [];
            $exdat['form_key'] = $form->form_key ? $form->form_key : '';
            $exdat['options'] = $form->options ? unserialize($form->options) : [];
            $exdat['status'] = $form->status ? $form->status : 'published';
            $exdat['settings'] = $form->settings ? unserialize($form->settings) : [];
            $exdat['styles'] = $form_styles;
            $exdat['created_at'] = $form->created_at ? $form->created_at : '';
            $fields = HashFormFields::get_form_fields($form->id);
            $exfield = array();
            foreach ($fields as $field) {
                $efield = array();
                $efield['name'] = $field->name;
                $efield['description'] = $field->description;
                $efield['type'] = $field->type;
                $efield['default_value'] = $field->default_value;
                $efield['options'] = $field->options;
                $efield['field_order'] = absint($field->field_order);
                $efield['required'] = absint($field->required);
                $efield['field_options'] = $field->field_options;
                $exfield[] = $efield;
            }
            $exdat['field'] = $exfield;

            $form_style = isset($form_styles['form_style']) && $form_styles['form_style'] ? $form_styles['form_style'] : 'default-style';

            if ($form_style == 'custom-style') {
                $form_style_id = $form_styles['form_style_template'];
                $hashform_styles = get_post_meta($form_style_id, 'hashform_styles', true);
                $hashform_styles = HashFormHelper::sanitize_array($hashform_styles, HashFormStyles::get_styles_sanitize_array());
                if ($hashform_styles) {
                    $exdat['style'] = $hashform_styles;
                }
            }

            ignore_user_abort(true);

            nocache_headers();
            header('Content-Type: application/json; charset=utf-8');
            header('Content-Disposition: attachment; filename=hf-' . $id . '-' . date('m-d-Y') . '.json');
            header("Expires: 0");

            echo wp_json_encode($exdat);
            exit;
        }
    }

    public function process_style_export() {
        $id = HashFormHelper::get_post('hashform_style_id', 'absint');

        if ('export_style' != HashFormHelper::get_post('hashform_imex_action') || !$id) {
            return;
        }

        if (!wp_verify_nonce(HashFormHelper::get_post('hashform_imex_export_nonce'), 'hashform_imex_export_nonce')) {
            return;
        }

        global $wpdb;

        $hashform_styles = get_post_meta($id, 'hashform_styles', true);
        $hashform_styles = HashFormHelper::sanitize_array($hashform_styles, HashFormStyles::get_styles_sanitize_array());

        if ($hashform_styles) {

            ignore_user_abort(true);

            nocache_headers();
            header('Content-Type: application/json; charset=utf-8');
            header('Content-Disposition: attachment; filename=hf-style-' . $id . '-' . date('m-d-Y') . '.json');
            header("Expires: 0");

            echo wp_json_encode($hashform_styles);
            exit;
        }
    }

    public function process_settings_import() {
        if (!current_user_can('manage_options')) {
            return;
        }

        $form_id = HashFormHelper::get_post('hashform_form_id', 'absint');

        if ('import_form' != HashFormHelper::get_post('hashform_imex_action') || !$form_id) {
            return;
        }

        if (!wp_verify_nonce(HashFormHelper::get_post('hashform_imex_import_nonce'), 'hashform_imex_import_nonce')) {
            return;
        }

        global $wpdb;

        $filename = sanitize_text_field(wp_unslash($_FILES['hashform_import_file']['name']));
        $extension = explode('.', $filename);
        $extension = end($extension);

        if ($extension != 'json') {
            wp_die(esc_html__('Please upload a valid .json file'));
        }

        $hashform_import_file = sanitize_text_field($_FILES['hashform_import_file']['tmp_name']);

        if (empty($hashform_import_file)) {
            wp_die(esc_html__('Please upload a file to import'));
        }

        // Retrieve the settings from the file and convert the json object to an array.
        $imdat = json_decode(file_get_contents($hashform_import_file), true);

        if (!(isset($imdat['options']) && isset($imdat['settings']) && isset($imdat['styles']))) {
            wp_die(esc_html__('Please upload a valid file to import'));
        }

        $options = HashFormHelper::recursive_parse_args($imdat['options'], HashFormHelper::get_form_options_default());
        $options = HashFormHelper::sanitize_array($options, HashFormHelper::get_form_options_sanitize_rules());

        $settings = HashFormHelper::recursive_parse_args($imdat['settings'], HashFormHelper::get_form_settings_default());
        $settings = HashFormHelper::sanitize_array($settings, HashFormHelper::get_form_settings_sanitize_rules());

        $styles = HashFormHelper::recursive_parse_args($imdat['styles'], array('form_style' => 'default-style', 'form_style_template' => ''));
        $styles = HashFormHelper::sanitize_array($styles, HashFormHelper::get_form_styles_sanitize_rules());

        if (isset($imdat['style'])) {
            $new_post = array(
                'post_type' => 'hashform-styles',
                'post_title' => 'hashform-style-' . $form_id,
                'post_status' => 'publish',
            );
            $style_id = wp_insert_post($new_post);
            $hashform_styles = HashFormHelper::recursive_parse_args($imdat['style'], HashFormStyles::default_styles());
            $hashform_styles = HashFormHelper::sanitize_array($hashform_styles, HashFormStyles::get_styles_sanitize_array());
            update_post_meta($style_id, 'hashform_styles', $hashform_styles);
            $styles['form_style_template'] = $style_id;
        }

        $form = array(
            'options' => serialize($options),
            'status' => esc_html($imdat['status']),
            'settings' => serialize($settings),
            'styles' => serialize($styles),
            'created_at' => gmdate('Y-m-d H:i:s', strtotime(esc_html($imdat['created_at']))),
        );

        if (empty($imdat['created_at'])) {
            $form['created_at'] = current_time('mysql');
        }

        $wpdb->update($wpdb->prefix . 'hashform_forms', $form, array('id' => $form_id));
        $query = $wpdb->prepare("DELETE FROM {$wpdb->prefix}hashform_fields WHERE form_id=%d", $form_id);
        $wpdb->query($query);

        if (isset($imdat['field']) && is_array($imdat['field']) && !empty($imdat['field'])) {
            foreach ($imdat['field'] as $field) {
                HashFormFields::create_row(array(
                    'name' => isset($field['name']) ? $field['name'] : '',
                    'description' => isset($field['description']) ? $field['description'] : '',
                    'type' => isset($field['type']) ? $field['type'] : 'text',
                    'default_value' => isset($field['default_value']) ? $field['default_value'] : '',
                    'options' => isset($field['options']) ? $field['options'] : '',
                    'field_order' => isset($field['field_order']) ? $field['field_order'] : '',
                    'form_id' => absint($form_id),
                    'required' => isset($field['required']) ? $field['required'] : false,
                    'field_options' => isset($field['field_options']) ? $field['field_options'] : array()
                ));
            }
        }

        $_SESSION['hashform_message'] = esc_html__('Settings Imported Successfully', 'hash-form');
    }

    public function process_style_import() {
        if (!current_user_can('manage_options')) {
            return;
        }

        $style_id = HashFormHelper::get_post('hashform_style_id', 'absint');

        if ('import_style' != HashFormHelper::get_post('hashform_imex_action') || !$style_id) {
            return;
        }

        if (!wp_verify_nonce(HashFormHelper::get_post('hashform_imex_import_nonce'), 'hashform_imex_import_nonce')) {
            return;
        }

        global $wpdb;

        $filename = sanitize_text_field(wp_unslash($_FILES['hashform_import_file']['name']));
        $extension = explode('.', $filename);
        $extension = end($extension);

        if ($extension != 'json') {
            wp_die(esc_html__('Please upload a valid .json file'));
        }

        $hashform_import_file = sanitize_text_field($_FILES['hashform_import_file']['tmp_name']);

        if (empty($hashform_import_file)) {
            wp_die(esc_html__('Please upload a file to import'));
        }

        // Retrieve the settings from the file and convert the json object to an array.
        $imdat = json_decode(file_get_contents($hashform_import_file), true);
        $hashform_styles = HashFormHelper::recursive_parse_args($imdat, HashFormStyles::default_styles());
        $hashform_styles = HashFormHelper::sanitize_array($hashform_styles, HashFormStyles::get_styles_sanitize_array());
        update_post_meta($style_id, 'hashform_styles', $hashform_styles);

        $_SESSION['hashform_message'] = esc_html__('Form Style Imported Successfully', 'hash-form');
    }

}

new HashFormImportExport();
