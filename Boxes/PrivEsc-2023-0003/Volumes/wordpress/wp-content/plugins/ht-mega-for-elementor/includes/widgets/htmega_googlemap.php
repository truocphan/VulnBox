<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_GoogleMap extends Widget_Base {

    public function get_name() {
        return 'htmega-google-map-addons';
    }
    
    public function get_title() {
        return __( 'Google Map', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-google-maps';
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
            'google-map-api',
            'mapmarker',
            'htmega-widgets-scripts',
        ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'google_map_content',
            [
                'label' => __( 'Google Map', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'zoom_control',
                [
                    'label' => __( 'Zoom Control', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'htmega_map_default_zoom',
                [
                    'label' => __( 'Default Zoom', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 5,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 1,
                            'max' => 24,
                        ],
                    ],
                    'condition' => [
                        'zoom_control' => 'yes',
                    ]
                ]
            );

            $this->add_responsive_control(
                'htmega_google_map_height',
                [
                    'label' => __( 'Map Height', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 1000,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 500,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-google-map'  => 'min-height: {{SIZE}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_control(
                'htmega_center_address',
                [
                    'label' => __( 'Center Address', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXTAREA,
                    'placeholder' => __( 'Enter your center address.', 'htmega-addons' ),
                    'default' => __( 'Bangladesh', 'htmega-addons' ),
                ]
            );


            $this->add_control(
                'htmega_style_address',
                [
                    'label' => __( 'Map Style', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXTAREA,
                    'placeholder' => __( 'Enter Map Style Json Code.', 'htmega-addons' ),
                    'description'   => __( 'Go to <a href="https://snazzymaps.com/" target=_blank>Snazzy Maps</a> and Choose/Customize your Map Style. Click on your demo and copy JavaScript Style Array', 'htmega-addons' )
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'google_map_marker',
            [
                'label' => __( 'Map Marker', 'htmega-addons' ),
            ]
        );


            $repeater = new Repeater();

            $repeater->add_control(
                'marker_lat', 
                [
                    'label'       => __( 'Latitude', 'htmega-addons' ),
                    'type'        => Controls_Manager::TEXT,
                    'default'     => '31.42866311735861',
                ]
            );
            $repeater->add_control(
                'marker_lng', 
                [
                    'label'       => __( 'Longitude', 'htmega-addons' ),
                    'type'        => Controls_Manager::TEXT,
                    'default'     => '-98.61328125',
                ]
            );
            
            $repeater->add_control(
                'marker_info_box',
                [
                    'label' => __( 'Marker Info Box ', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'separator' => 'before',
                ]
            );

            $repeater->add_control(
                'marker_title', 
                [
                'label'     => esc_html__( 'Title', 'htmega-addons' ),
                'type'      => Controls_Manager::TEXTAREA,
                'default'   => __('Another Place','htmega-addons'),
                ]
            );
            
            $repeater->add_control(
                'custom_marker', 
                [
                'label'       => esc_html__( 'Custom marker', 'htmega-addons' ),
                'description' => esc_html__('Use max 32x32 px size.', 'htmega-addons'),
                'type'        => Controls_Manager::MEDIA,
                ]
            );

             $repeater->add_control(
                'htmega_marker_address_heading',
                [
                    'label' => esc_html__( 'Info Address Style', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

             $repeater->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_marker_address_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .gm-style-iw .htmega-baloon-text{{CURRENT_ITEM}}',
                    'condition' => [
                        'marker_info_box' => 'yes',
                    ],
                    'separator' => 'before',
                ]
            );

             $repeater->add_control(
                'htmega_marker_address_color',
                [
                    'label'     => esc_html__( 'Font Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .gm-style-iw .htmega-baloon-text{{CURRENT_ITEM}}' => 'color: {{VALUE}}',
                    ],
                    'separator' => 'before',
                ]
            );

             $repeater->add_control(
                'htmega_marker_address_heading_span',
                [
                    'label' => esc_html__( 'Info Address Span Style', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

             $repeater->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_marker_address_span_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .gm-style-iw .htmega-baloon-text{{CURRENT_ITEM}} span',
                    'separator' => 'before',
                ]
            );

             $repeater->add_control(
                'htmega_marker_address_Span_color',
                [
                    'label'     => esc_html__( 'Font Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .gm-style-iw .htmega-baloon-text{{CURRENT_ITEM}} span' => 'color: {{VALUE}}',
                    ],
                    'separator' => 'before',
                ]
            ); 

            $this->add_control(
            'htmega_map_marker_list',
            [
                'label'     => __( 'Marker', 'htmega-addons' ),
                'type'      => Controls_Manager::REPEATER,
                'fields'    => $repeater->get_controls(),
                'default' => [
                    [
                            'marker_info_box' => __('no','htmega-addons'),
                            'marker_title' => __('This is <span>Dhaka</span>','htmega-addons'),
                            'marker_lat'   => __('23.8103','htmega-addons'),
                            'marker_lng'   => __('90.4125','htmega-addons'),
                            'custom_marker'=> __('90.4125','htmega-addons'),
                    ],
                ],
                'title_field' => '{{{ marker_title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'google_map_fixed_address',
            [
                'label' => __( 'Map Fixed Address', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'google_map_fixed_address_control',
                [
                    'label'   => __( 'Fixed Maps Address:', 'htmega-addons' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                ]
            );

            $this->add_control(
                'htmega_fixed_address',
                [
                    'label' => __( 'Fixed Address', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXTAREA,
                    'placeholder' => __( 'Enter your fixed address.', 'htmega-addons' ),
                    'default' => '<span>ADDRESS</span><br>Iris Watson <br>P.O. Box 283 8562 Fusce Rd.<br>Frederick Nebraska 20620<br>(372) 587-2335',
                    'separator' => 'before',
                    'condition' => [
                        'google_map_fixed_address_control' => 'yes',
                    ]
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'google_map_info_address',
            [
                'label' => __( 'Maps Info Address', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'htmega_info_address_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .gm-style-iw',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'htmega_info_address_area_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .gm-style-iw',
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_info_address_area_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .gm-style-iw' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'htmega_info_address_area_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .gm-style-iw' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_info_address_area_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .gm-style-iw' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'htmega_info_address_heading',
                [
                    'label' => esc_html__( 'Info Pointer', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_info_address_background_pointer_width',
                [
                    'label'   => __( 'Pointer Width', 'htmega-addons' ),
                    'type'    => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                        ],
                    ],
                    'size_units' => ['px' ],
                    'selectors'  => [
                        '{{WRAPPER}} .gm-style .gm-style-iw-t::after' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'htmega_info_address_background_pointer',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'gradient' ],
                    'selector' => '{{WRAPPER}} .gm-style .gm-style-iw-t::after',
                ]
            );

            $this->add_control(
                'htmega_info_address_close_button',
                [
                    'label' => __( 'Info Button Close', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator' => 'before',
                ]
            );

        $this->end_controls_section();


        $this->start_controls_section(
            'google_map_fixed_address_style',
            [
                'label' => __( 'Maps Fixed Address', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'google_map_fixed_address_control' => 'yes',
                ]
            ]
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_fixed_address_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-google-map-address-yes p',
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'htmega_fixed_address_color',
                [
                    'label'     => esc_html__( 'Font Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-google-map-address-yes' => 'color: {{VALUE}}',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'htmega_fixed_address_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-google-map-address-yes',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'htmega_fixed_address_area_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-google-map-address-yes',
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_fixed_address_area_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-google-map-address-yes' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'htmega_fixed_address_area_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-google-map-address-yes' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_fixed_address_area_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-google-map-address-yes' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'fixed_address_heading_span',
                [
                    'label' => esc_html__( 'Fixed Address Span Style', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_fixed_address_span_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-google-map-address-yes p span',
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'htmega_fixed_address_Span_color',
                [
                    'label'     => esc_html__( 'Font Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-google-map-address-yes p span' => 'color: {{VALUE}}',
                    ],
                    'separator' => 'before',
                ]
            ); 

        $this->end_controls_section();


    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $id         = $this->get_id();
        
        $map_options     = [];
        $marker_opts     = [];
        $all_markerslist = [];
        foreach ( $settings['htmega_map_marker_list'] as $marker_item ) {
            $marker_opts['latitude'] = ( $marker_item['marker_lat'] ) ? $marker_item['marker_lat'] : '';
            $marker_opts['longitude'] = ( $marker_item['marker_lng'] ) ? $marker_item['marker_lng'] : '';
            $marker_opts['baloon_text'] = ( $marker_item['marker_title'] ) ? "<div class='htmega-baloon-text elementor-repeater-item-".$marker_item['_id']."'>".$marker_item['marker_title']."</div>" : '';
            $marker_opts['icon'] = ( $marker_item['custom_marker']['url'] ) ? $marker_item['custom_marker']['url'] : '';
            $marker_opts['baloon_text_fixed'] = ( $marker_item['marker_info_box'] ) ? $marker_item['marker_info_box'] : '';
            $all_markerslist[] = $marker_opts;
        };
        $map_options['zoom'] = !empty( $settings['htmega_map_default_zoom']['size'] ) ? $settings['htmega_map_default_zoom']['size'] : 5;
        $map_options['center'] = !empty( $settings['htmega_center_address'] ) ? $settings['htmega_center_address'] : 'Bangladesh';

        $this->add_render_attribute( 'googlemaps_inilasije', 'class', 'htmega-google-map-inilasije' );
        $this->add_render_attribute( 'googlemaps_address_attr', 'class', 'htmega-google-map-address-'.$settings['google_map_fixed_address_control'] );

        $this->add_render_attribute( 'googlemaps_attr', 'class', 'htmega-google-map' );
        $this->add_render_attribute( 'googlemaps_attr', 'id', 'htmega-google-map-'.$id );
        $this->add_render_attribute( 'googlemaps_attr', 'data-mapmarkers', wp_json_encode( $all_markerslist ) );
        $this->add_render_attribute( 'googlemaps_attr', 'data-mapoptions', wp_json_encode( $map_options ) );
        $this->add_render_attribute( 'googlemaps_attr', 'data-mapstyle', $settings['htmega_style_address'] );

        ?>
            <div <?php echo $this->get_render_attribute_string('googlemaps_inilasije'); ?> >    
                <div <?php echo $this->get_render_attribute_string('googlemaps_address_attr'); ?> >
                    <p><?php echo htmega_kses_desc( $settings['htmega_fixed_address'] ) ?></p>
                </div>
                <div <?php echo $this->get_render_attribute_string('googlemaps_attr'); ?> >&nbsp;</div>
            </div>

            <?php if($settings['htmega_info_address_close_button'] == 'yes'): ?>
                <style><?php echo esc_html( '#htmega-google-map-'.$id ) ?> .gm-style-iw .gm-ui-hover-effect{ display: none !important;}</style>
           <?php endif;

    }

}