<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Bbpress extends Widget_Base {

    public function get_name() {
        return 'htmega-bbpress-addons';
    }
    
    public function get_title() {
        return __( 'Bbpress', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-form-horizontal';
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
        return [ 'bbpress', 'bbpress widget', 'forum', 'reply','htmega','htmega' ];
    }

    public function get_help_url() {
		return 'https://wphtmega.com/docs/3rd-party-plugin-widgets/bbpress-widget/';
	}
    
    protected function register_controls() {

        $this->start_controls_section(
            'bbpress_content',
            [
                'label' => __( 'Bbpress', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'bbpress_layout',
                [
                    'label'   => __( 'Layout', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'forum-index',
                    'options' => [
                        'forum-index'  => __('Forum Index', 'htmega-addons'),
                        'forum-form'   => __('Forum Form', 'htmega-addons'),
                        'single-forum' => __('Single Forum', 'htmega-addons'),
                        'topic-index'  => __('Topic Index', 'htmega-addons'),
                        'topic-form'   => __('Topic Form', 'htmega-addons'),
                        'single-topic' => __('Single Topic', 'htmega-addons'),
                        'reply-form'   => __('Reply Form', 'htmega-addons'),
                        'single-reply' => __('Single Reply', 'htmega-addons'),
                        'topic-tags'   => __('Topic Tags', 'htmega-addons'),
                        'single-tag'   => __('Single Tag', 'htmega-addons'),
                        'single-view'  => __('Single View', 'htmega-addons'),
                        'stats'        => __('Stats', 'htmega-addons'),
                    ],
                ]
            );

            $this->add_control(
                'bbpress_id',
                [
                    'label'       => __( 'ID', 'htmega-addons' ),
                    'type'        => Controls_Manager::TEXT,
                    'condition'   => [
                        'bbpress_layout' => array( 'single-forum', 'single-topic', 'single-reply', 'single-tag', 'single-view' )
                    ],
                ]
            );
            
        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $layout = array( 'single-forum', 'single-topic', 'single-reply', 'single-tag', 'single-view' );
        $bbpress_attributes = array();

        if ( isset( $settings['bbpress_id'] ) ) {
            $bbpress_attributes = array( ' id' => $settings['bbpress_id'] );
        } elseif ( $settings['bbpress_layout'] == 'topic-form' && isset( $settings['bbpress_id'] )) {
            $bbpress_attributes = array( ' forum_id' => $settings['bbpress_id'] );
        }
        $this->add_render_attribute( 'shortcode', $bbpress_attributes );

        echo do_shortcode( sprintf( '[bbp-'.$settings['bbpress_layout'].'%s]', $this->get_render_attribute_string( 'shortcode' ) ));

    }

}

