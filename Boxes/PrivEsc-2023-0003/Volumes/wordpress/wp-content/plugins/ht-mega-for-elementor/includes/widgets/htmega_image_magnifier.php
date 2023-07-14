<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Image_Magnifier extends Widget_Base {

    public function get_name() {
        return 'htmega-imagemagnifier-addons';
    }
    
    public function get_title() {
        return __( 'Image Magnifier', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-clone';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_style_depends() {
        return [
            'magnifier',
            'htmega-widgets',
        ];
    }

    public function get_script_depends() {
        return [
            'magnifier',
        ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'magifier_content',
            [
                'label' => __( 'Magnifier', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'magnifier_image',
                [
                    'label' => __( 'Thumbnail Image', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'magnifier_image_size',
                    'default' => 'large',
                    'separator' => 'none',
                ]
            );

            $this->add_control(
                'zoomable',
                [
                    'label'        => __( 'Zoomable', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default'      =>'yes'
                ]
            );

            $this->add_control(
                'zoomlabel',
                [
                    'label' => __( 'Zoom Label', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 1,
                            'max' => 10,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 2,
                    ],
                ]
            );
            
        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'imagemagnifier_style_section',
            [
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'imagemagnifier_area_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .zoom_thumbnail_area',
                ]
            );

            $this->add_responsive_control(
                'imagemagnifier_area_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .zoom_thumbnail_area' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        '{{WRAPPER}} .magnifier-thumb-wrapper img' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'imagemagnifier_area_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .zoom_thumbnail_area',
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'imagemagnifier_area_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .zoom_thumbnail_area' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'imagemagnifier_area_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .zoom_thumbnail_area' => 'margin: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'separator' => 'before',
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $id = $this->get_id();
        $image_url = wp_get_attachment_image_src( $settings['magnifier_image']['id'], $settings['magnifier_image_size_size'] );

        $magnifierimg_attr = [
            'id'                    => 'thumb-'.$id,
            'src'                   => $image_url[0],
            'alt'                   => $settings['magnifier_image_size_size'],
            'data-large-img-url'    => $settings['magnifier_image']['url'],
            'data-mode'             => 'inside',
            'data-zoomable'         => ( 'yes' === $settings['zoomable'] ) ? 'true' : 'false',
            'data-zoom'             => $settings['zoomlabel']['size'],
        ];
        $this->add_render_attribute( 'zoomimgattr', $magnifierimg_attr );
       
        ?>
            <div class="zoom_image_area">
                <div class="zoom_thumbnail_area">
                    <a class="magnifier-thumb-wrapper"><img <?php echo $this->get_render_attribute_string( 'zoomimgattr' ); ?>></a>
                </div>
            </div>
            <script>
                jQuery(document).ready(function($) {
                    'use strict';
                    m.attach({
                        thumb: '#thumb-<?php echo esc_js( $id ); ?>',
                    });
                });
            </script>
        <?php

    }

}

