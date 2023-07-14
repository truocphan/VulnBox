<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_VideoPlayer extends Widget_Base {

    public function get_name() {
        return 'htmega-videoplayer-addons';
    }
    public function get_title() {
        return __( 'Video Player', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-play';
    }

    public function get_style_depends() {
        return [
            'ytplayer',
            'magnific-popup',
            'htmega-widgets',
        ];
    }
    public function get_script_depends() {
        return [
            'ytplayer',
            'magnific-popup',
            'htmega-widgets-scripts',
        ];
    }
    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'videoplayer_content',
            [
                'label' => __( 'Video Player', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'videocontainer',
                [
                    'label' => __( 'Video Container', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'self',
                    'options' => [
                        'self'         => __( 'Self', 'htmega-addons' ),
                        'popup'         => __( 'Pop Up', 'htmega-addons' ),
                    ],
                ]
            );
            $this->add_control(
                'video_url',
                [
                    'label'     => __( 'Video Url', 'htmega-addons' ),
                    'type'      => Controls_Manager::TEXT,
                    'default'   => __( 'https://www.youtube.com/watch?v=CDilI6jcpP4', 'htmega-addons' ),
                    'placeholder' => __( 'https://www.youtube.com/watch?v=CDilI6jcpP4', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'buttontext',
                [
                    'label'     => __( 'Button Text', 'htmega-addons' ),
                    'type'      => Controls_Manager::TEXT,
                    'default'   => __( 'Pop Up Button', 'htmega-addons' ),
                    'condition' =>[
                        'videocontainer' =>'popup',
                    ],
                ]
            );
            $this->add_control(
                'buttonicon_type',
                [
                    'label' => esc_html__( 'Play Button Icon', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'icon',
                    'options' => [
                        'icon' => esc_html__( 'Icon', 'htmega-addons' ),
                        'image' => esc_html__( 'Image', 'htmega-addons' ),
                    ],
                    'condition' =>[
                        'videocontainer' =>'popup',
                    ],             
                ]
            );

            $this->add_control(
                'buttonicon_image',
                [
                    'label' => __( 'Icon Image', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                    'condition' => [
                        'buttonicon_type' => 'image',
                        'videocontainer' =>'popup',
                    ]
                ]
            );
            $this->add_control(
                'buttonicon',
                [
                    'label' => __( 'Button Icon', 'htmega-addons' ),
                    'type' => Controls_Manager::ICONS,
                    'condition' => [
                        'buttonicon_type' => 'icon',
                        'videocontainer' =>'popup',
                    ]
                ]
            );
            $this->add_control(
                'controleranimation',
                [
                    'label' => __( 'Button Infinity Animation', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'yes' => __( 'Yes', 'htmega-addons' ),
                    'no' => __( 'No', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' =>[
                        'videocontainer' =>'popup',
                    ],
                ]
            );
            $this->add_control(
                'video_image',
                [
                    'label' => __( 'Video Image', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                    'condition' =>[
                        'videocontainer' =>'self',
                    ],
                ]
            );

        $this->end_controls_section();

        // Video Options
        $this->start_controls_section(
            'videoplayer_options',
            [
                'label' => __( 'Video Options', 'htmega-addons' ),
                'condition' =>[
                    'videocontainer' =>'self',
                ],
            ]
        );
            $this->add_control(
                'autoplay',
                [
                    'label' => __( 'Auto Play', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'htmega-addons' ),
                    'label_off' => __( 'No', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'soundmute',
                [
                    'label' => __( 'Sound Mute', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'htmega-addons' ),
                    'label_off' => __( 'No', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'repeatvideo',
                [
                    'label' => __( 'Repeat Video', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'htmega-addons' ),
                    'label_off' => __( 'No', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'controlerbutton',
                [
                    'label' => __( 'Show Controller Button', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'htmega-addons' ),
                    'label_off' => __( 'No', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'videosourselogo',
                [
                    'label' => __( 'Show video source Logo', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'htmega-addons' ),
                    'label_off' => __( 'No', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );
            $this->add_control(
                'videostarttime',
                [
                    'label' => __( 'Video Start Time', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 5,
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'htmega_video_style_section',
            [
                'label' => __( 'Video Box Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'htmega_video_background',
                'label' => __( 'Background', 'htmega-addons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .htmega-player-container',
            ]
        );
        $this->add_responsive_control(
            'htmega_video_padding',
            [
                'label' => __( 'Padding', 'htmega-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-player-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'htmega_video_border',
                'label' => __( 'Border', 'htmega-addons' ),
                'selector' => '{{WRAPPER}} .htmega-player-container',
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'htmega_video_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-player-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'htmega_videoshadow',
                'label' => __( 'Box Shadow', 'htmega-addons' ),
                'selector' => '{{WRAPPER}} .htmega-player-container',
            ]
        );

            $this->add_responsive_control(
                'video_style_align',
                [
                    'label' => __( 'Alignment', 'htmega-addons' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-player-container' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'separator' =>'before',
                    'condition'=>[
                        'videocontainer' =>'popup', 
                    ]
                ]
            );

        $this->end_controls_section();

        // Style Button section
        $this->start_controls_section(
            'video_button_style',
            [
                'label' => __( 'Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'videocontainer' =>'popup',
                ],
            ]
        );
            $this->start_controls_tabs('video_button_style_tabs');
                $this->start_controls_tab(
                    'video_button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                $this->add_control(
                    'video_button_color',
                    [
                        'label' => __( 'Color', 'htmega-addons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#18012c',
                        'selectors' => [
                            '{{WRAPPER}} .htmega-player-container .magnify-video-active' => 'color: {{VALUE}};',
                            '{{WRAPPER}} .htmega-player-container .magnify-video-active svg path' => 'fill: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name' => 'video_button_background',
                        'label' => __( 'Background', 'htmega-addons' ),
                        'types' => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} .htmega-player-container .magnify-video-active',
                    ]
                );

                $this->add_control(
                    'video_button_fontsize',
                    [
                        'label' => __( 'Font Size', 'htmega-addons' ),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                            'px' => [
                                'min' => 0,
                                'max' => 100,
                                'step' => 1,
                            ],
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        'default' => [
                            'unit' => 'px',
                            'size' => 40,
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .htmega-player-container .magnify-video-active' => 'font-size: {{SIZE}}{{UNIT}};',
                            '{{WRAPPER}} .htmega-player-container .magnify-video-active svg' => 'width: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );

                $this->add_responsive_control(
                    'video_button_margin',
                    [
                        'label' => __( 'Margin', 'htmega-addons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors' => [
                            '{{WRAPPER}} .htmega-player-container .magnify-video-active' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            '{{WRAPPER}} .htmega-player-container .magnify-video-active svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'separator' =>'before',
                    ]
                );

                $this->add_responsive_control(
                    'video_button_padding',
                    [
                        'label' => __( 'Padding', 'htmega-addons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors' => [
                            '{{WRAPPER}} .htmega-player-container .magnify-video-active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'separator' =>'before',
                    ]
                );

                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'video_button_border',
                        'label' => __( 'Border', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .htmega-player-container .magnify-video-active',
                    ]
                );
                $this->add_control(
                    'video_button_animation_color',
                    [
                        'label' => __( 'Animation Border Color', 'htmega-addons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#ffffff',
                        'selectors' => [
                            '{{WRAPPER}} .htmega-video-mark .htmega-wave-pulse::after, {{WRAPPER}} .htmega-video-mark .htmega-wave-pulse::before' => 'border-color: {{VALUE}};',
                        ],
                        'condition' =>[
                            'controleranimation' =>'yes',
                        ],
                    ]
                );
                $this->add_responsive_control(
                    'video_button_border_radius',
                    [
                        'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'selectors' => [
                            '{{WRAPPER}} .htmega-player-container .magnify-video-active' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        ],
                    ]
                );

            $this->end_controls_tab();// Normal Tab

            // Hover Tab
            $this->start_controls_tab(
                'video_button_style_hover_tab',
                [
                    'label' => __( 'Hover', 'htmega-addons' ),
                ]
            );
                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'video_button_hover_border',
                        'label' => __( 'Border', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .htmega-player-container .magnify-video-active:hover',
                    ]
                );
                $this->add_responsive_control(
                    'video_button_border_hover_radius',
                    [
                        'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'selectors' => [
                            '{{WRAPPER}} .htmega-player-container .magnify-video-active:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        ],
                    ]
                );
                $this->add_control(
                    'video_button_hover_color',
                    [
                        'label' => __( 'Color', 'htmega-addons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#ffffff',
                        'selectors' => [
                            '{{WRAPPER}} .htmega-player-container .magnify-video-active:hover' => 'color: {{VALUE}};',
                            '{{WRAPPER}} .htmega-player-container .magnify-video-active:hover svg path' => 'fill: {{VALUE}};',
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name' => 'video_button_hover_background',
                        'label' => __( 'Background', 'htmega-addons' ),
                        'types' => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} .htmega-player-container .magnify-video-active:hover',
                    ]
                );

            $this->end_controls_tabs(); // Hover tab end
        $this->end_controls_section();
    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $buttonicon_type =  isset( $settings['buttonicon_type'] ) ? $settings['buttonicon_type'] : 'icon';
        $buttonicon_image =  isset( $settings['buttonicon_image']['url'] ) ? $settings['buttonicon_image']['url'] : '';
        $controleranimation =  !empty( $settings['controleranimation'] ) ? $settings['controleranimation'] : 'no';

        $this->add_render_attribute( 'htmega_button', 'class', 'htmega-button' );

        if( $settings['videocontainer'] == 'self' ){
            $player_options_settings = [
                'videoURL'          => !empty( $settings['video_url'] ) ? $settings['video_url'] : 'https://www.youtube.com/watch?v=CDilI6jcpP4',
                'coverImage'        => !empty( $settings['video_image']['url'] ) ? $settings['video_image']['url'] : '',
                'autoPlay'          => ( $settings['autoplay'] == 'yes' ) ? true : false,
                'mute'              => ( $settings['soundmute'] == 'yes' ) ? true : false,
                'loop'              => ( $settings['repeatvideo'] == 'yes' ) ? true : false,
                'showControls'      => ( $settings['controlerbutton'] == 'yes' ) ? true : false,
                'showYTLogo'        => ( $settings['videosourselogo'] == 'yes' ) ? true : false,
                'startAt'           => $settings['videostarttime'],
                'containment'       => 'self',
                'opacity'           => 1,
                'optimizeDisplay'   => true,
                'realfullscreen'    => true,
            ];
        }
        $videocontainer = [
            'videocontainer' => isset( $settings['videocontainer'] ) ? $settings['videocontainer'] : '',
        ];
        
        $animation_markup = '';
        if( 'no' == $controleranimation ) {
            $animation_markup = "";
        } else { 
            $animation_markup = '<div class="htmega-video-mark">
                <div class="htmega-wave-pulse wave-pulse-1"></div>
                <div class="htmega-wave-pulse wave-pulse-2"></div>
                </div>';
            }
        ?>
            <div class="htmega-player-container" data-videotype=<?php echo wp_json_encode( $videocontainer ); ?>>
                <?php if($settings['videocontainer'] == 'self'): ?>
                    <div class="htmega-video-player" data-property=<?php echo wp_json_encode( $player_options_settings );?> ></div>
                <?php else:
                    if( 'icon' == $buttonicon_type && $settings['buttonicon']['value'] != '' ){
                        echo sprintf('<a class="magnify-video-active" href="%1$s">%2$s %3$s %4$s</a>',esc_url( $settings['video_url'] ),HTMega_Icon_manager::render_icon( $settings['buttonicon'], [ 'aria-hidden' => 'true' ] ), htmega_kses_title($settings['buttontext'] ),$animation_markup );
                    } elseif ('image' == $buttonicon_type && $buttonicon_image != '' ){
                        
                        echo sprintf( '<a class="magnify-video-active" href="%1$s"><img src="%2$s" alt="htmega-addons"> %3$s %4$s </a>', esc_url($settings['video_url'] ),$buttonicon_image, htmega_kses_title( $settings['buttontext'] ),$animation_markup );

                    } else {
                        echo sprintf('<a class="magnify-video-active" href="%1$s">%2$s %3$s</a>',esc_url( $settings['video_url'] ), htmega_kses_title( $settings['buttontext'] ), $animation_markup );
                    }
                ?>
                <?php endif;?>
            </div>
        <?php
    }
}