<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Image_Comparison extends Widget_Base {

    public function get_name() {
        return 'htmega-imagecomparison-addons';
    }
    
    public function get_title() {
        return __( 'Image Comparison', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-image-before-after';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_style_depends() {
        return [
            'compare-image',
            'htmega-widgets',
        ];
    }

    public function get_script_depends() {
        return [
            'beerslider',
            'htmega-widgets-scripts',
        ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'image_comparison_content',
            [
                'label' => __( 'Image Comparison', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'before_image',
                [
                    'label' => __( 'Before Image', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'before_image_size',
                    'default' => 'large',
                    'separator' => 'none',
                ]
            );

            $this->add_control(
                'after_image',
                [
                    'label' => __( 'After Image', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'after_image_size',
                    'default' => 'large',
                    'separator' => 'none',
                ]
            );
            
        $this->end_controls_section();

        // Image Comparison Box Style
        $this->start_controls_section(
            'image_comparsion_style_section',
            [
                'label' => __( 'Box Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'image_comparsion_box_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-imagecomparison .beer-slider',
                ]
            );

            $this->add_responsive_control(
                'image_comparsion_box_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-imagecomparison .beer-slider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'image_comparsion_box_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-imagecomparison .beer-slider',
                ]
            );

            $this->add_responsive_control(
                'image_comparsion_box_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-imagecomparison .beer-slider' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Addition Option
        $this->start_controls_section(
            'image_comparison_addition',
            [
                'label' => __( 'Additional Setting', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'before_title',
                [
                    'label' => __( 'Before Title', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder'=>__('Before','htmega-addons'),
                ]
            );

            $this->add_control(
                'after_title',
                [
                    'label' => __( 'After Title', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder'=>__('After','htmega-addons'),
                ]
            );

            $this->add_control(
                'start_amount',
                [
                    'label' => __( 'Before Start Amount', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 25,
                ]
            );

            $this->add_control(
                'imagecomparison_laben_pos',
                [
                    'label' => __( 'Level Position', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'top',
                    'options' => [
                        'top'      => __( 'Top', 'htmega-addons' ),
                        'center'   => __( 'Center', 'htmega-addons' ),
                        'bottom'   => __( 'Bottom', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'imagecomparison_laben_postionig',
                [
                    'label' => __( 'Positioning Lebel', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .beer-reveal[data-beer-label]::after, .beer-slider[data-beer-label]::after' => 'top: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'imagecomparison_laben_pos'=>'top',
                    ]
                ]
            );

        $this->end_controls_section();

        // Style before tab section
        $this->start_controls_section(
            'before_label_style_section',
            [
                'label' => __( 'Before Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'before_title!'=>'',
                ],
            ]
        );

            $this->add_control(
                'before_title_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   =>'#212529',
                    'selectors' => [
                        '{{WRAPPER}} .beer-slider[data-beer-label]::after' => 'color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'before_title_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .beer-slider[data-beer-label]::after',
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'before_title_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .beer-slider[data-beer-label]::after',
                ]
            );

            $this->add_responsive_control(
                'before_title_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .beer-slider[data-beer-label]::after' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'separator' => 'after',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'before_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .beer-slider[data-beer-label]::after',
                ]
            );

            $this->add_responsive_control(
                'before_title_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .beer-slider[data-beer-label]::after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );
            
        $this->end_controls_section();

        // Style after tab section
        $this->start_controls_section(
            'after_label_style_section',
            [
                'label' => __( 'After Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'after_title!'=>'',
                ],
            ]
        );

            $this->add_control(
                'after_title_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   =>'#212529',
                    'selectors' => [
                        '{{WRAPPER}} .beer-reveal[data-beer-label]::after' => 'color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'after_title_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .beer-reveal[data-beer-label]::after',
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'after_title_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .beer-reveal[data-beer-label]::after',
                ]
            );

            $this->add_responsive_control(
                'after_title_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .beer-reveal[data-beer-label]::after' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'separator' => 'after',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'after_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .beer-reveal[data-beer-label]::after',
                ]
            );

            $this->add_responsive_control(
                'after_title_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .beer-reveal[data-beer-label]::after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );
            
        $this->end_controls_section();

        // Style Reveal tab section
        $this->start_controls_section(
            'reveal_style_section',
            [
                'label' => __( 'Reveal', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'reveal_border',
                'label' => __( 'Border', 'htmega-addons' ),
                'selector' => '{{WRAPPER}} .htmega-imagecomparison .beer-reveal',
            ]
        );

        $this->end_controls_section();


        // Style handler tab section
        $this->start_controls_section(
            'handler_style_section',
            [
                'label' => __( 'Handler', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'handler_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   =>'#000000',
                    'selectors' => [
                        '{{WRAPPER}} .beer-handle' => 'color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'handler_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .beer-handle',
                ]
            );

            $this->add_responsive_control(
                'handler_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .beer-handle' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'separator' => 'after',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'handler_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .beer-handle',
                ]
            );

            $this->add_control(
                'handler_width',
                [
                    'label' => __( 'Width', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 48,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .beer-handle' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'handler_height',
                [
                    'label' => __( 'Height', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 48,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .beer-handle' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Image Before tab section
        $this->start_controls_section(
            'image_before_style_section',
            [
                'label' => __( 'Image Before', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'image_before_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .beer-slider::before',
                ]
            );

        $this->end_controls_section();

        // Style Image After tab section
        $this->start_controls_section(
            'image_after_style_section',
            [
                'label' => __( 'Image After', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'image_after_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .beer-reveal::before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'image_after_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .beer-reveal',
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {
        $settings   = $this->get_settings_for_display();

        $this->add_render_attribute( 'htmega_image_comparison', 'class', 'htmega-imagecomparison htmega-label-pos-'.$settings['imagecomparison_laben_pos'] );

        // Before Image Attribute
        $this->add_render_attribute( 'image_comparison_before_attr', 'class', 'beer-slider' );
        $this->add_render_attribute( 'image_comparison_before_attr', 'data-start', $settings['start_amount'] );
        if( !empty( $settings['before_title'] ) ){
            $this->add_render_attribute( 'image_comparison_before_attr', 'data-beer-label', $settings['before_title'] );
        }

        // After Image Attribute
        $this->add_render_attribute( 'image_comparison_after_attr', 'class', 'beer-reveal' );
        if( !empty( $settings['after_title'] ) ){
            $this->add_render_attribute( 'image_comparison_after_attr', 'data-beer-label', $settings['after_title'] );
        }
       
        ?>
            <div <?php echo $this->get_render_attribute_string( 'htmega_image_comparison' ); ?> >

                <div <?php echo $this->get_render_attribute_string( 'image_comparison_before_attr' ); ?> >
                    <?php
                        echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'before_image_size', 'before_image' );
                    ?>
                    <div <?php echo $this->get_render_attribute_string( 'image_comparison_after_attr' ); ?> >
                        <?php
                            echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'after_image_size', 'after_image' );
                        ?>
                    </div>
                </div>

            </div>

        <?php

    }

}

