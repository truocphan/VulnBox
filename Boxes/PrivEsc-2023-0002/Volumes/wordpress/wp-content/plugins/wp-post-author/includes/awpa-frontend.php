<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


/**
 * WP Post Author
 *
 * Allows user to get WP Post Author.
 *
 * @class   WP_Post_Author_Frontend
 */
class WP_Post_Author_Frontend
{


    /**
     * Init and hook in the integration.
     *
     * @return void
     */


    public function __construct()
    {
        $this->id = 'WP_Post_Author_Frontend';
        $this->method_title = __('WP Post Author Frontend', 'wp-post-author');
        $this->method_description = __('WP Post Author Frontend', 'wp-post-author');

        // Actions
        add_action('wp_enqueue_scripts', array($this, 'awpa_post_author_enqueue_style'));
    }


    /**
     * Loading  frontend styles.
     */

    public function awpa_post_author_enqueue_style()
    {
        wp_register_style('awpa-wp-post-author-styles', AWPA_PLUGIN_URL . 'assets/css/awpa-frontend-style.css', array(), '', 'all');
        wp_enqueue_style('awpa-wp-post-author-styles');
        wp_enqueue_style('react-date-range-styles', AWPA_PLUGIN_URL . 'assets/css/react-date-range/styles.css');
        wp_enqueue_style('react-date-range-default', AWPA_PLUGIN_URL . 'assets/css/react-date-range/default.css');
        wp_add_inline_style('awpa-wp-post-author-style', wp_post_author_add_custom_style());

        wp_register_script('awpa-custom-bg-scripts', AWPA_PLUGIN_URL . 'assets/js/awpa-frontend-scripts.js', array('jquery'));
        wp_enqueue_script('awpa-custom-bg-scripts');
        wp_enqueue_script(
            'render-block-script',
            AWPA_PLUGIN_URL . 'assets/dist/frontend.build.js',
            array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor')
        );
       

        wp_set_script_translations('render-block-script', 'wp-post-author',  AWPA_PLUGIN_URL . 'assets/dist/frontend.build.js', '/languages');
        wp_localize_script(
            'render-block-script',
            'frontend_global_data',
            array(
                'base_url' => get_site_url()
            )
        );
        wp_set_script_translations('render-block-script', 'wp-post-author',  AWPA_PLUGIN_URL . 'assets/dist/frontend.build.js', '/languages');
    }
}

$awpa_frontend = new WP_Post_Author_Frontend();
