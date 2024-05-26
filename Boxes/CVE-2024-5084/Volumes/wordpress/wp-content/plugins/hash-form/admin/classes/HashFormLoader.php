<?php

defined('ABSPATH') || die();

class HashFormLoader {

    public function __construct() {
        add_action('init', array($this, 'load_plugin_textdomain'));
        add_filter('admin_body_class', array($this, 'add_admin_class'), 999);
        add_action('admin_enqueue_scripts', array($this, 'admin_init'), 11);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'), 11);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('elementor/editor/after_enqueue_styles', array($this, 'elementor_editor_styles'));
    }

    public function load_plugin_textdomain() {
        load_plugin_textdomain('hash-form', false, basename(dirname(__FILE__)) . '/languages');
    }

    public static function add_admin_class($classes) {
        if (HashFormHelper::is_form_builder_page()) {
            $full_screen_on = self::get_full_screen_setting();
            if ($full_screen_on) {
                $classes .= ' is-fullscreen-mode';
                wp_enqueue_style('wp-edit-post'); // Load the CSS for .is-fullscreen-mode.
            }
        }
        return $classes;
    }

    private static function get_full_screen_setting() {
        global $wpdb;
        $meta_key = $wpdb->get_blog_prefix() . 'persisted_preferences';
        $prefs = get_user_meta(get_current_user_id(), $meta_key, true);
        if ($prefs && isset($prefs['core/edit-post']['fullscreenMode']))
            return $prefs['core/edit-post']['fullscreenMode'];
        return true;
    }

    public static function admin_init() {
        $page = HashFormHelper::get_var('page', 'sanitize_title');
        if (strpos($page, 'hashform') === 0) {
            wp_enqueue_script('hashform-builder', HASHFORM_URL . 'js/builder.js', array('jquery', 'jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-sortable', 'wp-i18n', 'wp-hooks', 'jquery-ui-dialog', 'hashform-select2'), HASHFORM_VERSION, true);
            wp_enqueue_script('hashform-backend', HASHFORM_URL . 'js/backend.js', array('jquery', 'jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-sortable', 'wp-i18n', 'wp-hooks', 'jquery-ui-dialog', 'jquery-ui-datepicker'), HASHFORM_VERSION, true);

            wp_localize_script('hashform-backend', 'hashform_backend_js', array(
                'nonce' => wp_create_nonce('hashform_ajax'),
            ));
        }

        if (strpos($page, 'hashform-smtp') === 0) {
            wp_enqueue_script('plugin-install');
            wp_enqueue_script('updates');
        }

        wp_enqueue_script('hashform-chosen', HASHFORM_URL . '/js/chosen.jquery.js', array('jquery'), HASHFORM_VERSION, true);
        wp_enqueue_script('hashform-select2', HASHFORM_URL . '/js/select2.min.js', array('jquery'), HASHFORM_VERSION, true);
        wp_enqueue_script('jquery-condition', HASHFORM_URL . '/js/jquery-condition.js', array('jquery'), HASHFORM_VERSION, true);
        wp_enqueue_script('wp-color-picker-alpha', HASHFORM_URL . '/js/wp-color-picker-alpha.js', array('wp-color-picker'), HASHFORM_VERSION, true);
        wp_enqueue_script('hashform-admin-settings', HASHFORM_URL . '/js/admin-settings.js', array('jquery'), HASHFORM_VERSION, true);

        wp_localize_script('hashform-admin-settings', 'hashform_admin_js_obj', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('hashform-ajax-nonce'),
            'installing_text' => esc_html__('Installing WP Mail SMTP', 'hash-form'),
            'activating_text' => esc_html__('Activating WP Mail SMTP', 'hash-form'),
            'error' => esc_html__('Error! Reload the page and try again.', 'hash-form'),
        ));

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_style('hashform-icons', HASHFORM_URL . 'fonts/hf-icons.css', array(), HASHFORM_VERSION);
        wp_enqueue_style('materialdesignicons', HASHFORM_URL . 'fonts/materialdesignicons.css', array(), HASHFORM_VERSION);
        wp_enqueue_style('hashform-chosen', HASHFORM_URL . '/css/chosen.css', array(), HASHFORM_VERSION);
        wp_enqueue_style('hashform-select2', HASHFORM_URL . '/css/select2.min.css', array(), HASHFORM_VERSION);
        wp_enqueue_style('hashform-admin', HASHFORM_URL . 'css/admin-style.css', array(), HASHFORM_VERSION);
        wp_enqueue_style('hashform-file-uploader', HASHFORM_URL . 'css/file-uploader.css', array(), HASHFORM_VERSION);
        wp_enqueue_style('hashform-admin-settings', HASHFORM_URL . '/css/admin-settings.css', array(), HASHFORM_VERSION);
        wp_enqueue_style('hashform-style', HASHFORM_URL . '/css/style.css', array(), HASHFORM_VERSION);

        $fonts_url = HashFormStyles::fonts_url();

        // Load Fonts if necessary.
        if ($fonts_url) {
            wp_enqueue_style('hashform-fonts', $fonts_url, array(), false);
        }
    }
    
    public static function elementor_editor_styles() {
        wp_enqueue_style('hashform-icons', HASHFORM_URL . 'fonts/hf-icons.css', array(), HASHFORM_VERSION);
    }

    public static function enqueue_styles() {
        wp_enqueue_style('dashicons');
        wp_enqueue_style('jquery-timepicker', HASHFORM_URL . 'css/jquery.timepicker.css', array(), HASHFORM_VERSION);
        wp_enqueue_style('hashform-file-uploader', HASHFORM_URL . 'css/file-uploader.css', array(), HASHFORM_VERSION);
        wp_enqueue_style('materialdesignicons', HASHFORM_URL . 'fonts/materialdesignicons.css', array(), HASHFORM_VERSION);
        wp_enqueue_style('hashform-style', HASHFORM_URL . 'css/style.css', array(), HASHFORM_VERSION);
        $fonts_url = HashFormStyles::fonts_url();

        if ($fonts_url) {
            wp_enqueue_style('hashform-fonts', $fonts_url, array(), false);
        }
    }

    public static function enqueue_scripts() {
        wp_enqueue_script('jquery-ui-slider');
        wp_enqueue_script('jquery-timepicker', HASHFORM_URL . 'js/jquery.timepicker.min.js', array('jquery'), HASHFORM_VERSION, true);
        wp_enqueue_script('hashform-file-uploader', HASHFORM_URL . 'js/file-uploader.js', array(), HASHFORM_VERSION, true);
        wp_localize_script('hashform-file-uploader', 'hashform_file_vars', array(
            'remove_txt' => esc_html('Remove', 'hash-form')
        ));
        wp_enqueue_script('moment', HASHFORM_URL . 'js/moment.js', array(), HASHFORM_VERSION, true);
        wp_enqueue_script('frontend', HASHFORM_URL . 'js/frontend.js', array('jquery', 'jquery-ui-datepicker', 'jquery-timepicker', 'hashform-file-uploader', 'hashform-file-uploader'), HASHFORM_VERSION, true);
        wp_localize_script('frontend', 'hashform_vars', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'ajax_nounce' => wp_create_nonce('hashform-upload-ajax-nonce'),
            'preview_img' => '',
        ));
    }

}

new HashFormLoader();
