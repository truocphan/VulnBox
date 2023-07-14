<?php
namespace HTMega_Builder\Elementor\Widget;

// Elementor Classes
use Elementor\Plugin as Elementor;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Bl_Post_Title_ELement extends Widget_Base {

    public function get_name() {
        return 'bl-single-blog-title';
    }

    public function get_title() {
        return __( 'BL: Post Title', 'htmega-addons' );
    }

    public function get_icon() {
        return 'eicon-post-title';
    }

    public function get_categories() {
        return ['htmega_builder'];
    }

    protected function register_controls() {


        // Post Title
        $this->start_controls_section(
            'blog_title_content',
            [
                'label' => __( 'Post Title', 'htmega-addons' ),
            ]
        );
            $this->add_control(
                'blog_title_html_tag',
                [
                    'label'   => __( 'Title HTML Tag', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'options' => htmega_html_tag_lists(),
                    'default' => 'h1',
                ]
            );

        $this->end_controls_section();

        // Style
        $this->start_controls_section(
            'blog_title_style_section',
            array(
                'label' => __( 'Post Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_control(
                'blog_title_color',
                [
                    'label'     => __( 'Title Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .entry-title' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'blog_title_typography',
                    'label'     => __( 'Typography', 'htmega-addons' ),
                    'selector'  => '{{WRAPPER}} .entry-title',
                )
            );

            $this->add_responsive_control(
                'blog_title_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .entry-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'blog_title_align',
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
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {
        $settings   = $this->get_settings_for_display();

        $title_tag = htmega_validate_html_tag( $settings['blog_title_html_tag'] );

        if( Elementor::instance()->editor->is_edit_mode() ){
            echo sprintf( '<%1$s class="entry-title">' . __('Blog Title', 'htmega-addons' ). '</%1$s>', $title_tag );
        }else{
            echo sprintf( the_title( '<%1$s class="entry-title">', '</%1$s>', false ), $title_tag  );
        }

    }

}
