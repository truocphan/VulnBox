<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

class HTMegaMenu_Admin_Settings {

    private $settings_api;

    function __construct() {
        $this->settings_api = new HTMega_Settings_API();
        add_action( 'admin_init', [ $this, 'admin_init' ] );
        add_action( 'admin_menu', [ $this, 'admin_menu' ], 224 );
    }

    // Admin Initialize
    function admin_init() {

        // //set the settings
        $this->settings_api->set_sections( $this->admin_get_settings_sections() );
        $this->settings_api->set_fields( $this->admin_fields_settings() );

        // //initialize settings
        $this->settings_api->admin_init();
    }

    // Plugins menu Register
    function admin_menu() {

        add_submenu_page(
            'htmega-addons', 
            __( 'HT Menu', 'htmega-addons' ),
            __( 'HT Menu', 'htmega-addons' ), 
            'manage_options', 
            'htmegamenubl', 
            array ( $this, 'plugin_page' )
        );

    }

    // Options page Section register
    function admin_get_settings_sections() {
        $sections = array(

            array(
                'id'    => 'htmegamenu_setting_tabs',
                'title' => esc_html__( 'HT Menu Settings', 'htmega-addons' )
            ),

        );
        return $sections;
    }

    // Options page field register
    protected function admin_fields_settings() {

        $settings_fields = array(
            
            'htmegamenu_setting_tabs' => array(
                
                array(
                    'name'  => 'menu_items_color',
                    'label' => __( 'Menu Items Color', 'htmega-addons' ),
                    'desc' => wp_kses_post( 'Menu Items color.', 'htmega-addons' ),
                    'type' => 'color',
                ),

                array(
                    'name'  => 'menu_items_hover_color',
                    'label' => __( 'Menu Items Hover Color', 'htmega-addons' ),
                    'desc' => wp_kses_post( 'Menu Items Hover color.', 'htmega-addons' ),
                    'type' => 'color',
                ),

                array(
                    'name'  => 'sub_menu_width',
                    'label' => __( 'Sub Menu Width', 'htmega-addons' ),
                    'desc' => wp_kses_post( 'Sub Menu Width.', 'htmega-addons' ),
                    'min'               => 0,
                    'max'               => 1000,
                    'step'              => '1',
                    'type'              => 'number',
                    'sanitize_callback' => 'floatval'
                ),

                array(
                    'name'  => 'sub_menu_bg_color',
                    'label' => __( 'Sub Menu Background Color', 'htmega-addons' ),
                    'desc' => wp_kses_post( 'Menu Background Color.', 'htmega-addons' ),
                    'type' => 'color',
                ),

                array(
                    'name'  => 'sub_menu_items_color',
                    'label' => __( 'Sub Menu Items Color', 'htmega-addons' ),
                    'desc' => wp_kses_post( 'Sub Menu Items Color.', 'htmega-addons' ),
                    'type' => 'color',
                ),

                array(
                    'name'  => 'sub_menu_items_hover_color',
                    'label' => __( 'Sub Menu Items Hover Color', 'htmega-addons' ),
                    'desc' => wp_kses_post( 'Sub Menu Items Hover Color.', 'htmega-addons' ),
                    'type' => 'color',
                ),

                array(
                    'name'  => 'mega_menu_width',
                    'label' => __( 'Mega Menu Width', 'htmega-addons' ),
                    'desc' => wp_kses_post( 'Mega Menu Width.', 'htmega-addons' ),
                    'min'               => 0,
                    'max'               => 1500,
                    'step'              => '1',
                    'type'              => 'number',
                    'sanitize_callback' => 'floatval'
                ),

                array(
                    'name'  => 'mega_menu_bg_color',
                    'label' => __( 'Mega Menu Background Color', 'htmega-addons' ),
                    'desc' => wp_kses_post( 'Mega Menu Background Color.', 'htmega-addons' ),
                    'type' => 'color',
                ),

            ),


        );
        
        return array_merge( $settings_fields );
    }

    // Admin Menu Page Render
    function plugin_page() {

        echo '<div class="wrap">';
            echo '<h2>'.esc_html__( 'HT Menu Settings','htmega-addons' ).'</h2>';
            $this->save_message();
            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();
        echo '</div>';

    }

    // Save Options Message
    function save_message() {
        if( isset($_GET['settings-updated']) ) { ?>
            <div class="updated notice is-dismissible"> 
                <p><strong><?php esc_html_e('Successfully Settings Saved.', 'htmega-addons') ?></strong></p>
            </div>
            <?php
        }
    }


}

new HTMegaMenu_Admin_Settings();