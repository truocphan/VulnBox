<?php
namespace WprAddons\Classes;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Compare_Popup_Action setup
 *
 * @since 1.0
 */
class WPR_Compare_Popup_Action { 

    public function __construct() {
        add_action('wp_ajax_wpr_get_page_content', [$this, 'wpr_get_page_content']);
        add_action('wp_ajax_nopriv_wpr_get_page_content', [$this, 'wpr_get_page_content']);
        // add_action('rest_api_init', [$this, 'register_compare_custom_routes']);
    }
    
    // function register_compare_custom_routes() {
    //     register_rest_route('wpr-addons/v1', '/page-content/(?P<id>\d+)', array(
    //         'methods' => 'GET',
    //         'callback' => 'wpr_get_page_content'
    //     ));
    // }

    function wpr_get_page_content($request) {
        $page_id = $_POST['wpr_compare_page_id'];
        // $page_id = $request->get_param('id');
        
        // Check if the page was created with Elementor
        if (\Elementor\Plugin::$instance->db->is_built_with_elementor($page_id)) {
            $content = \Elementor\Plugin::$instance->frontend->get_builder_content($page_id);
            wp_send_json_success(array('content' => $content, 'page_url' => get_page_link( $page_id )));
            // return new WP_REST_Response(array('content' => $content), 200);
        } else {
            $page = get_post($page_id);  
            if ($page) {
                $content = apply_filters('the_content', $page->post_content);
                wp_send_json_success(array('content' => $content));
                // return new WP_REST_Response(array('content' => $content), 200);
            } else {
                wp_send_json_error(array('message' => 'Page not found'));
                // return new WP_Error('page_not_found', 'Page not found', array('status' => 404));
            }
        }
        wp_die();
    }
}

new WPR_Compare_Popup_Action();