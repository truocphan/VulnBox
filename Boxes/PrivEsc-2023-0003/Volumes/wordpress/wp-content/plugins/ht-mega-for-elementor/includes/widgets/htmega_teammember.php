<?php
namespace Elementor;

// Elementor Classes
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_TeamMember extends Widget_Base {

    public function get_name() {
        return 'htmega-team-member-addons';
    }
    
    public function get_title() {
        return __( 'Team Member', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-person';
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

        // Team Content tab Start
        $this->start_controls_section(
            'htmega_teammember_content',
            [
                'label' => __( 'Team Member', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'htmega_team_style',
                [
                    'label' => __( 'Style', 'htmega-addons' ),
                    'type' => 'htmega-preset-select',
                    'default' => '1',
                    'options' => [
                        '1'   => __( 'Style One', 'htmega-addons' ),
                        '2'   => __( 'Style Two', 'htmega-addons' ),
                        '3'   => __( 'Style Three', 'htmega-addons' ),
                        '4'   => __( 'Style Four', 'htmega-addons' ),
                        '5'   => __( 'Style Five', 'htmega-addons' ),
                        '6'   => __( 'Style Six', 'htmega-addons' ),
                        '7'   => __( 'Style Seven', 'htmega-addons' ),
                        '8'   => __( 'Style Eight', 'htmega-addons' ),
                    ],
                ]
            );
            $this->add_control(
                'htmega_team_content_style',
                [
                    'label' => __( 'Content Style', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'one',
                    'options' => [
                        'one'      => __( 'Style One', 'htmega-addons' ),
                        'two'      => __( 'Style Two', 'htmega-addons' ),
                    ],
                    'condition' =>[
                        'htmega_team_style' => array('2'),
                    ],
                ]
            );
            $this->add_control(
                'htmega_team_content_style2',
                [
                    'label' => __( 'Content Style', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'one',
                    'options' => [
                        'one'      => __( 'Style One', 'htmega-addons' ),
                        'two'      => __( 'Style Two', 'htmega-addons' ),
                        'three'      => __( 'Style Three', 'htmega-addons' ),
                    ],
                    'condition' =>[
                        'htmega_team_style' => array('8'),
                    ],
                ]
            );

            $this->add_control(
                'htmega_team_image_hover_style',
                [
                    'label' => __( 'Image Hover Animate', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'top',
                    'options' => [
                        'none'      => __( 'None', 'htmega-addons' ),
                        'left'      => __( 'Left', 'htmega-addons' ),
                        'right'     => __( 'Right', 'htmega-addons' ),
                        'top'       => __( 'Top', 'htmega-addons' ),
                        'bottom'    => __( 'Bottom', 'htmega-addons' ),
                    ],
                    'condition' =>[
                        'htmega_team_style' =>'4',
                    ],
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'htmega_team_image_hover_on_mobile',
                [
                    'label' => esc_html__( 'Animate Top on Mobile Layout', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' =>[
                        'htmega_team_style' =>'4',
                        'htmega_team_image_hover_style' =>array('left','right'),
                    ],
                    'separator' => 'after',
                ]
            );
            $this->add_control(
                'htmega_member_image',
                [
                    'label' => __( 'Member image', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'htmega_member_imagesize',
                    'default' => 'large',
                    'separator' => 'none',
                ]
            );

            $this->add_control(
                'htmega_member_name',
                [
                    'label' => __( 'Name', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => 'Sams Roy',
                    'placeholder' => __( 'Sams Roy', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'htmega_member_designation',
                [
                    'label' => __( 'Designation', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Managing director', 'htmega-addons' ),
                    'condition' =>[
                        'htmega_team_style' => array('1','3','5','6','7','8','2','4'),
                    ],
                ]
            );
            
            $this->add_control(
                'htmega_member_bioinfo',
                [
                    'label' => __( 'Bio Info', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXTAREA,
                    'placeholder' => __( 'I am web developer.', 'htmega-addons' ),

                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            [
                            'terms' => [
                                    ['name' => 'htmega_team_style', 'operator' => 'in', 'value' => ['1','5','6']]
                                ]
                            ],
                            [
                            'terms' => [
                                    ['name' => 'htmega_team_style', 'operator' => '===', 'value' => '4'],
                                    ['name' => 'htmega_team_image_hover_style', 'operator' => '!==', 'value' => 'none'],
                                ]
                            ],
                        ]
                    ],

                ]
            );
            
        $this->end_controls_section(); // End Team Content tab

        // Social Media tab
        $this->start_controls_section(
            'htmega_team_member_social_link',
            [
                'label' => __( 'Social Media', 'htmega-addons' ),
            ]
        );

            $repeater = new Repeater();

            $repeater->add_control(
                'htmega_social_title',
                [
                    'label'   => __( 'Title', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => 'Facebook',
                ]
            );

            $repeater->add_control(
                'htmega_social_link',
                [
                    'label'   => __( 'Link', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __( 'https://www.facebook.com/hastech.company/', 'htmega-addons' ),
                ]
            );

            $repeater->add_control(
                'htmega_social_icon',
                [
                    'label'   => __( 'Icon', 'htmega-addons' ),
                    'type'    => Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fab fa-facebook-f',
                        'library'=>'solid',
                    ],
                ]
            );

            $repeater->add_control(
                'htmega_icon_color',
                [
                    'label'     => __( 'Icon Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-social-network {{CURRENT_ITEM}} a' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .htmega-team .htmega-social-network {{CURRENT_ITEM}} a svg path' => 'fill: {{VALUE}}',
                    ],
                ]
            );

            $repeater->add_control(
                'htmeha_icon_background',
                [
                    'label'     => __( 'Icon Background', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-social-network {{CURRENT_ITEM}} a' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $repeater->add_control(
                'htmega_icon_hover_color',
                [
                    'label'     => __( 'Icon Hover Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-social-network {{CURRENT_ITEM}} a:hover' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .htmega-team .htmega-social-network {{CURRENT_ITEM}} a:hover svg path' => 'fill: {{VALUE}}',
                    ],
                ]
            );

            $repeater->add_control(
                'htmeha_icon_hover_background',
                [
                    'label'     => __( 'Icon Hover Background', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-social-network {{CURRENT_ITEM}} a:hover' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $repeater->add_control(
                'htmeha_icon_hover_border_color',
                [
                    'label'     => __( 'Icon Hover border color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-social-network {{CURRENT_ITEM}} a:hover' => 'border-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'htmega_team_member_social_link_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => $repeater->get_controls(),
                    'default' => [

                        [
                            'htmega_social_title'      => 'Facebook',
                            'htmega_social_icon'       => 'fab fa-facebook-f',
                            'htmega_social_link'       => __( 'https://www.facebook.com/hastech.company/', 'htmega-addons' ),
                        ],
                    ],
                    'title_field' => '{{{ htmega_social_title }}}',
                    'prevent_empty'=>false,
                ]
            );

        $this->end_controls_section(); // End Social Member tab

        // Member Item Style tab section
        $this->start_controls_section(
            'htmega_team_member_style',
            [
                'label' => __( 'Team Box Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'team_member_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'team_member_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                    'condition' =>[
                        'htmega_team_style!' => '8',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'team_item_border_box',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-team',
                    'separator' =>'before',
                    'condition' =>[
                        'htmega_team_style!' => '8',
                    ],
                ]
            );

            $this->add_responsive_control(
                'team_item_border_radius_box',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team,{{WRAPPER}} .htmega-team-style-2 .htmega-thumb img, {{WRAPPER}} .htmega-team-style-4 .htmega-thumb,{{WRAPPER}} .htmega-team-style-4 .htmega-thumb img,{{WRAPPER}} .htmega-team-style-4 .htmega-team-hover-action::before,{{WRAPPER}} .htmega-team-style-4 .htmega-team-hover-action' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'separator' =>'before',
                    'condition' =>[
                        'htmega_team_style!' => '8',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'team_member_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-team,{{WRAPPER}} .htmega-team-style-6 .htmega-team-info',
                    'separator' =>'before',
                    'condition' =>[
                        'htmega_team_style!' => array( '8','2','5','4' ),
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'team_item_boxshadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-team-style-7, {{WRAPPER}} .htmega-team',
                    'separator' =>'before',
                ]
            );            
            $this->add_control(
                'team_member_hover_content_bg',
                [
                    'label' => __( 'Hover Background Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => 'rgba(24, 1, 44, 0.6)',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team:hover .htmega-team-hover-action' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-team-style-6:hover .htmega-team-info' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-team-style-4 .htmega-team-hover-action::before,{{WRAPPER}} .htmega-team-style-1::before' => 'background-color: {{VALUE}};',
                    ],
                    'condition' =>[
                        'htmega_team_style' => array( '1','4','5','6' ),
                    ],
                ]
            );
            $this->add_responsive_control(
                'team_member_hover_st4_space',
                [
                    'label' => __( 'Hover Round Space', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team-style-4 .htmega-team-image-hover-none .htmega-team-hover-action::before' => 'top: {{TOP}}{{UNIT}};right: {{RIGHT}}{{UNIT}};bottom: {{BOTTOM}}{{UNIT}};left: {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                    'condition' =>[
                        'htmega_team_style' => '4',
                        'htmega_team_image_hover_style' => 'none',
                        
                    ],
                ]
            );

            $this->add_control(
                'team_member_hover_content_bg_2',
                [
                    'label' => __( 'Hover Content background color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default'=>'#18012c',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team-style-2 .htmega-team-hover-action .htmega-hover-action' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-team-click-action' => 'background-color: {{VALUE}};',
                    ],

                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            [
                            'terms' => [
                                    ['name' => 'htmega_team_style', 'operator' => '===', 'value' => '3'],
                                ]
                            ],
                            [
                            'terms' => [
                                    ['name' => 'htmega_team_style', 'operator' => '===', 'value' => '2'],
                                    ['name' => 'htmega_team_content_style', 'operator' => '===', 'value' => 'one'],
                                ]
                            ],
                        ]
                    ],
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'team_member_plus_icon_color',
                [
                    'label' => __( 'Plus Icon Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team-style-3 .plus_click::before' => 'color: {{VALUE}};',
                    ],
                    'condition' =>[
                        'htmega_team_style' => array('3'),
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'team_member_plus_icon_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-team-style-3 .plus_click::before',
                    'condition' =>[
                        'htmega_team_style' => array('3'),
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'team_member_plus_icon_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-team-style-3 .plus_click::before',
                    'condition' =>[
                        'htmega_team_style' => array('3'),
                    ],
                ]
            );
            // Team content Box Style
            $this->add_control(
                'content_box_bg_heading',
                [
                    'label' => __( 'Content Box Background', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' =>'before',
                    'condition' =>[
                        'htmega_team_style!' => array( '4','6','3','5' ),
                    ],
                ]
            ); 
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'team_content_background_box',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-team .htmega-team-content,{{WRAPPER}} .htmega-team-style-2 .htmega-team-hover-action .htmega-hover-action, {{WRAPPER}} .htmega-team-hover-action.htmega-action-hover-st2 .htmega-hover-action',
                    'condition' =>[
                        'htmega_team_style!' => array( '4','6','3','5' ),
                    ],
                ]
            );
            $this->add_control(
                'content_box_bg_hover_heading',
                [
                    'label' => __( 'Content Box Hover Background', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' =>'before',
                    'condition' =>[
                        'htmega_team_style!' => array( '4','6','7','3','2','5' ),
                    ],
                ]
            ); 
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'team_content_hover_background_box',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-team-style-8 .htmega-team-hover-action',
                    'condition' =>[
                        'htmega_team_style!' => array( '4','6','7','3','2','5' ),
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'team_content_border_box',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-team .htmega-team-content',
                    'condition' =>[
                        'htmega_team_style!' => array( '4','6','3','5' ),
                    ],
                ]
            );

            $this->add_responsive_control(
                'team_content_border_radius_box',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-content,{{WRAPPER}} .htmega-team-style-2 .htmega-team-hover-action .htmega-hover-action' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'condition' =>[
                        'htmega_team_style!' => array('8','4','6','3','5'),
                    ],
                ]
            );

            $this->add_responsive_control(
                'team_content_margin_box',
                [
                    'label' => __( 'Content Box Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-team-style-2 .htmega-team-hover-action' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' =>[
                        'htmega_team_style!' => array( '4','3','5', ),
                    ],
                ]
            );
            $this->add_responsive_control(
                'team_content_padding_box',
                [
                    'label' => __( 'Content Box Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-content,{{WRAPPER}} .htmega-team-style-2 .htmega-team-hover-action .htmega-hover-action,{{WRAPPER}} .htmega-team-style-5 .htmega-team-hover-action .htmega-hover-action,{{WRAPPER}} .htmega-team-style-4 .htmega-team-hover-action .htmega-hover-action' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' =>[
                        'htmega_team_style!' => array('3' ),
                    ],
                ]
            );
            $this->add_responsive_control(
                'team_content_alignment_box',
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
                        '{{WRAPPER}} .htmega-team .htmega-team-content,{{WRAPPER}} .htmega-team-style-5 .htmega-team-hover-action .htmega-hover-action,{{WRAPPER}} .htmega-team-style-4 .htmega-team-hover-action .htmega-hover-action,{{WRAPPER}} .htmega-team ul.htmega-social-network' => 'text-align: {{VALUE}};',
                    ],
                    'condition' =>[
                        'htmega_team_style!' => array('6','3' ),
                    ],
                ]
            );
            $this->add_control(
                'team_content_corner_shape_color',
                [
                    'label' => __( 'Corner Shape Color', 'htmega-addons' ),
                    'description' => __( 'To hide the shape, please set the color to transparent.', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#8e74ff',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team-style-8::before,{{WRAPPER}} .htmega-team-style-8::after' => 'border-color: {{VALUE}};',
                    ],
                    'condition' =>[
                        'htmega_team_style' => '8',
                    ],
                ]
            );           
            $this->add_control(
                'team_content_corner_shape_hover',
                [
                    'label' => __( 'Hover Shape Color', 'htmega-addons' ),
                    'description' => __( 'To hide the shape, please set the color to transparent.', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#fff',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team-style-8 .htmega-team-hover-action:after' => 'background-color: {{VALUE}};',
                    ],
                    'condition' =>[
                        'htmega_team_style' => '8',
                    ],
                ]
            );  
            $this->add_control(
                'show_img_animation',
                [
                    'label' => esc_html__( 'ON/OFF Image Hover rotation', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'no',
                    'default' => 'yes',
                    'condition' =>[
                        'htmega_team_style' => array( '6','7','8' ),
                    ],
                ]
            );
            
        $this->end_controls_section();

        // Team Member Name style tab start
        $this->start_controls_section(
            'htmega_team_member_name_style',
            [
                'label'     => __( 'Name', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'htmega_member_name!' => '',
                ],
            ]
        );

            $this->add_control(
                'team_name_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-name' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'team_name_typography',
                    'selector' => '{{WRAPPER}} .htmega-team .htmega-team-name',
                ]
            );

            $this->add_responsive_control(
                'team_name_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'team_name_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'team_name_align',
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
                        'justify' => [
                            'title' => __( 'Justified', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-name' => 'text-align: {{VALUE}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // Team Member Name style tab end

        // Team Member Designation style tab start
        $this->start_controls_section(
            'htmega_team_member_designation_style',
            [
                'label'     => __( 'Designation', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'htmega_member_designation!' => '',
                    'htmega_team_style' =>array('1','3','5','6','7','8','2','4'),
                ],
            ]
        );

            $this->add_control(
                'team_designation_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-designation' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'team_designation_typography',
                    'selector' => '{{WRAPPER}} .htmega-team .htmega-team-designation',
                ]
            );

            $this->add_responsive_control(
                'team_designation_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'team_designation_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-designation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'team_designation_align',
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
                        'justify' => [
                            'title' => __( 'Justified', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-designation' => 'text-align: {{VALUE}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // Team Member Designation style tab end

        // Team Member Bio Info style tab start
        $this->start_controls_section(
            'htmega_team_member_bioinfo_style',
            [
                'label'     => __( 'Bio info', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'htmega_member_bioinfo!' => '',
                    'htmega_team_style' => array('1','5','6','4'),
                ],
            ]
        );

            $this->add_control(
                'team_bioinfo_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-bio-info' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'team_bioinfo_typography',
                    'selector' => '{{WRAPPER}} .htmega-team .htmega-team-bio-info',
                ]
            );

            $this->add_responsive_control(
                'team_bioinfo_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-bio-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'team_bioinfo_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-bio-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'team_bioinfo_align',
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
                        'justify' => [
                            'title' => __( 'Justified', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-bio-info' => 'text-align: {{VALUE}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // Team Member Designation style tab end

        // Team Member Social Media style tab start
        $this->start_controls_section(
            'htmega_team_member_socialmedia_style',
            [
                'label'     => __( 'Social Media', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'team_socialmedia_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-social-network li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'team_socialmedia_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-social-network li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'team_socialmedia_align',
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
                        'justify' => [
                            'title' => __( 'Justified', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team ul.htmega-social-network' => 'text-align: {{VALUE}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'team_socialmedia_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-social-network li a',
                ]
            );

            $this->add_responsive_control(
                'team_socialmedia_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-social-network li a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'team_socialmedia_boxshadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-social-network li a',
                ]
            );

            $this->add_responsive_control(
                'team_socialmedia_font_size',
                [
                    'label' => __( 'Font Size', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-social-network li a' => 'font-size: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-social-network li a svg' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
                        
            $this->add_responsive_control(
                'team_socialmedia_height_widht',
                [
                    'label' => __( 'Height and Width', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team-style-7 .htmega-team-hover-action .htmega-hover-action .htmega-social-network li a,{{WRAPPER}} .htmega-social-network li a' => 'height: {{SIZE}}{{UNIT}}; width:{{SIZE}}{{UNIT}}; line-height:{{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            
        $this->end_controls_section(); // Team Member Designation style tab end

    }

    protected function render( $instance = [] ) {
        $settings   = $this->get_settings_for_display();
        $sectionid = "sid". $this-> get_id();


        $this->add_render_attribute( 'team_area_attr', 'class', 'htmega-team' );
        if( '8' == $settings['htmega_team_style'] ){
            if( 'two'== $settings['htmega_team_content_style2'] ){
                $this->add_render_attribute( 'team_area_attr', 'class', 'htmega-st8-new htmega-team-style-7 htmega-team-style-'.$settings['htmega_team_style'].' '.$sectionid );
            } elseif( 'three'== $settings['htmega_team_content_style2'] ){
                $this->add_render_attribute( 'team_area_attr', 'class', 'htmega-st8-new3 htmega-st8-new htmega-team-style-7 htmega-team-style-'.$settings['htmega_team_style'].' '.$sectionid );
            }
            else {
                $this->add_render_attribute( 'team_area_attr', 'class', ' htmega-team-style-7 htmega-team-style-'.$settings['htmega_team_style'].' '.$sectionid );
            }

            $this->add_render_attribute( 'team_area_attr', 'class', ' htmega-team-style-7 htmega-team-style-'.$settings['htmega_team_style'].' '.$sectionid );

        } else {

            if( 'two'== $settings['htmega_team_content_style'] ){
                $this->add_render_attribute( 'team_area_attr', 'class', 'htmega-st2-new htmega-team-style-'.$settings['htmega_team_style'].' '.$sectionid ); 
            } else {
                $this->add_render_attribute( 'team_area_attr', 'class', 'htmega-team-style-'.$settings['htmega_team_style'].' '.$sectionid );
            }
        }
       
        ?>
            <div <?php echo $this->get_render_attribute_string( 'team_area_attr' ); ?> >

                <?php if( $settings['htmega_team_style'] == 2 ): ?>
                    <div class="htmega-thumb">
                        <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'htmega_member_imagesize', 'htmega_member_image' );
                        // for new design
                        if( 'two'== $settings['htmega_team_content_style'] ){ ?>
                            <div class="htmega-team-hover-action htmega-action-hover-st2">
                                <div class="htmega-hover-action">
                                    <div class="htmega-hover-content-box-st2">
                                        <?php
                                        if( !empty($settings['htmega_member_name']) ){
                                            echo '<h4 class="htmega-team-name">'.htmega_kses_title( $settings['htmega_member_name'] ).'</h4>';
                                        }
                                        if( !empty($settings['htmega_member_designation']) ){
                                            echo '<span class="htmega-team-designation">'.esc_html( $settings['htmega_member_designation'] ).'</span>';
                                        }
                                        ?>
                                    </div>
                                    <ul class="htmega-social-network">
                                        <?php foreach ( $settings['htmega_team_member_social_link_list'] as $socialprofile ) :?>
                                            <li class="elementor-repeater-item-<?php echo esc_attr( $socialprofile['_id'] ); ?>" ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><?php echo HTMega_Icon_manager::render_icon( $socialprofile['htmega_social_icon'], [ 'aria-hidden' => 'true' ] ); ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        <?php
                      } else {  ?>                        
                            <div class="htmega-team-hover-action">
                                <div class="htmega-hover-action">
                                    <?php
                                        if( !empty($settings['htmega_member_name']) ){
                                            echo '<h4 class="htmega-team-name">'.htmega_kses_title( $settings['htmega_member_name'] ).'</h4>';
                                        }
                                        if( !empty($settings['htmega_member_designation']) ){
                                            echo '<span class="htmega-team-designation">'.esc_html( $settings['htmega_member_designation'] ).'</span>';
                                        }
                                    ?>
                                    <ul class="htmega-social-network">
                                        <?php foreach ( $settings['htmega_team_member_social_link_list'] as $socialprofile ) :?>
                                            <li class="elementor-repeater-item-<?php echo esc_attr( $socialprofile['_id'] ); ?>" ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><?php echo HTMega_Icon_manager::render_icon( $socialprofile['htmega_social_icon'], [ 'aria-hidden' => 'true' ] ); ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <?php
                        } ?>
                    </div>

                <?php elseif( $settings['htmega_team_style'] == 3 ):?>
                    <div class="htmega-thumb">
                        <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'htmega_member_imagesize', 'htmega_member_image' );?>
                        <div class="htmega-team-hover-action">

                            <div class="htmega-team-click-action">
                                <div class="plus_click"></div>
                                <?php
                                    if( !empty($settings['htmega_member_name']) ){
                                        echo '<h4 class="htmega-team-name">'.htmega_kses_title( $settings['htmega_member_name'] ).'</h4>';
                                    }
                                    if( !empty($settings['htmega_member_designation']) ){
                                        echo '<span class="htmega-team-designation">'.esc_html( $settings['htmega_member_designation'] ).'</span>';
                                    }
                                ?>
                                <ul class="htmega-social-network">
                                    <?php foreach ( $settings['htmega_team_member_social_link_list'] as $socialprofile ) :?>
                                        <li class="elementor-repeater-item-<?php echo esc_attr( $socialprofile['_id'] ); ?>" ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><?php echo HTMega_Icon_manager::render_icon( $socialprofile['htmega_social_icon'], [ 'aria-hidden' => 'true' ] ); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                        </div>
                    </div>

                <?php 
                    elseif( $settings['htmega_team_style'] == 4 ):
                    $this->add_render_attribute( 'team_thumb_attr', 'class', 'htmega-thumb' );
                    $this->add_render_attribute( 'team_thumb_attr', 'class', 'htmega-team-image-hover-'.$settings['htmega_team_image_hover_style'] );
                ?>
                    <div <?php echo $this->get_render_attribute_string( 'team_thumb_attr' ); ?>>
                        <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'htmega_member_imagesize', 'htmega_member_image' );?>
                        <div class="htmega-team-hover-action">
                            <div class="htmega-hover-action">
                                <?php
                                    if( !empty($settings['htmega_member_name']) ){
                                        echo '<h4 class="htmega-team-name">'.htmega_kses_title( $settings['htmega_member_name'] ).'</h4>';
                                    } 
                                    if( !empty($settings['htmega_member_designation']) ){
                                        echo '<span class="htmega-team-designation">'.esc_html( $settings['htmega_member_designation'] ).'</span>';
                                    }
                                    if( !empty($settings['htmega_member_bioinfo']) ){ echo '<p class="htmega-team-bio-info">'.htmega_kses_desc( $settings['htmega_member_bioinfo'] ).'</p>'; }
                                    if( $settings['htmega_team_member_social_link_list'] ): 
                                ?>
                                    <ul class="htmega-social-network">
                                        <?php foreach ( $settings['htmega_team_member_social_link_list'] as $socialprofile ) :?>
                                            <li class="elementor-repeater-item-<?php echo esc_attr( $socialprofile['_id'] ); ?>" ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><?php echo HTMega_Icon_manager::render_icon( $socialprofile['htmega_social_icon'], [ 'aria-hidden' => 'true' ] ); ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>

                <?php elseif( $settings['htmega_team_style'] == 5 ):?>
                    <div class="htmega-thumb">
                        <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'htmega_member_imagesize', 'htmega_member_image' );?>
                        <div class="htmega-team-hover-action">
                            <div class="htmega-hover-action">
                                <?php
                                    if( !empty($settings['htmega_member_name']) ){
                                        echo '<h4 class="htmega-team-name">'.htmega_kses_title( $settings['htmega_member_name'] ).'</h4>';
                                    }
                                    if( !empty($settings['htmega_member_designation']) ){
                                        echo '<span class="htmega-team-designation">'.esc_html( $settings['htmega_member_designation'] ).'</span>';
                                    }
                                    if( !empty($settings['htmega_member_bioinfo']) ){ echo '<p class="htmega-team-bio-info">'.htmega_kses_desc( $settings['htmega_member_bioinfo'] ).'</p>'; }
                                ?>
                                <?php if( $settings['htmega_team_member_social_link_list'] ): ?>
                                    <ul class="htmega-social-network">
                                        <?php foreach ( $settings['htmega_team_member_social_link_list'] as $socialprofile ) :?>
                                            <li class="elementor-repeater-item-<?php echo esc_attr( $socialprofile['_id'] ); ?>" ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><?php echo HTMega_Icon_manager::render_icon( $socialprofile['htmega_social_icon'], [ 'aria-hidden' => 'true' ] ); ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>

                <?php elseif( $settings['htmega_team_style'] == 6 ):?>
                    <div class="htmega-thumb">
                        <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'htmega_member_imagesize', 'htmega_member_image' );?>
                    </div>
                    <div class="htmega-team-info">
                        <div class="htmega-team-content">
                            <?php
                                if( !empty($settings['htmega_member_name']) ){
                                    echo '<h4 class="htmega-team-name">'.htmega_kses_title( $settings['htmega_member_name'] ).'</h4>';
                                }
                                if( !empty($settings['htmega_member_designation']) ){
                                    echo '<span class="htmega-team-designation">'.esc_html( $settings['htmega_member_designation'] ).'</span>';
                                }
                                if( !empty($settings['htmega_member_bioinfo']) ){ echo '<p class="htmega-team-bio-info">'.htmega_kses_desc( $settings['htmega_member_bioinfo'] ).'</p>'; }
                            ?>
                        </div>
                        <?php if( $settings['htmega_team_member_social_link_list'] ): ?>
                            <ul class="htmega-social-network">
                                <?php foreach ( $settings['htmega_team_member_social_link_list'] as $socialprofile ) :?>
                                    <li class="elementor-repeater-item-<?php echo esc_attr( $socialprofile['_id'] ); ?>" ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><?php echo HTMega_Icon_manager::render_icon( $socialprofile['htmega_social_icon'], [ 'aria-hidden' => 'true' ] ); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif;?>
                    </div>

                <?php elseif( $settings['htmega_team_style'] == 7 ):?>

                    <div class="htmega-thumb">
                        <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'htmega_member_imagesize', 'htmega_member_image' );?>
                        <div class="htmega-team-hover-action">
                            <div class="htmega-hover-action">
                                <?php if( $settings['htmega_team_member_social_link_list'] ): ?>
                                    <ul class="htmega-social-network">
                                        <?php foreach ( $settings['htmega_team_member_social_link_list'] as $socialprofile ) :?>
                                            <li class="elementor-repeater-item-<?php echo esc_attr( $socialprofile['_id'] ); ?>" ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><?php echo HTMega_Icon_manager::render_icon( $socialprofile['htmega_social_icon'], [ 'aria-hidden' => 'true' ] ); ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                    <div class="htmega-team-content">
                        <?php
                            if( !empty($settings['htmega_member_name']) ){
                                echo '<h4 class="htmega-team-name">'.htmega_kses_title( $settings['htmega_member_name'] ).'</h4>';
                            }
                            if( !empty($settings['htmega_member_designation']) ){
                                echo '<span class="htmega-team-designation">'.esc_html( $settings['htmega_member_designation'] ).'</span>';
                            }
                        ?>
                    </div>
                <?php elseif( $settings['htmega_team_style'] == 7 ):?>

                    <div class="htmega-thumb">
                        <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'htmega_member_imagesize', 'htmega_member_image' );?>
                        <div class="htmega-team-hover-action">
                            <div class="htmega-hover-action">
                                <?php if( $settings['htmega_team_member_social_link_list'] ): ?>
                                    <ul class="htmega-social-network">
                                        <?php foreach ( $settings['htmega_team_member_social_link_list'] as $socialprofile ) :?>
                                            <li class="elementor-repeater-item-<?php echo esc_attr( $socialprofile['_id'] ); ?>" ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><?php echo HTMega_Icon_manager::render_icon( $socialprofile['htmega_social_icon'], [ 'aria-hidden' => 'true' ] ); ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                    <div class="htmega-team-content">
                        <?php
                            if( !empty($settings['htmega_member_name']) ){
                                echo '<h4 class="htmega-team-name">'.htmega_kses_title( $settings['htmega_member_name'] ).'</h4>';
                            }
                            if( !empty($settings['htmega_member_designation']) ){
                                echo '<span class="htmega-team-designation">'.esc_html( $settings['htmega_member_designation'] ).'</span>';
                            }
                        ?>
                    </div>
                <?php elseif( $settings['htmega_team_style'] == 8 ):?>

                    <div class="htmega-thumb">
                        <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'htmega_member_imagesize', 'htmega_member_image' );?>
                    </div>
                    <div class="htmega-team-content">
                        <?php
                            if( !empty($settings['htmega_member_name']) ){
                                echo '<h4 class="htmega-team-name">'.htmega_kses_title( $settings['htmega_member_name'] ).'</h4>';
                            }
                            if( !empty($settings['htmega_member_designation']) ){
                                echo '<span class="htmega-team-designation">'.esc_html( $settings['htmega_member_designation'] ).'</span>';
                            }
                        ?>
                        <?php if( $settings['htmega_team_member_social_link_list'] ): ?>
                        <div class="htmega-team-hover-action">
                            <div class="htmega-hover-action">
                                
                                    <ul class="htmega-social-network">
                                        <?php foreach ( $settings['htmega_team_member_social_link_list'] as $socialprofile ) :?>
                                            <li class="elementor-repeater-item-<?php echo esc_attr( $socialprofile['_id'] ); ?>" ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><?php echo HTMega_Icon_manager::render_icon( $socialprofile['htmega_social_icon'], [ 'aria-hidden' => 'true' ] ); ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                            </div>
                        </div>
                        <?php endif;?>
                    </div>                    
                <?php else:?>
                    <div class="htmega-thumb">
                        <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'htmega_member_imagesize', 'htmega_member_image' );?>
                        <div class="htmega-team-hover-action">
                            <div class="htmega-team-hover">
                                <?php if( $settings['htmega_team_member_social_link_list'] ): ?>
                                    <ul class="htmega-social-network">
                                        <?php foreach ( $settings['htmega_team_member_social_link_list'] as $socialprofile ) :?>
                                            <li class="elementor-repeater-item-<?php echo esc_attr( $socialprofile['_id'] ); ?>" ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><?php echo HTMega_Icon_manager::render_icon( $socialprofile['htmega_social_icon'], [ 'aria-hidden' => 'true' ] ); ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif;?>
                                <?php if( !empty($settings['htmega_member_bioinfo']) ){ echo '<p class="htmega-team-bio-info">'.htmega_kses_desc( $settings['htmega_member_bioinfo'] ).'</p>'; }?>
                            </div>
                        </div>
                    </div>
                    <div class="htmega-team-content">
                        <?php
                            if( !empty($settings['htmega_member_name']) ){
                                echo '<h4 class="htmega-team-name">'.htmega_kses_title( $settings['htmega_member_name'] ).'</h4>';
                            }
                            if( !empty($settings['htmega_member_designation']) ){
                                echo '<p class="htmega-team-designation">'.esc_html( $settings['htmega_member_designation'] ).'</p>';
                            }
                        ?>
                    </div>
                <?php endif;?>
            </div>

                <?php 
                 $htmega_print_css = '';

                    if( 'no'== $settings['show_img_animation'] ){
                        $htmega_print_css .=  " .{$sectionid}.htmega-team-style-7:hover .htmega-thumb img,.{$sectionid}.htmega-team-style-6:hover .htmega-thumb img {
                        transform: scale(1) rotate(0);
                    }";
                    ?>
                <?php } ?>

                <?php if( 'yes'== $settings['htmega_team_image_hover_on_mobile'] ){
                    $htmega_print_css .= " @media (max-width: 767px) {
                        .{$sectionid}.htmega-team-style-4 .htmega-thumb.htmega-team-image-hover-left img,.{$sectionid}.htmega-team-style-4 .htmega-thumb.htmega-team-image-hover-right img {
                            -webkit-transform-origin: 50% 0%;
                            -moz-transform-origin: 50% 0%;
                            -ms-transform-origin: 50% 0%;
                            -o-transform-origin: 50% 0%;
                            transform-origin: 50% 0%;
                        }
                        .{$sectionid}.htmega-team-style-4:hover .htmega-thumb.htmega-team-image-hover-left img,.{$sectionid}.htmega-team-style-4:hover .htmega-thumb.htmega-team-image-hover-right img {
                            -webkit-transform: rotate3d(1, 0, 0, 180deg);
                            -moz-transform: rotate3d(1, 0, 0, 180deg);
                            -ms-transform: rotate3d(1, 0, 0, 180deg);
                            -o-transform: rotate3d(1, 0, 0, 180deg);
                            transform: rotate3d(1, 0, 0, 180deg);
                        }
                        }";
                    ?>
                <?php }
                
                if( '' != $htmega_print_css ){ ?>
                    <style>
                        <?php echo esc_html( $htmega_print_css ); ?>
                    </style>

                <?php } ?>



        <?php
    }
}