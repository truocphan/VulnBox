<?php

//Exit if directly acess
defined('ABSPATH') or die('No script kiddies please!');

function apwa_block_assets()
{

    $min = (SCRIPT_DEBUG == true) ? '' : '.min';
    wp_enqueue_style(
        'wpauthor-blocks-fontawesome-front',
        plugins_url('assets/fontawesome/css/all.css', dirname(__FILE__)),
        array()
    );
    // Load the compiled styles.
    wp_enqueue_style(
        'wpauthor-frontend-block-style-css',
        plugins_url('assets/dist/blocks.style.build.css', dirname(__FILE__)),
        array()
    );
}

add_action('init', 'apwa_block_assets');

if (!function_exists('apwa_create_block')) {

    function apwa_create_block()
    {

        $min = (SCRIPT_DEBUG == true) ? '' : '.min';
        // Register our block script with WordPress
        wp_enqueue_script(
            'wpauthor-blocks-block-js',
            AWPA_PLUGIN_URL . 'assets/dist/blocks.build.js',
            array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor')
        );

        // Register our block's editor-specific CSS
        if (is_admin()):
            wp_enqueue_style(
                'wpauthor-block-edit-style',
                AWPA_PLUGIN_URL . 'assets/dist/blocks.editor.build.css',
                array('wp-edit-blocks')
            );
        endif;

        wp_enqueue_style(
            'wpauthor-blocks-fontawesome',
            plugins_url('assets/fontawesome/css/all.css', dirname(__FILE__)),
            array()
        );

        $awpa_priview_img_url = AWPA_PLUGIN_URL . 'dist/images/awpa-placeholder-img.jpg';
        $awpa_food_img_url = AWPA_PLUGIN_URL . 'src/assets/awpa-placeholder-img-square.jpg';

        global $wpdb;
        $table_name = $wpdb->prefix . 'wpa_form_builder';

        $results = $wpdb->get_results("SELECT * FROM $table_name", OBJECT);

        $allforms = array();
        foreach ($results as $res) {

            $allforms[] = array(
                'label' => $res->post_title,
                'value' => $res->id,
            );
        }

        $get_first_row = $results[0]->post_content;

        wp_localize_script(
            'wpauthor-blocks-block-js',
            'wpauthor_global_data',
            array(
                'srcUrl' => untrailingslashit(plugins_url('/', AWPA_BASE_DIR . '/dist/')),
                'rest_url' => esc_url(rest_url()),
                'formlist' => json_encode($allforms),
                'base_url' => get_site_url(),
                'single_value' => json_decode($get_first_row),

            )
        );
    }

    add_action('enqueue_block_editor_assets', 'apwa_create_block');

}

/**
 * Registers the post grid block on server
 */
function wpa_form_register()
{
    if (!function_exists('register_block_type')) {
        return;
    }

    ob_start();
    include AWPA_PLUGIN_DIR . 'assets/block.json';
    $metadata = json_decode(ob_get_clean(), true);

    /* Block attributes */
    register_block_type(
        'awpa/add-form',
        array(
            'attributes' => $metadata['attributes'],
            'render_callback' => 'wpa_form_render',
        )
    );
}

add_action('init', 'wpa_form_register');


require_once "awpa-user-login.php";