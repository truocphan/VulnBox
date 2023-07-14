<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Scroll_Image extends Widget_Base {

    public function get_name() {
        return 'htmega-scrollimage-addons';
    }
    
    public function get_title() {
        return __( 'Scroll Image', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-exchange';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_keywords() {
        return ['image scroll', 'ht mega', 'htmega', 'scroll image'];
    }

    public function get_help_url() {
        return 'https://wphtmega.com/docs/general-widgets/scroll-image-widget/';
    }

    public function get_style_depends(){
        return [
            'htmega-widgets',
        ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'button_content',
            [
                'label' => __( 'Scroll Image', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'scroll_image',
                [
                    'label' => __( 'Choose Image', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                ]
            );

            $this->add_control(
                'scroll_image_link',
                [
                    'label' => __('Custom Link', 'htmega-addons'),
                    'show_label' => false,
                    'type' => Controls_Manager::URL,
                    'placeholder' => __('https://example.com/', 'htmega-addons'),
                    'dynamic' => [
                        'active' => true,
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'scroll_image_size',
                    'default' => 'large',
                    'separator' => 'none',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'scroll_inner_image_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .ht-scroll-image .thumb',
                ]
            );

            $this->add_responsive_control(
                'scroll_image_height',
                [
                    'label' => __( 'Minimum Height', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 1,
                            'step' => 1,
                            'max'=>5000,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 600,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image .thumb' => 'min-height: {{SIZE}}{{UNIT}};',
                    ],
                    'separator' => 'after',   
                ]
            );

            $this->add_control(
                'show_badge',
                [
                    'label' => __('Show Badge', 'htmega-addons'),
                    'type'  => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );
    
            $this->add_control(
                'badge_text',
                [
                    'label' => __( 'Badge Text', 'htmega-addons' ),
                    'show_label' => false,
                    'label_block' => true,
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Badge Text', 'htmega-addons' ),
                    'dynamic' => [
                        'active' => true,
                    ],
                    'condition' => [
                        'show_badge' => 'yes',
                    ]
                ]
            );        
            
            $this->add_control(
                'speed_scroll_time',
                [
                    'label'			=> __( 'Speed', 'htmega-addons' ),
                    'type'			=> Controls_Manager::NUMBER,
                    'min' => 0,
                    'default'		=> 3,
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image .thumb'   => 'transition-duration: {{Value}}s',
                    ]
                ]
            );            

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'scroll_image_style_section',
            [
                'label' => __( 'Scroll Image Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'scroll_image_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .ht-scroll-image',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'scroll_image_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .ht-scroll-image',
                ]
            );

            $this->add_responsive_control(
                'scroll_image_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'scroll_image_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'scroll_image_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'scroll_image_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .ht-scroll-image',
                ]
            );
            
        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'scroll_badge_style_section',
            [
                'label' => __( 'Badge', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_badge' => 'yes',
                ]
            ]
        );

            $this->add_control(
                'scroll_badge_offset_toggle',
                [
                    'label' => __( 'Offset', 'htmega-addons' ),
                    'type' => Controls_Manager::POPOVER_TOGGLE,
                    'label_off' => __( 'None', 'htmega-addons' ),
                    'label_on' => __( 'Custom', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'condition'=>[
                        'show_badge' => 'yes',
                    ],
                ]
            );
    
            $this->start_popover();
    
            $this->add_responsive_control(
                'scroll_badge_left_right',
                [
                    'label' => __( 'Left-Right', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -1000,
                            'step' => 1,
                            'max'=> 1000,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image span.htmega-badge' => 'left: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );  

            $this->add_responsive_control(
                'scroll_badge_top_bottom',
                [
                    'label' => __( 'Top-Bottom', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -1000,
                            'step' => 1,
                            'max'=> 1000,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image span.htmega-badge' => 'top: {{SIZE}}{{UNIT}};',
                    ], 
                ]
            );

            $this->end_popover();            
                
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'scroll_badge_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .ht-scroll-image span.htmega-badge',
                    'condition'=>[
                        'show_badge' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'scroll_badge_text_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image span.htmega-badge' => 'color: {{VALUE}};',
                    ],
                    'condition'=>[
                        'show_badge' => 'yes',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'scroll_badge_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .ht-scroll-image span.htmega-badge',
                ]
            );

            $this->add_responsive_control(
                'scroll_badge_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image span.htmega-badge' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'scroll_badge_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image span.htmega-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'scroll_badge_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image span.htmega-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'scroll_badge_box_shadow',
                    'exclude' => [
                        'box_shadow_position',
                    ],
                    'selector' => '{{WRAPPER}} .ht-scroll-image span.htmega-badge',
                ]
            );
    
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'scroll_badge_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'exclude' => [
                        'line_height'
                    ],
                    'default' => [
                        'font_size' => ['']
                    ],
                    'selector' => '{{WRAPPER}} .ht-scroll-image span.htmega-badge',
                ]
            );            


        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $scroll_image_url = Group_Control_Image_Size::get_attachment_image_src( $settings['scroll_image']['id'], 'scroll_image_size', $settings);
        if ( !$scroll_image_url ) {
            $scroll_image_url = $settings['scroll_image']['url'];
        }
        if( 'yes'=== $settings['show_badge'] && $settings['badge_text'] ){
            $this->add_render_attribute('htbadge','class','htmega-badge');
        }
        $this->add_render_attribute('scrollimage', 'class', 'thumb');
        $this->add_render_attribute('scrollimage', 'style', 'background-image: url('.esc_url( $scroll_image_url ).');');

        // Event Link
        if ( isset( $settings['scroll_image_link'] ) ) {

            $this->add_render_attribute( 'url', 'href', $settings['scroll_image_link']['url'] );

            if ( $settings['scroll_image_link']['is_external'] ) {
                $this->add_render_attribute( 'url', 'target', '_blank' );
            }

            if ( ! empty( $settings['scroll_image_link']['nofollow'] ) ) {
                $this->add_render_attribute( 'url', 'rel', 'nofollow' );
            }
        }

        if(!empty($settings['scroll_image_link']['url'])){ ?>
            <a <?php echo $this->get_render_attribute_string( 'url' ); ?> >
                <div class="ht-scroll-image">
                    <?php if( 'yes'=== $settings['show_badge'] && $settings['badge_text'] ): ?>
                        <span <?php $this->print_render_attribute_string('htbadge'); ?>>
                            <?php echo wp_kses_post($settings['badge_text']);?>
                        </span>
                    <?php endif; ?>
                    <div <?php echo $this->get_render_attribute_string( 'scrollimage' ); ?> ></div>
                </div>                        
            </a>                    
        <?php } else{ ?>
            <div class="ht-scroll-image">
                <?php if( 'yes'=== $settings['show_badge'] && $settings['badge_text'] ): ?>
                    <span <?php $this->print_render_attribute_string('htbadge'); ?>>
                        <?php echo wp_kses_post($settings['badge_text']);?>
                    </span>
                <?php endif; ?>
                <div <?php echo $this->get_render_attribute_string( 'scrollimage' ); ?> ></div>
            </div>
        <?php } 
    }
}