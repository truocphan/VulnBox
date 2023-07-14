<?php

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

class HTMega_Admin_Setting{

    public function __construct(){
        add_action( 'admin_enqueue_scripts', [ $this, 'htmega_enqueue_admin_scripts' ] );
        $this->HTMega_Admin_Settings_page();

        // HT Mega Pro version check and menu remove action
        if( is_plugin_active('htmega-pro/htmega_pro.php') && ( version_compare( HTMEGA_VERSION_PRO, '1.4.3' ) <= 0 ) ){
            add_action( 'admin_init', [ $this, 'htmega_un_register_admin_menu' ] );
        }

        // Dashboard Widget.
        if( !is_plugin_active('woolentor-addons/woolentor_addons_elementor.php') ){
            add_action( 'wp_dashboard_setup', [ $this, 'dashboard_widget' ], 9999 );
        }
     
		
    }

    /*
    *  Setting Page
    */
    public function HTMega_Admin_Settings_page() {
        require_once('include/class.newsletter-data.php');
        require_once('include/class.diagnostic-data.php');
        require_once('include/template-library.php');
        require_once ('include/class.htmega-elementor-template-library.php' );
        require_once ('include/class.library-source.php' );
        require_once( HTMEGA_ADDONS_PL_PATH.'includes/class.api.php' );
        if( ! class_exists( 'HTMega_Settings_API' ) ) {
            require_once ( HTMEGA_ADDONS_PL_PATH . '/admin/include/class.settings-api.php' );
        }
        require_once( 'include/admin-setting.php' );
        if( is_plugin_active('htmega-pro/htmega_pro.php') && defined( "HTMEGA_ADDONS_PL_PATH_PRO" ) && file_exists( HTMEGA_ADDONS_PL_PATH_PRO.'includes/admin/admin-setting.php' ) ){
            require_once ( HTMEGA_ADDONS_PL_PATH_PRO.'includes/admin/admin-setting.php' );
        }

        // HT Builder
        if( htmega_get_option( 'themebuilder', 'htmega_advance_element_tabs', 'off' ) === 'on' ){
            if( is_plugin_active('htmega-pro/htmega_pro.php') ){
                require_once( HTMEGA_ADDONS_PL_PATH_PRO.'extensions/ht-builder/admin/setting.php' );
            }else{
                require_once( HTMEGA_ADDONS_PL_PATH.'extensions/ht-builder/admin/setting.php' );
            }
        }

        // Sale Notification
        if( htmega_get_option( 'salenotification', 'htmega_advance_element_tabs', 'off' ) === 'on' ){
            if( is_plugin_active('htmega-pro/htmega_pro.php') ){
                require_once( HTMEGA_ADDONS_PL_PATH_PRO.'extensions/wc-sales-notification/admin/setting.php' );
            }else{
                require_once( HTMEGA_ADDONS_PL_PATH.'extensions/wc-sales-notification/admin/setting.php' );
            }
        }

        // HT Mega Menu
        if( htmega_get_option( 'megamenubuilder', 'htmega_advance_element_tabs', 'off' ) === 'on' ){
            require_once( HTMEGA_ADDONS_PL_PATH.'extensions/ht-menu/admin/setting.php' );
        }

    }

    /*
    *   Enqueue admin scripts
    */
    public function htmega_enqueue_admin_scripts( $hook ){
        if( $hook === 'htmega-addons_page_htmega_addons_templates_library' || $hook === 'toplevel_page_htmega-addons' || $hook === 'htmega-addons_page_htmeganotification' || $hook === 'htmega-addons_page_htmegamenubl' || $hook === 'htmega-addons_page_htmegabuilder' ){
            // wp core styles
            wp_enqueue_style( 'wp-jquery-ui-dialog' );
            wp_enqueue_style( 'htmega-admin' );
            
            // wp core scripts
            wp_enqueue_script( 'jquery-ui-dialog' );
            wp_enqueue_script( 'htmega-admin' );
            
        }

    }
    /*
    *   Remove old HT Mega admin menu from (version 1.4.3 )
    */
    public function htmega_un_register_admin_menu(){
        remove_menu_page( 'htmega_addons_option_page' );
    }

    /**
     * [dashboard_widget] Register Dashboard Widget
     * @return [void]
     */
    public function dashboard_widget() {
		wp_add_dashboard_widget( 
            'hasthemes-dashboard-stories', 
            esc_html__( 'HasThemes Stories', 'htmega-addons' ), 
            [ $this, 'dashboard_hasthemes_widget' ] 
        );

		// Metaboxes Array.
		global $wp_meta_boxes;

		$dashboard_widget_list = $wp_meta_boxes['dashboard']['normal']['core'];

        $hastheme_dashboard_widget = [
            'hasthemes-dashboard-stories' => $dashboard_widget_list['hasthemes-dashboard-stories']
        ];

        $all_dashboard_widget = array_merge( $hastheme_dashboard_widget, $dashboard_widget_list );

		$wp_meta_boxes['dashboard']['normal']['core'] = $all_dashboard_widget;

	}


    /**
     * [dashboard_hasthemes_widget] Dashboard Stories Widget
     * @return [void]
     */
    public function dashboard_hasthemes_widget() {
        ob_start();
        self::load_template('widget');
        echo ob_get_clean();
    }

    /**
     * [load_template] Template load
     * @param  [string] $template template suffix
     * @return [void]
     */
    private static function load_template( $template ) {
        $tmp_file = HTMEGA_ADDONS_PL_PATH . 'admin/include/templates/dashboard-' . $template . '.php';
        if ( file_exists( $tmp_file ) ) {
            include_once( $tmp_file );
        }
    }



}

new HTMega_Admin_Setting();