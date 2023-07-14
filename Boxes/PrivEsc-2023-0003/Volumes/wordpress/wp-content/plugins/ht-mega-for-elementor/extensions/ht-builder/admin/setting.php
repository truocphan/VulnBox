<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

class HTMegaBuilder_Admin_Settings {

    function __construct() {

        $this->settings_api = new HTMega_Settings_API();
        add_action( 'admin_init', [ $this, 'admin_init' ] );
        add_action( 'admin_menu', [ $this, 'admin_menu' ], 224 );

        add_action( 'wsa_form_bottom_htmegabuilder_templatebuilder_tabs', [ $this, 'popup_box' ] );
    }

    // Admin Initialize
    function admin_init() {
        add_filter( 'htmega_admin_fields_sections', [ $this, 'fields_section' ], 10, 1 );

        //set the settings
        $this->settings_api->set_sections( $this->admin_get_settings_sections() );
        $this->settings_api->set_fields( $this->admin_fields_settings() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    // Plugins menu Register
    function admin_menu() {

        add_submenu_page(
            'htmega-addons', 
            __( 'HT Builder', 'htmega-addons' ),
            __( 'HT Builder', 'htmega-addons' ), 
            'manage_options', 
            'htmegabuilder', 
            array ( $this, 'plugin_page' ) 
        );

    }

    /**
     * Admin Fields Section Route
     *
     * @param [array] $sections
     * @return void
     */
    public function fields_section( $sections ){

        $sections['themebuilder'] = array(
            'id'    => 'htmega_themebuilder_element_tabs',
            'title' => esc_html__( 'Theme Builder', 'htmega-addons' ),
            'icon'  => 'htmega htmega-themebuilder',
            'content' => [
                'column' => 3,
                'title' => __( 'Theme Builder Widget List', 'htmega-addons' ),
                'desc'  => __( 'Freely use these elements to create your site. You can enable which you are not using, and, all associated assets will be disable to improve your site loading speed.', 'htmega-addons' ),
            ]
        );

        return $sections;

    }

    
    // Options page Section register
    function admin_get_settings_sections() {
        $sections = array(

            array(
                'id'    => 'htmegabuilder_templatebuilder_tabs',
                'title' => esc_html__( 'Theme Builder', 'htmega-addons' )
            ),

        );
        return $sections;
    }

    // Options page field register
    protected function admin_fields_settings() {

        $settings_fields = array(
            
            'htmegabuilder_templatebuilder_tabs' => array(

                array(
                    'name'    => 'single_blog_page',
                    'label'   => __( 'Single Blog Template.', 'htmega-addons' ),
                    'desc'    => __( 'You can select Single blog page from here.', 'htmega-addons' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => htmega_elementor_template()
                ),

                array(
                    'name'    => 'archive_blog_page',
                    'label'   => __( 'Blog Template.', 'htmega-addons' ),
                    'desc'    => __( 'You can select blog page from here.', 'htmega-addons' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => htmega_elementor_template()
                ),

                array(
                    'name'    => 'header_page',
                    'label'   => __( 'Header Template.', 'htmega-addons' ),
                    'desc'    => __( 'You can select header template from here.', 'htmega-addons' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => htmega_elementor_template()
                ),

                array(
                    'name'    => 'footer_page',
                    'label'   => __( 'Footer Template.', 'htmega-addons' ),
                    'desc'    => __( 'You can select footer template from here.', 'htmega-addons' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => htmega_elementor_template()
                ),

                array(
                    'name'    => 'search_pagep',
                    'label'   => __( 'Search Page Template.', 'htmega-addons' ),
                    'desc'    => __( 'You can select search page from here. <span>( Pro )</span>', 'htmega-addons' ),
                    'type'    => 'select',
                    'default' => 'select',
                    'options' => array(
                        'select'=>'Select Template',
                    ),
                    'class'=>'htmegapro',
                ),

                array(
                    'name'    => 'error_pagep',
                    'label'   => __( '404 Page Template.', 'htmega-addons' ),
                    'desc'    => __( 'You can select 404 page from here. <span>( Pro )</span>', 'htmega-addons' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => array(
                        'select'=>'Select Template',
                    ),
                    'class'   =>'htmegapro',
                ),

                array(
                    'name'    => 'coming_soon_pagep',
                    'label'   => __( 'Coming Soon Page Template.', 'htmega-addons' ),
                    'desc'    => __( 'You can select coming soon page from here. <span>( Pro )</span>', 'htmega-addons' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => array(
                        'select'=>'Select Template',
                    ),
                    'class'   =>'htmegapro',
                ),

            ),


        );
        
        return array_merge( $settings_fields );
    }

    // Pop up Box
    function popup_box(){
        ob_start();
        ?>
            <div id="htmega-dialog" title="<?php echo esc_attr( 'Go Premium' ); ?>" style="display: none;">
                <div class="htmega-content">
                    <span><i class="dashicons dashicons-warning"></i></span>
                    <p>
                        <?php
                            echo __('Purchase our','htmega-addons').' <strong><a href="'.esc_url( 'https://wphtmega.com/pricing/' ).'" target="_blank" rel="nofollow">'.__( 'premium version', 'htmega-addons' ).'</a></strong> '.__('to unlock these pro elements!','htmega-addons');
                        ?>
                    </p>
                </div>
            </div>
            <script type="text/javascript">
                ( function( $ ) {
                    
                    $(function() {
                        $( '.htmega_table_row.pro,.htmegapro label' ).click(function() {
                            $( "#htmega-dialog" ).dialog({
                                modal: true,
                                minWidth: 500,
                                buttons: {
                                    Ok: function() {
                                      $( this ).dialog( "close" );
                                    }
                                }
                            });
                        });
                        $(".htmega_table_row.pro input[type='checkbox'],.htmegapro select").attr("disabled", true);
                    });

                } )( jQuery );
            </script>
        <?php
        echo ob_get_clean();
    }

    // Admin Menu Page Render
    function plugin_page() {

        echo '<div class="wrap">';
            echo '<h2>'.esc_html__( 'HT Builder Settings','htmega-addons' ).'</h2>';
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

new HTMegaBuilder_Admin_Settings();