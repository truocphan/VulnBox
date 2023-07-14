<?php

namespace HtMega\Preset;

defined( 'ABSPATH' ) || die();

class Preset_Manage {
    public static function init() {
        add_action( 'wp_ajax_htmega_preset_design', [ __CLASS__, 'get_preset_design' ] );
    }

    public static function get_preset_design(){

        if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( $_GET['nonce'], 'htmega_preset_select' ) ) {
            wp_send_json_error( __( 'Invalid preset request', 'htmega-addons' ), 403 );
        }

        if ( empty( $_GET['widget'] ) ) {
            wp_send_json_error( __( 'Incomplete preset request', 'htmega-addons' ), 404 );
        }

        if ( ! ( $preset_designs = self::get_presets_option( $_GET['widget'] ) ) ) {
            wp_send_json_error( __( 'Preset not found', 'htmega-addons' ), 404 );
        }

        wp_send_json_success( $preset_designs, 200 );

        die();
    }

    protected static function get_presets_option($presete_name){
        $preset_path = HTMEGA_ADDONS_PL_PATH . 'admin/assets/presets/' . $presete_name . '.json'; 
        if( is_plugin_active('htmega-pro/htmega_pro.php') ){
            if(!file_exists($preset_path)){
                $preset_path = HTMEGA_ADDONS_PL_PATH_PRO . 'assets/preset-json/'. $presete_name . '.json';
            }
        }
        $preset_design = $preset_path;
        if ( ! is_readable( $preset_design ) ) {
            return false;
        }
        return file_get_contents( $preset_design );
    }
}

Preset_Manage::init();