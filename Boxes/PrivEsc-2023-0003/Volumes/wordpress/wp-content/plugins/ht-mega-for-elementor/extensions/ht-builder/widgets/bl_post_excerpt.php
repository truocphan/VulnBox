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

class Bl_Post_Excerpt_ELement extends Widget_Base {

    public function get_name() {
        return 'bl-post-excerpt';
    }

    public function get_title() {
        return __( 'BL: Post Excerpt', 'htmega-addons' );
    }

    public function get_icon() {
        return 'eicon-post-excerpt';
    }

    public function get_categories() {
        return ['htmega_builder'];
    }

    protected function register_controls() {


        // Style
        $this->start_controls_section(
            'post_excerpt_style_section',
            array(
                'label' => __( 'Post Excerpt', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_control(
                'post_excerpt_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}}' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'post_excerpt_typography',
                    'label'     => __( 'Typography', 'htmega-addons' ),
                    'selector'  => '{{WRAPPER}}',
                )
            );

            $this->add_responsive_control(
                'post_excerpt_align',
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

        $post = get_post();
        
        if( Elementor::instance()->editor->is_edit_mode() ){
            echo '<h3>' . __('Post Excerpt', 'htmega-addons' ). '</h3>';
        }else{
            if ( post_password_required( $post->ID ) ) {
                echo get_the_password_form( $post->ID );
                return;
            }
            echo apply_filters( 'the_excerpt', get_the_excerpt() );
        }

    }

}
