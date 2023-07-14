<?php
namespace HTMega_Builder\Elementor\Widget;

// Elementor Classes
use Elementor\Plugin as Elementor;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Bl_Site_Logo_ELement extends Widget_Base {

    public function get_name() {
        return 'bl-site-logo';
    }

    public function get_title() {
        return __( 'BL: Site Logo', 'htmega-addons' );
    }

    public function get_icon() {
        return 'eicon-site-logo';
    }

    public function get_categories() {
        return ['htmega_builder'];
    }

    protected function register_controls() {

        // Title
        $this->start_controls_section(
            'title_content',
            [
                'label' => __( 'Site Title', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'logo_type',
                [
                    'label' => __( 'Logo', 'htmega-addons' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'default' => [
                            'title' => __( 'Default', 'htmega-addons' ),
                            'icon' => 'eicon-site-logo',
                        ],
                        'custom' => [
                            'title' => __( 'Custom', 'htmega-addons' ),
                            'icon' => 'eicon-image-rollover',
                        ]
                        
                    ],
                    'default' => 'default',
                    'toggle' => true,
                ]
            );

            $this->add_control(
                'sitelogo_image',
                [
                    'label' => __( 'Site Logo', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                    'condition' =>[
                        'logo_type' => 'custom',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'logosize',
                    'default' => 'large',
                    'separator' => 'none',
                    'condition' =>[
                        'logo_type' => 'custom',
                    ],
                ]
            );

        $this->end_controls_section();


        // Style
        $this->start_controls_section(
            'logo_style_section',
            array(
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'logo_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}}',
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'logo_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'logo_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'logo_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'logo_align',
                [
                    'label'        => __( 'Alignment', 'htmega-addons' ),
                    'type'         => Controls_Manager::CHOOSE,
                    'options'      => [
                        'left'   => [
                            'title' => __( 'Left', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right'  => [
                            'title' => __( 'Right', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                        'justify' => [
                            'title' => __( 'Justified', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'prefix_class' => 'elementor-align-%s',
                    'default'      => 'left',
                    'selectors' => [
                        '{{WRAPPER}} .bl-site-logo-area' => 'text-align: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {
        $settings = $this->get_settings_for_display();
        echo "<div class='bl-site-logo-area'>";
        if( $settings['logo_type'] == 'default' ){
            if( has_custom_logo() ){ the_custom_logo(); }
        }else{
            echo '<a href="'.esc_url( home_url( '/' ) ).'">'.Group_Control_Image_Size::get_attachment_image_html( $settings, 'logosize', 'sitelogo_image' ).'</a>';
        }
        echo "</div>";
    }

    

}
