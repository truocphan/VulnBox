<?php
namespace WprAddons\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Add_Remove_From_Compare setup
 *
 * @since 1.0
 */
class WPR_Add_Remove_From_Compare { 

    /**
    ** Constructor
    */
    public function __construct() {
        // add_action('init', [$this, 'register_compare_cpt']);
        add_action( 'wp_ajax_add_to_compare',[$this, 'add_to_compare'] );
        add_action( 'wp_ajax_nopriv_add_to_compare',[$this, 'add_to_compare'] );
        add_action( 'wp_ajax_remove_from_compare', [$this, 'remove_from_compare'] );
        add_action( 'wp_ajax_nopriv_remove_from_compare', [$this, 'remove_from_compare'] );
    }

    // // Register Post Type
    // function register_compare_cpt() {
    //     $args = array(
    //         'label'				  => esc_html__( 'Royal compare', 'wpr-addons' ),
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
    
    function add_to_compare() {
        if ( ! isset( $_POST['product_id'] ) ) {
            return;
        }
        $product_id = intval( $_POST['product_id'] );
        $user_id = get_current_user_id();

        // $compare = get_user_meta( get_current_user_id(), 'wpr_compare', true );
        // if ( ! $compare ) {
        //     $compare = array();
        // }
        // if ( in_array( $product_id, $compare ) ) {
        //     wp_send_json_error( array( 'message' => esc_html__('Product is already in Compare.', 'wpr-addons') ) );
        //     return;
        // }
        // $compare[] = $product_id;
        // update_user_meta( get_current_user_id(), 'wpr_compare', $compare );
        
        
        // NEW CODE
        if ($user_id > 0) {
            $compare = get_user_meta($user_id, 'wpr_compare', true);
            if (!$compare) {
                $compare = array();
            }
        } else {
            $compare = $this->get_compare_from_cookie();
        }
    
        if (in_array($product_id, $compare)) {
            wp_send_json_error(array('message' => esc_html__('Product is already in compare.', 'wpr-addons')));
            return;
        }
    
        $compare[] = $product_id;
    
        if ($user_id > 0) {
            update_user_meta($user_id, 'wpr_compare', $compare);
        } else {
            $this->set_compare_to_cookie($compare);
        }

        wp_send_json_success();
    }
    
    function remove_from_compare() {
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
    
        $compare = array_diff($compare, array($product_id));
    
        if ($user_id > 0) {
            update_user_meta($user_id, 'wpr_compare', $compare);
        } else {
            $this->set_compare_to_cookie($compare);
        }

        // $compare = get_user_meta( get_current_user_id(), 'wpr_compare', true );
        // if ( ! $compare ) {
        //     $compare = array();
        // }
        // $compare = array_diff( $compare, array( $product_id ) );
        // update_user_meta( get_current_user_id(), 'wpr_compare', $compare );

        wp_send_json_success();
    }
    
    function get_compare_from_cookie() {
        if (isset($_COOKIE['wpr_compare'])) {
            return json_decode(stripslashes($_COOKIE['wpr_compare']), true);
        } else if ( isset($_COOKIE['wpr_compare_'. get_current_blog_id() .'']) ) {
            return json_decode(stripslashes($_COOKIE['wpr_compare_'. get_current_blog_id() .'']), true);
        }
        return array();
    }
    
    function set_compare_to_cookie($compare) {
        if ( is_multisite() ) {
            setcookie('wpr_compare_'. get_current_blog_id() .'', json_encode($compare), time() + (86400 * 10), '/'); // Expires in 7 days
        } else {
            setcookie('wpr_compare', json_encode($compare), time() + (86400 * 10), '/'); // Expires in 7 days
        }
    }
}

new WPR_Add_Remove_From_Compare();