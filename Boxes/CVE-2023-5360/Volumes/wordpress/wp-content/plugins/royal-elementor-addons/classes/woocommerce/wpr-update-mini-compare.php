<?php
namespace WprAddons\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Update_Mini_Compare setup
 *
 * @since 1.0
 */
class WPR_Update_Mini_Compare { 

    /**
    ** Constructor
    */
    public function __construct() {
        // add_action('init', [$this, 'register_compare_cpt']);
        add_action( 'wp_ajax_update_mini_compare',[$this, 'update_mini_compare'] );
        add_action( 'wp_ajax_nopriv_update_mini_compare',[$this, 'update_mini_compare'] );
    }

    // // Register Post Type
    // function register_compare_cpt() {
    //     $args = array(
    //         'label'				  => esc_html__( 'Royal Compare', 'wpr-addons' ),
    //         'public'              => true,
    //         'publicly_queryable'  => true,
    //         'rewrite'             => false,
    //         'show_ui'             => true,
    //         'show_in_menu'        => true,
    //         'show_in_nav_menus'   => false,
    //         'exclude_from_search' => true,
    //         'capability_type'     => 'post',
    //         'supports'            => array( 'title', 'editor', 'elementor' ),
    //         'hierarchical'        => false,
    //     );
    
    //     register_post_type( 'compare', $args );
    // }

	// Add two new functions for handling cookies
	public function get_compare_from_cookie() {
        if (isset($_COOKIE['wpr_compare'])) {
            return json_decode(stripslashes($_COOKIE['wpr_compare']), true);
        } else if ( isset($_COOKIE['wpr_compare_'. get_current_blog_id() .'']) ) {
            return json_decode(stripslashes($_COOKIE['wpr_compare_'. get_current_blog_id() .'']), true);
        }
        return array();
	}
    
    function update_mini_compare() {
        if ( ! isset( $_POST['product_id'] ) ) {
            return;
        }
        
        $product_id = intval( $_POST['product_id'] );
        $user_id = get_current_user_id();

        
        if ($user_id > 0) {
            $compare = get_user_meta($user_id, 'wpr_compare', true);
            if (!$compare) {
                $compare = array();
            }
        } else {
            $compare = $this->get_compare_from_cookie();
        }

        $product = wc_get_product( $product_id );
        $product_data = [];
        if ( $product ) {
            $product_data['product_url'] = $product->get_permalink();
            $product_data['product_image'] = $product->get_image();
            $product_data['product_title'] = $product->get_title();
            $product_data['product_price'] = $product->get_price_html();
            $product_data['product_id'] = $product->get_id();
            $product_data['compare_count'] = sizeof($compare);
        }

       wp_send_json($product_data);

       wp_die();
    }
}

new WPR_Update_Mini_Compare();