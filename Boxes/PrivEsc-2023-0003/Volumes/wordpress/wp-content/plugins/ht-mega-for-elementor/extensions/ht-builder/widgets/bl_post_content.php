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

class Bl_Post_Content_ELement extends Widget_Base {

    public function get_name() {
        return 'bl-post-content';
    }

    public function get_title() {
        return __( 'BL: Post Content', 'htmega-addons' );
    }

    public function get_icon() {
        return 'eicon-post-content';
    }

    public function get_categories() {
        return ['htmega_builder'];
    }

    protected function register_controls() {


        // Style
        $this->start_controls_section(
            'post_content_style_section',
            array(
                'label' => __( 'Post Content', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_control(
                'post_content_color',
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
                    'name'      => 'post_content_typography',
                    'label'     => __( 'Typography', 'htmega-addons' ),
                    'selector'  => '{{WRAPPER}}',
                )
            );

            $this->add_responsive_control(
                'post_contnet_align',
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

        static $have_posts = [];
        $post = get_post();
        
        if( Elementor::instance()->editor->is_edit_mode() ){
            echo '<h3>' . __('Post Content', 'htmega-addons' ). '</h3>';
        }else{

            if ( post_password_required( $post->ID ) ) {
                echo get_the_password_form( $post->ID );
                return;
            }

            // Avoid editor recursion
            if ( isset( $have_posts[ $post->ID ] ) ) { return; }
            $have_posts[ $post->ID ] = true;
            // End avoid editor recursion

            // End avoid editor recursion
            if ( htmega_is_elementor_version( '>=', '3.2.0' ) ){
                $build_width = Elementor::$instance->documents->get( $post->ID )->is_built_with_elementor();
            }else{
                $build_width = Elementor::$instance->db->is_built_with_elementor( $post->ID );
            }
           if( $build_width ){
                echo Elementor::instance()->frontend->get_builder_content( $post->ID, true );
            }else{
                echo apply_filters( 'the_content', get_the_content() );
                wp_link_pages( [
                    'before' => '<div class="page-links"><span class="page-links-title elementor-page-links-title">' . __( 'Pages:', 'htmega-addons' ) . '</span>',
                    'after' => '</div>',
                    'link_before' => '<span>',
                    'link_after' => '</span>',
                    'pagelink' => '<span class="screen-reader-text">' . __( 'Page', 'htmega-addons' ) . ' </span>%',
                    'separator' => '<span class="screen-reader-text">, </span>',
                ] );
            }

        }
    }

    

}
