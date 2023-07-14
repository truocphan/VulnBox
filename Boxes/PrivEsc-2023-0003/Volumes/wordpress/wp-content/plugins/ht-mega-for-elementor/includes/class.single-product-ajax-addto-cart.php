<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
*  groupe product Ajax add to cart
*/
class Single_Product_Ajax_Add_To_Cart{

    private static $instance = null;
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    function __construct(){
        add_action( 'wp_ajax_woocommerce_grouped_product_ajax_add_to_cart', [ $this, 'grouped_product_addto_cart' ] );
        add_action( 'wp_ajax_nopriv_woocommerce_grouped_product_ajax_add_to_cart', [ $this, 'grouped_product_addto_cart' ] );
    }

    public function grouped_product_addto_cart(){
        // phpcs:disable WordPress.Security.NonceVerification.Missing

        if ( ! isset( $_POST['product_id'] ) ) {
            return;
        }

        if( 'no' == $_POST['isgrouped'] ){
            $this->single_product_add($_POST);
        }else{
            $this->grouped_product_add($_POST);
        }
    }

    private function single_product_add($product_info){
        $product_id         = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $product_info['product_id'] ) );
        $quantity           = empty( $product_info['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $product_info['quantity'] ) );
        $variation_id       = !empty( $product_info['variation_id'] ) ? absint( $product_info['variation_id'] ) : 0;
        $variations         = is_array( $product_info['variations'] ) && !empty( $product_info['variations'] ) ? array_map( 'sanitize_text_field', $product_info['variations'] ) : array();
        $passed_validation  = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variations );
        $product_status     = get_post_status( $product_id );

        if ( $passed_validation && \WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations ) && 'publish' === $product_status ) {
            do_action( 'woocommerce_ajax_added_to_cart', $product_id );
            if ( 'yes' === get_option('woocommerce_cart_redirect_after_add') ) {
                wc_add_to_cart_message( array( $product_id => $quantity ), true );
            }
            \WC_AJAX::get_refreshed_fragments();
        } else {
            $data = array(
                'error' => true,
                'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
            );
            echo wp_send_json( $data );
        }
        wp_send_json_success();
    }

    private function grouped_product_add($product_info){
        $product_id  = absint( $product_info['product_id'] );
        $quanties = sanitize_text_field( $product_info['quantity'] );
        $product_qunatites = !empty($quanties) ?  explode( ',', $quanties ) : array();
        $product = wc_get_product( $product_id );
        $grouped_product_ids = !empty($product_info['grouped_product_id']) ? explode(',',$product_info['grouped_product_id']) : array();
        if( !empty($product_qunatites) && !empty($grouped_product_ids)){
            foreach( $grouped_product_ids as $key => $children_id ){
                $grouped_product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $children_id ) );
                $quantity           = empty( $product_qunatites[$key] ) ? 0 : wc_stock_amount( wp_unslash( $product_qunatites[$key] ) );
                $passed_validation  = apply_filters( 'woocommerce_add_to_cart_validation', true, $grouped_product_id, $quantity );
                $product_status     = get_post_status( $grouped_product_id );
                if ( $passed_validation && \WC()->cart->add_to_cart( $grouped_product_id, $quantity ) && 'publish' === $product_status ) {
                    do_action( 'woocommerce_ajax_added_to_cart', $grouped_product_id );
                    if ( 'yes' === get_option('woocommerce_cart_redirect_after_add') ) {
                        wc_add_to_cart_message( array( $product_id => $quantity ), true );
                    }
                }
            }
            \WC_AJAX::get_refreshed_fragments();
        }else{
            $data = array(
                'error' => true,
                'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
            );
            echo wp_send_json( $data );
        }
        wp_send_json_success();
    }


}

Single_Product_Ajax_Add_To_Cart::instance();