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

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Bl_Post_Author_Info_ELement extends Widget_Base {

    public function get_name() {
        return 'bl-post-author-info';
    }

    public function get_title() {
        return __( 'BL: Author Info', 'htmega-addons' );
    }

    public function get_icon() {
        return 'eicon-person';
    }

    public function get_categories() {
        return ['htmega_builder'];
    }

    protected function register_controls() {

        // Post Title
        $this->start_controls_section(
            'post_author_info_box',
            [
                'label' => __( 'Author Info', 'htmega-addons' ),
            ]
        );
            $this->add_control(
                'show_name',
                [
                    'label' => __( 'Show Title', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'name_html_tag',
                [
                    'label'   => __( 'Name HTML Tag', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'options' => htmega_html_tag_lists(),
                    'default' => 'h5',
                    'condition'=>[
                        'show_name' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'show_avater_image',
                [
                    'label'         => __( 'Show Avatar Image', 'htmega-addons' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => __( 'Show', 'htmega-addons' ),
                    'label_off'     => __( 'Hide', 'htmega-addons' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                ]
            );

            $this->add_control(
                'show_biography',
                [
                    'label'        => __( 'Show Biography', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => __( 'Show', 'htmega-addons' ),
                    'label_off'    => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default'      => 'yes',
                ]
            );

            $this->add_control(
                'author_link_to',
                [
                    'label' => __( 'Link', 'htmega-addons' ),
                    'type'  => Controls_Manager::SELECT,
                    'options' => [
                        ''              => __( 'None', 'htmega-addons' ),
                        'website'       => __( 'Website', 'htmega-addons' ),
                        'admin_archive' => __( 'Admin Posts', 'htmega-addons' ),
                    ],
                    'description'       => __( 'Link for the Author Name and Image', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'avater_image_position',
                [
                    'label'   => __( 'Avater Image Position', 'htmega-addons' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'htmega-addons' ),
                            'icon'  => 'eicon-h-align-left',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'htmega-addons' ),
                            'icon'  => 'eicon-h-align-right',
                        ],
                        'top' => [
                            'title' => __( 'Top', 'htmega-addons' ),
                            'icon'  => 'eicon-v-align-top',
                        ],
                        'bottom' => [
                            'title' => __( 'Bottom', 'htmega-addons' ),
                            'icon'  => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default'     => 'left',
                    'label_block' => true,
                ]
            );

        $this->end_controls_section();

        // Avatar Image Style
        $this->start_controls_section(
            'avatar_style_section',
            array(
                'label' => __( 'Image', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->add_control(
                'avatar_width',
                [
                    'label' => __( 'Width', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 300,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 150,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htavatar img' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'avatar_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htavatar img',
                ]
            );

            $this->add_control(
                'avatar_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htavatar img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'avatar_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htavatar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Content Style
        $this->start_controls_section(
            'content_style_section',
            array(
                'label' => __( 'Content', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->add_control(
                'heading_name_style',
                [
                    'label' => __( 'Name', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'author_name_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htauthor-info .htauthor-name' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'author_name_typography',
                    'selector' => '{{WRAPPER}} .htauthor-info .htauthor-name',
                ]
            );

            $this->add_control(
                'author_name_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htauthor-info .htauthor-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'heading_bio_style',
                [
                    'label' => __( 'Biography', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'author_bio_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htauthor-info .htbuilder-bio' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'author_bio_typography',
                    'selector' => '{{WRAPPER}} .htauthor-info .htbuilder-bio',
                ]
            );

            $this->add_control(
                'author_bio_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htauthor-info .htbuilder-bio' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {
        $settings   = $this->get_settings_for_display();
        $author_info = [];
        $author_link = '';
        $avatar_args['size'] = 300;

        $user_id = get_the_author_meta( 'ID' );
        $author_info['avatar'] = get_avatar_url( $user_id, $avatar_args );
        $author_info['display_name'] = get_the_author_meta( 'display_name' );
        $author_info['website'] = get_the_author_meta( 'user_url' );
        $author_info['bio'] = get_the_author_meta( 'description' );
        $author_info['posts_url'] = get_author_posts_url( $user_id );

        // Aavater image
        if ( $settings['show_avater_image'] == 'yes' ) {
            $this->add_render_attribute( 'avatar_attr', 'src', $author_info['avatar'] );
            if ( ! empty( $author_info['display_name'] ) ) {
                $this->add_render_attribute( 'avatar_attr', 'alt', $author_info['display_name'] );
            }
        }

        // Author Link
        if( ( $settings['author_link_to'] == 'website' ) && !empty( $author_info['website'] ) ){
            $author_link = $author_info['website'];
        }elseif( ( $settings['author_link_to'] == 'admin_archive' ) && !empty( $author_info['posts_url'] ) ){
            $author_link = $author_info['posts_url'];
        }else{
            $author_link = $author_link;
        }

        $name_tag = htmega_validate_html_tag( $settings['name_html_tag'] );

        ?>
            <div class="htbuilder-author-box htavaterpos-<?php echo esc_attr($settings['avater_image_position']); ?>">
                <?php if ( $settings['show_avater_image'] == 'yes' ): ?>
                    <div class="htavatar">
                        <?php
                            if( !empty( $author_link ) ){
                                echo sprintf( '<a href="%1$s" target="_blank"><img %2$s ></a>',$author_link, $this->get_render_attribute_string( 'avatar_attr' ) );
                            }else{
                                echo sprintf( '<img %1$s >', $this->get_render_attribute_string( 'avatar_attr' ) );
                            }
                        ?>
                    </div>
                <?php endif; ?>
                <div class="htauthor-info">
                    <?php
                        if( $settings['show_name'] == 'yes' ){
                            if( !empty( $author_link ) ){
                                echo sprintf( '<a href="%1$s" target="_blank"><%2$s class="htauthor-name">%3$s</%2$s></a>', $author_link, $name_tag, $author_info['display_name'] );
                            }else{
                                echo sprintf( '<%1$s class="htauthor-name">%2$s</%1$s>', $name_tag, $author_info['display_name'] );
                            }
                        }
                        if( $settings['show_biography'] == 'yes' ){
                            echo '<div class="htbuilder-bio">'.esc_html($author_info['bio']).'</div>';
                        }
                    ?>
                </div>
            </div>
        <?php

    }

}
