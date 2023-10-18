<?php
namespace WprAddons\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Check_Product setup
 *
 * @since 1.0
 */
class WPR_Check_Product { 

    /**
    ** Constructor
    */
    public function __construct() {
        add_action( 'wp_ajax_check_product_in_wishlist', [$this, 'check_product_in_wishlist'] );
        add_action( 'wp_ajax_nopriv_check_product_in_wishlist', [$this, 'check_product_in_wishlist'] );
        add_action( 'wp_ajax_check_product_in_compare', [$this, 'check_product_in_compare'] );
        add_action( 'wp_ajax_nopriv_check_product_in_compare', [$this, 'check_product_in_compare'] );
        add_action( 'wp_ajax_check_product_in_wishlist_grid', [$this, 'check_product_in_wishlist_grid'] );
        add_action( 'wp_ajax_nopriv_check_product_in_wishlist_grid', [$this, 'check_product_in_wishlist_grid'] );
        add_action( 'wp_ajax_check_product_in_compare_grid', [$this, 'check_product_in_compare_grid'] );
        add_action( 'wp_ajax_nopriv_check_product_in_compare_grid', [$this, 'check_product_in_compare_grid'] );
    }

	// Add two new functions for handling cookies
	public function get_wishlist_from_cookie() {
        if (isset($_COOKIE['wpr_wishlist'])) {
            return json_decode(stripslashes($_COOKIE['wpr_wishlist']), true);
        } else if ( isset($_COOKIE['wpr_wishlist_'. get_current_blog_id() .'']) ) {
            return json_decode(stripslashes($_COOKIE['wpr_wishlist_'. get_current_blog_id() .'']), true);
        }
        return array();
    }
    
    function get_compare_from_cookie() {
        if (isset($_COOKIE['wpr_compare'])) {
            return json_decode(stripslashes($_COOKIE['wpr_compare']), true);
        } else if ( isset($_COOKIE['wpr_compare_'. get_current_blog_id() .'']) ) {
            return json_decode(stripslashes($_COOKIE['wpr_compare_'. get_current_blog_id() .'']), true);
        }
        return array();
    }
    
    function check_product_in_wishlist() {
		$user_id = get_current_user_id();

        if ( !isset( $_POST['product_id'] ) ) {
            return;
        }

		if ($user_id > 0) {
			$wishlist = get_user_meta( $user_id, 'wpr_wishlist', true );
		
			if ( ! $wishlist ) {
				$wishlist = array();
			}
	
		} else {
			$wishlist = $this->get_wishlist_from_cookie();
		}
        
        wp_send_json(in_array( $_POST['product_id'], $wishlist ));

       wp_die();
    }
    
    function check_product_in_compare() {
		$user_id = get_current_user_id();

        if ( !isset( $_POST['product_id'] ) ) {
            return;
        }

		if ($user_id > 0) {
			$compare = get_user_meta( $user_id, 'wpr_compare', true );
		
			if ( ! $compare ) {
				$compare = array();
			}
	
		} else {
			$compare = $this->get_compare_from_cookie();
		}
        
        wp_send_json(in_array( $_POST['product_id'], $compare ));

       wp_die();
    }
    
    function check_product_in_wishlist_grid() {
		$user_id = get_current_user_id();

		if ($user_id > 0) {
			$wishlist = get_user_meta( $user_id, 'wpr_wishlist', true );
	
		} else {
			$wishlist = $this->get_wishlist_from_cookie();
		}

		if ( ! $wishlist ) {
			$wishlist = array();
		}

        wp_send_json($wishlist);

       wp_die();
    }
    
    function check_product_in_compare_grid() {
		$user_id = get_current_user_id();
		

		if ($user_id > 0) {
			$compare = get_user_meta( $user_id, 'wpr_compare', true );
		} else {
			$compare = $this->get_compare_from_cookie();
		}
		
        if ( ! $compare ) {
            $compare = array();
        }
        
        wp_send_json($compare);

       wp_die();
    }
}

new WPR_Check_Product();