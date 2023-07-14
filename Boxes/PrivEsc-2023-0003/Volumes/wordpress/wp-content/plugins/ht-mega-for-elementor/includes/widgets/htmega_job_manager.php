<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Job_Manager extends Widget_Base {

    public function get_name() {
        return 'htmega-jobmanager-addons';
    }
    
    public function get_title() {
        return __( 'Job Manager', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-site-title';
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
        return [ 'job manager', 'job manager widget', 'job list','htmega','htmega' ];
    }

    public function get_help_url() {
		return 'https://wphtmega.com/docs/3rd-party-plugin-widgets/job-manager/';
	}

    protected function register_controls() {

        $this->start_controls_section(
            'jobmanager_content',
            [
                'label' => __( 'Job Manager', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'job_layout',
                [
                    'label'   => __( 'Layout', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'list',
                    'options' => [
                        'list'          => __( 'Job List', 'htmega-addons' ),
                        'summary'       => __( 'Job Summary', 'htmega-addons' ),
                        'applyjob'      => __( 'Job Apply To', 'htmega-addons' ),
                        'jobform'       => __( 'Job Post Form', 'htmega-addons' ),
                        'jobdashboard'  => __( 'Job Dashboard', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'item_number',
                [
                    'label' => __( 'Number of listings to show', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 5,
                    'label_block'=>true,
                    'condition'=>[
                        'job_layout'=> [ 'list', 'summary' ],
                    ],
                ]
            );

            $this->add_control(
                'order',
                [
                    'label'   => __( 'Order', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'desc',
                    'options' => [
                        'asc'  => __( 'Ascending', 'htmega-addons' ),
                        'desc' => __( 'Descending', 'htmega-addons' ),
                    ],
                    'condition'=>[
                        'job_layout'=>'list',
                    ],
                ]
            );

            $this->add_control(
                'order_by',
                [
                    'label'   => __( 'Order By', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'date',
                    'options' => [
                        'date'          => __( 'Date', 'htmega-addons' ),
                        'title'         => __( 'Title', 'htmega-addons' ),
                        'author'        => __( 'Author', 'htmega-addons' ),
                        'rand_featured' => __( 'Random', 'htmega-addons' ),
                    ],
                    'condition'=>[
                        'job_layout'=>'list',
                    ],
                ]
            );

            $this->add_control(
                'featured_jobs',
                [
                    'label'        => __( 'Feature Jobs only', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'condition'=>[
                        'job_layout'=> [ 'list', 'summary' ],
                    ],
                ]
            );

            $this->add_control(
                'pagination_type',
                [
                    'label'   => __( 'Pagination Type', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'loadmore',
                    'options' => [
                        'none'          => __( 'None', 'htmega-addons' ),
                        'number'        => __( 'Number', 'htmega-addons' ),
                        'loadmore'      => __( 'Load More', 'htmega-addons' ),
                    ],
                    'condition'=>[
                        'job_layout'=>'list',
                    ]
                ]
            );

            $this->add_control(
                'show_filters',
                [
                    'label'        => __( 'Filters', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition'=>[
                        'job_layout'=>'list',
                    ]
                ]
            );

            $this->add_control(
                'content_align',
                [
                    'label'   => __( 'Content Alignment', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'left',
                    'options' => [
                        'left'  => __( 'Left', 'htmega-addons' ),
                        'right' => __( 'Right', 'htmega-addons' ),
                    ],
                    'condition'=>[
                        'job_layout'=>'summary',
                    ],
                ]
            );

            $this->add_control(
                'content_width',
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
                        'size' => 250,
                    ],
                    'condition'=>[
                        'job_layout'=>'summary',
                    ],
                ]
            );

            if( class_exists('WP_Job_Manager') ){
                $job_list = get_posts( ['numberposts' => -1, 'post_type' => 'job_listing',] );
                $job_list_options = ['0' => esc_html__( 'Select Job', 'htmega-addons' ) ];
                foreach ( $job_list as $list ) :
                    $job_list_options[ $list->ID ] = $list->post_title;
                endforeach;
                $this->add_control(
                    'job_id',
                    [
                        'label' => __( 'Select Job', 'htmega-addons' ),
                        'type'        => Controls_Manager::SELECT2,
                        'options'     => $job_list_options,
                        'default'     => ['0'],
                        'condition'=>[
                            'job_layout'=>'applyjob',
                        ],
                    ]
                );
            }
            
        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        // Job List
        if( $settings['job_layout'] == 'list' ){
            $jobmanager_attributes = [
                'per_page'          => $settings['item_number'],
                'orderby'           => $settings['order_by'],
                'order'             => $settings['order'],
                'featured'          => ( 'yes' === $settings['featured_jobs'] ) ? true : null,
                'show_filters'      => ( 'yes' === $settings['show_filters'] ) ? true : false,
            ];
            if( $settings['pagination_type'] == 'number' ){
                $jobmanager_attributes['show_pagination']  = true;
            }elseif( $settings['pagination_type'] == 'loadmore' ){
                $jobmanager_attributes['show_more']  = true;
            }else{
                $jobmanager_attributes['show_pagination']  = false;
                $jobmanager_attributes['show_more']  = false;
            }
            $this->add_render_attribute( 'shortcode', $jobmanager_attributes );
            echo do_shortcode( sprintf( '[jobs %s]', $this->get_render_attribute_string( 'shortcode' ) ) );
        }

        // Job summary
        if( $settings['job_layout'] == 'summary' ){
            $job_summary_atts = [
                'limit'     => $settings['item_number'],
                'featured'  => ( 'yes' === $settings['featured_jobs'] ) ? true : null,
                'align'     => $settings['content_align'],
                'width'     => $settings['content_width']['size'].$settings['content_width']['unit'],
            ];
            $this->add_render_attribute( 'shortcodesummary', $job_summary_atts );
            echo do_shortcode( sprintf( '[job_summary %s]', $this->get_render_attribute_string( 'shortcodesummary' ) ) );
        }

        // Job apply
        if( $settings['job_layout'] == 'applyjob' ){
            $job_apply_attributes = [
                'id' => $settings['job_id'],
            ];
            $this->add_render_attribute( 'shortcodeapplyjob', $job_apply_attributes );
            echo do_shortcode( sprintf( '[job_apply %s]', $this->get_render_attribute_string( 'shortcodeapplyjob' ) ) );
        }

        // Job Post Form
        if( $settings['job_layout'] == 'jobform' ){
            echo do_shortcode( '[submit_job_form]' );
        }

        // Job Dashboard
        if( $settings['job_layout'] == 'jobdashboard' ){
            echo do_shortcode( '[job_dashboard]' );
        }
        

    }

}

