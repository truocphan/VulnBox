<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Buddy_Press extends Widget_Base {

    public function get_name() {
        return 'htmega-buddypress-addons';
    }
    
    public function get_title() {
        return __( 'BuddyPress', 'htmega-addons' );
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
    public function get_keywords() {
        return [ 'buddypress', 'buddypress widget', 'Social','htmega','htmega' ];
    }

    public function get_help_url() {
		return 'https://wphtmega.com/docs/3rd-party-plugin-widgets/buddypress-widget/';
	}
    protected function register_controls() {

        $this->start_controls_section(
            'buddypress_content',
            [
                'label' => __( 'BuddyPress', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'buddypress_type',
                [
                    'label'   => __( 'Type', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'member',
                    'options' => [
                        'member'    => __('Member', 'htmega-addons'),
                        'group'     => __('Group', 'htmega-addons'),
                    ],
                    'label_block'=>true,
                ]
            );

            $this->add_control(
                'content_type',
                [
                    'label'   => __( 'Content Type', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'newest',
                    'options' => [
                        'newest'  => __('Newest', 'htmega-addons'),
                        'popular' => __('Popular', 'htmega-addons'),
                        'active'  => __('Active', 'htmega-addons'),
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'max_items',
                [
                    'label'   => esc_html__( 'Max Item', 'htmega-addons' ),
                    'type'    => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 5,
                    ],
                    'range' => [
                        'px' => [
                            'min'  => 1,
                            'max'  => 20,
                            'step' => 1,
                        ],
                    ],
                ]
            );

            $this->add_control(
                'avatar_size',
                [
                    'label'     => __( 'Image Size', 'htmega-addons' ),
                    'type'      => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 80,
                    ],
                    'range' => [
                        'px' => [
                            'min'  => 5,
                            'max'  => 200,
                            'step' => 1,
                        ],
                    ],
                ]
            );

            $this->add_control(
                'show_content_meta',
                [
                    'label' => __( 'Show Meta Info', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'show_active_time',
                [
                    'label' => __( 'Show Active Time', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition'=>[
                        'show_content_meta'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'show_register_time',
                [
                    'label' => __( 'Show Register Time', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition'=>[
                        'show_content_meta'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'show_friend_count',
                [
                    'label' => __( 'Show Friend Count', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition'=>[
                        'show_content_meta'=>'yes',
                        'buddypress_type'=>'group',
                    ]
                ]
            );
            
        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'buddypress_style_section',
            [
                'label' => __( 'Area', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'area_button_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega_buddypress_single',
                ]
            );

            $this->add_responsive_control(
                'area_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega_buddypress_single' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'area_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega_buddypress_single',
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'area_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega_buddypress_single',
                ]
            );

            $this->add_responsive_control(
                'area_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega_buddypress_single' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'area_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega_buddypress_single' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega_buddy_press_area' => 'margin-right:-{{RIGHT}}{{UNIT}};margin-left: -{{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'area_align',
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
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .htmega_buddypress_single' => 'text-align: {{VALUE}};',
                        '{{WRAPPER}} .htmega_buddy_press_area' => 'text-align: {{VALUE}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'show_column',
                [
                    'label' => esc_html__( 'Column Width', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'description' => esc_html__( 'Add Column Width Ex. 25%', 'htmega-addons' ),
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 500,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega_buddypress_single' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
        $this->end_controls_section();

        // Title style tab
        $this->start_controls_section(
            'buddypress_title_style',
            [
                'label' => __( 'Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->start_controls_tabs('title_style_tabs');

                $this->start_controls_tab(
                    'title_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'buddypress_title_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'=>'#000000',
                            'selectors' => [
                                '{{WRAPPER}} .buddypress_title a' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'buddypress_title_typography',
                            'label' => __( 'Typography', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .buddypress_title a',
                        ]
                    );

                    $this->add_responsive_control(
                        'buddypress_title_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .buddypress_title a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'title_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'buddypress_title_hover_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .buddypress_title a:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();


        // Meta Info style tab
        $this->start_controls_section(
            'buddypress_meta_info_style',
            [
                'label' => __( 'Meta Info', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'buddypress_meta_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'=>'#000000',
                    'selectors' => [
                        '{{WRAPPER}} .buddy_press_meta span' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'buddypress_meta_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .buddy_press_meta span',
                ]
            );

            $this->add_responsive_control(
                'buddypress_meta_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .buddy_press_meta span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        // Member Query Args.
        if( $settings['buddypress_type'] == 'group' ){
            $groups_args = array(
                'user_id'  => 0,
                'type'     => esc_attr( $settings['content_type'] ),
                'per_page' => esc_attr( $settings['max_items']['size'] ),
                'max'      => esc_attr( $settings['max_items']['size'] ),
            );
        }else{
            $members_args = array(
                'user_id'         => 0,
                'type'            => esc_attr( $settings['content_type'] ),
                'per_page'        => esc_attr( $settings['max_items']['size'] ),
                'max'             => esc_attr( $settings['max_items']['size'] ),
                'populate_extras' => true,
                'search_terms'    => false,
            );
            $avatar = array(
                'type'   => 'full',
                'width'  => esc_attr( $settings['avatar_size']['size'] ),
            );
        }

        ?>

        <div class="htmega_buddy_press_area">

            <?php if( $settings['buddypress_type'] == 'member' ): if ( bp_has_members( $members_args ) ): while ( bp_members() ) : bp_the_member(); ?>
                <div class="htmega_buddypress_single">
                    <div class="buddypress_thumbnails">
                        <a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar( $avatar ); ?></a>
                    </div>
                    <div class="buddypress_content">
                        <div class="buddypress_title">
                            <a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>
                            <?php if( $settings['show_content_meta'] == 'yes' ): ?>
                                <div class="buddy_press_meta">
                                    <?php
                                        if( $settings['show_active_time'] == 'yes' ){
                                            echo '<span class="buddy_press_active_time">'.esc_html( bp_get_member_last_active() ).'</span>';
                                        }
                                        if( $settings['show_register_time'] == 'yes' ){
                                            echo '<span class="buddy_press_register_time">'.esc_html( bp_get_member_registered() ).'</span>';
                                        }
                                        if( $settings['show_friend_count'] == 'yes' ){
                                            echo '<span class="buddy_press_friend_count">'.esc_html( bp_get_member_total_friend_count() ).'</span>';
                                        }
                                    ?>
                                </div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            <?php endwhile; endif; endif;?>

            <?php if( $settings['buddypress_type'] == 'group' ): if ( bp_is_active( 'groups' ) && bp_has_groups( $groups_args ) ) :
            while ( bp_groups() ) : bp_the_group(); ?>
                <div class="htmega_buddypress_single">
                    <div class="buddypress_thumbnails">
                        <a href="<?php bp_group_permalink() ?>"><?php bp_group_avatar_thumb() ?></a>
                    </div>
                    <div class="buddypress_content">
                        <div class="buddypress_title">
                            <?php bp_group_link(); ?>
                            <?php if( $settings['show_content_meta'] == 'yes' ): ?>
                                <div class="buddy_press_meta">
                                    <?php
                                        if( $settings['show_active_time'] == 'yes' ){
                                            echo '<span class="buddy_press_active_time">'.esc_html( bp_get_group_last_active() ).'</span>';
                                        }
                                        if( $settings['show_register_time'] == 'yes' ){
                                            echo '<span class="buddy_press_register_time">'.esc_html( bp_get_group_date_created() ).'</span>';
                                        }
                                        if( $settings['show_friend_count'] == 'yes' ){
                                            echo '<span class="buddy_press_friend_count">'.esc_html( bp_get_group_member_count() ).'</span>';
                                        }
                                    ?>
                                </div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            <?php endwhile; endif; endif;?>

        </div>

        <?php

    }

}

