<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

class HTMegaWcsale_Admin_Settings {

    private $settings_api;

    function __construct() {
        $this->settings_api = new HTMega_Settings_API();
        add_action( 'admin_init', [ $this, 'admin_init' ] );
        add_action( 'admin_menu', [ $this, 'admin_menu' ], 224 );

        add_action( 'wsa_form_bottom_htmegawcsales_setting_tabs', [ $this, 'popup_box' ] );
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
            __( 'Sales Notification', 'htmega-addons' ),
            __( 'Sales Notification', 'htmega-addons' ), 
            'manage_options', 
            'htmeganotification', 
            array ( $this, 'plugin_page' ) 
        );

    }

    // Options page Section register
    function admin_get_settings_sections() {
        $sections = array(

            array(
                'id'    => 'htmegawcsales_setting_tabs',
                'title' => esc_html__( 'Sale Notification Settings', 'htmega-addons' )
            ),

        );
        return $sections;
    }

    // Options page field register
    protected function admin_fields_settings() {

        $settings_fields = array(
            
            'htmegawcsales_setting_tabs' => array(
                
                array(
                    'name'    => 'notification_content_typep',
                    'label'   => __( 'Notification Content Type', 'htmega-addons' ),
                    'desc'    => __( 'Select Content Type <span>( Pro )</span>', 'htmega-addons' ),
                    'type'    => 'radio',
                    'default' => 'actual',
                    'options' => array(
                        'actual' => __('Real','htmega-addons'),
                        'fakes'  => __('Fakes','htmega-addons'),
                    ),
                    'class'=>'htmegapro',
                ),

                array(
                    'name'    => 'notification_posp',
                    'label'   => __( 'Position', 'htmega-addons' ),
                    'desc'    => __( 'Sale Notification Position on frontend.( Top Left, Top Right, Bottom Right option are pro features ) <span>( Pro )</span>', 'htmega-addons' ),
                    'type'    => 'select',
                    'default' => 'bottomleft',
                    'options' => array(
                        'bottomleft'    =>__( 'Bottom Left','htmega-addons' ),
                    ),
                    'class'=>'htmegapro',
                ),

                array(
                    'name'    => 'notification_layoutp',
                    'label'   => __( 'Image Position', 'htmega-addons' ),
                    'desc'    => __( 'Notification Layout. <span>( Pro )</span>', 'htmega-addons' ),
                    'type'    => 'select',
                    'default' => 'imageleft',
                    'options' => array(
                        'imageleft'       =>__( 'Image Left','htmega-addons' ),
                    ),
                    'class'       => 'notification_real htmegapro'
                ),

                array(
                    'name'              => 'notification_limit',
                    'label'             => __( 'Limit', 'htmega-addons' ),
                    'desc'              => __( 'Order Limit for notification.', 'htmega-addons' ),
                    'min'               => 1,
                    'max'               => 100,
                    'default'           => '5',
                    'step'              => '1',
                    'type'              => 'number',
                    'sanitize_callback' => 'number',
                    'class'       => 'notification_real',
                ),

                array(
                    'name'    => 'notification_loadduration',
                    'label'   => __( 'Loading Time', 'htmega-addons' ),
                    'desc'    => __( 'Notification Loading duration.', 'htmega-addons' ),
                    'type'    => 'select',
                    'default' => '3',
                    'options' => array(
                        '2'       =>__( '2 seconds','htmega-addons' ),
                        '3'       =>__( '3 seconds','htmega-addons' ),
                        '4'       =>__( '4 seconds','htmega-addons' ),
                        '5'       =>__( '5 seconds','htmega-addons' ),
                        '6'       =>__( '6 seconds','htmega-addons' ),
                        '7'       =>__( '7 seconds','htmega-addons' ),
                        '8'       =>__( '8 seconds','htmega-addons' ),
                        '9'       =>__( '9 seconds','htmega-addons' ),
                        '10'       =>__( '10 seconds','htmega-addons' ),
                        '20'       =>__( '20 seconds','htmega-addons' ),
                        '30'       =>__( '30 seconds','htmega-addons' ),
                        '40'       =>__( '40 seconds','htmega-addons' ),
                        '50'       =>__( '50 seconds','htmega-addons' ),
                        '60'       =>__( '1 minute','htmega-addons' ),
                        '90'       =>__( '1.5 minutes','htmega-addons' ),
                        '120'       =>__( '2 minutes','htmega-addons' ),
                    ),
                ),

                array(
                    'name'    => 'notification_time_intp',
                    'label'   => __( 'Time Interval', 'htmega-addons' ),
                    'desc'    => __( 'Time between notifications. <span>( Pro )</span>', 'htmega-addons' ),
                    'type'    => 'select',
                    'default' => '4',
                    'options' => array(
                        '4'       =>__( '4 seconds','htmega-addons' ),
                    ),
                    'class' => 'htmegapro',
                ),

                array(
                    'name'    => 'notification_uptodatep',
                    'label'   => __( 'Order Upto', 'htmega-addons' ),
                    'desc'    => __( 'Do not show purchases older than.( More Options are Pro features ) <span>( Pro )</span>', 'htmega-addons' ),
                    'type'    => 'select',
                    'default' => '7',
                    'options' => array(
                        '7'   =>__( '1 week','htmega-addons' ),
                    ),
                    'class'   => 'notification_real htmegapro',
                ),

                array(
                    'name'    => 'notification_inanimationp',
                    'label'   => __( 'Animation In', 'htmega-addons' ),
                    'desc'    => __( 'Notification Enter Animation. <span>( Pro )</span>', 'htmega-addons' ),
                    'type'    => 'select',
                    'default' => 'fadeInLeft',
                    'options' => array(
                        'fadeInLeft'  =>__( 'fadeInLeft','htmega-addons' ),
                    ),
                    'class' => 'htmegapro',
                ),

                array(
                    'name'    => 'notification_outanimationp',
                    'label'   => __( 'Animation Out', 'htmega-addons' ),
                    'desc'    => __( 'Notification Out Animation. <span>( Pro )</span>', 'htmega-addons' ),
                    'type'    => 'select',
                    'default' => 'fadeOutRight',
                    'options' => array(
                        'fadeOutRight'  =>__( 'fadeOutRight','htmega-addons' ),
                    ),
                    'class' => 'htmegapro',
                ),
                
                array(
                    'name'  => 'background_colorp',
                    'label' => __( 'Background Color', 'htmega-addons' ),
                    'desc' => wp_kses_post( 'Notification Background Color. <span>( Pro )</span>', 'htmega-addons' ),
                    'type' => 'color',
                    'class'=> 'notification_real htmegapro',
                ),

                array(
                    'name'  => 'heading_colorp',
                    'label' => __( 'Heading Color', 'htmega-addons' ),
                    'desc' => wp_kses_post( 'Notification Heading Color. <span>( Pro )</span>', 'htmega-addons' ),
                    'type' => 'color',
                    'class'       => 'notification_real htmegapro',
                ),

                array(
                    'name'  => 'content_colorp',
                    'label' => __( 'Content Color', 'htmega-addons' ),
                    'desc' => wp_kses_post( 'Notification Content Color. <span>( Pro )</span>', 'htmega-addons' ),
                    'type' => 'color',
                    'class'=> 'notification_real htmegapro',
                ),

                array(
                    'name'  => 'cross_colorp',
                    'label' => __( 'Cross Icon Color', 'htmega-addons' ),
                    'desc' => wp_kses_post( 'Notification Cross Icon Color. <span>( Pro )</span>', 'htmega-addons' ),
                    'type' => 'color',
                    'class'=> 'htmegapro',
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
                        $(".htmega_table_row.pro input[type='checkbox'],.htmegapro select,.htmegapro input[type='radio']").attr("disabled", true);
                    });

                } )( jQuery );
            </script>
        <?php
        echo ob_get_clean();
    }

    // Admin Menu Page Render
    function plugin_page() {

        echo '<div class="wrap">';
            echo '<h2>'.esc_html__( 'WC Sales Notification Settings','htmega-addons' ).'</h2>';
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

new HTMegaWcsale_Admin_Settings();