<?php

defined('ABSPATH') || die();

class HashFormBlock {

    public function __construct() {
        add_action('init', array($this, 'register_block'));
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_block_editor_assets'));
    }

    public function register_block() {
        if (!function_exists('register_block_type')) {
            return;
        }

        register_block_type('hash-form/form-selector', array(
            'attributes' => array(
                'formId' => array(
                    'type' => 'string',
                )
            ),
            'editor_style' => 'hash-form-block-editor',
            'editor_script' => 'hash-form-block-editor',
            'render_callback' => array($this, 'get_form_html'),
        ));
    }

    public function enqueue_block_editor_assets() {
        wp_register_style('hash-form-block-editor', HASHFORM_URL . 'css/form-block.css', array('wp-edit-blocks'), HASHFORM_VERSION);
        wp_register_script('hash-form-block-editor', HASHFORM_URL . 'js/form-block.min.js', array('wp-blocks', 'wp-element', 'wp-i18n', 'wp-components'), HASHFORM_VERSION, true);

        $all_forms = HashFormHelper::get_all_forms_list_options();
        unset($all_forms['']);

        $form_block_data = array(
            'forms' => $all_forms,
            'i18n' => array(
                'title' => esc_html__('Hash Form', 'hash-form'),
                'description' => esc_html__('Select and display one of your forms.', 'hash-form'),
                'form_keywords' => array(
                    esc_html__('form', 'hash-form'),
                    esc_html__('contact', 'hash-form'),
                ),
                'form_select' => esc_html__('Select a Form', 'hash-form'),
                'form_settings' => esc_html__('Form Settings', 'hash-form'),
                'form_selected' => esc_html__('Form', 'hash-form'),
            ),
        );
        wp_localize_script('hash-form-block-editor', 'hash_form_block_data', $form_block_data);
    }

    public function get_form_html($attr) {
        $form_id = !empty($attr['formId']) ? absint($attr['formId']) : 0;
        if (empty($form_id)) {
            return '';
        }

        ob_start();
        HashFormPreview::show_form($form_id);
        return ob_get_clean();
    }

}

new HashFormBlock();