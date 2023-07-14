<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Template_Selector extends Widget_Base {

    public function get_name() {
        return 'htmega-template-selector';
    }
    
    public function get_title() {
        return __( 'Template Selector', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-select';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_style_depends(){
        return [
            'htmega-widgets',
        ];
    }

    public function get_script_depends() {
        return [
            'htmega-widgets-scripts',
        ];
    }

    protected function register_controls() {

        // Content
        $this->start_controls_section(
            'template_selector_content',
            [
                'label' => esc_html__( 'Template', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'template_id',
                [
                    'label' => __( 'Select Your template', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '0',
                    'options' => htmega_elementor_template(),
                ]
            );


        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {
        $settings   = $this->get_settings_for_display();

        if ( !empty( $settings['template_id'] )) {
            echo Plugin::instance()->frontend->get_builder_content_for_display( $settings['template_id'] );
        }else{
            echo '<div class="htmega_error">'.esc_html__( 'No selected template', 'htmega-addons' ).'<div/>';
        }
        
    }



}


