<?php
namespace WprAddons\Extensions;

use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Controls_Stack;
use Elementor\Element_Base;
use Elementor\Repeater;
use Elementor\Utils;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Parallax_Scroll {

    public function __construct() {
        add_action( 'elementor/element/section/section_background/after_section_end', [$this, 'register_controls'], 10);
        add_action( 'elementor/frontend/section/before_render', [$this, '_before_render'], 10, 1);
        add_action( 'elementor/section/print_template', [ $this, '_print_template' ], 10, 2 );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        // FLEXBOX
        add_action('elementor/element/container/section_layout/after_section_end', [$this, 'register_controls'], 10);
        add_action('elementor/frontend/container/before_render', [$this, '_before_render'], 10, 1);
        add_action( 'elementor/container/print_template', [ $this, '_print_template' ], 10, 2 );
    }

    public function register_controls( $element ) {
        $element->start_controls_section(
            'wpr_section_parallax',
            [
                'tab' => Controls_Manager::TAB_STYLE,
                'label' =>  sprintf(esc_html__('Parallax - %s', 'wpr-addons'), Utilities::get_plugin_name()),
            ]
        );

        $element->add_control(
            'wpr_parallax',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div class="elementor-update-preview editor-wpr-preview-update"><span>Update changes to Preview</span><button class="elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply</button>',
                'separator' => 'after'
            ]
        );
        
        if ( 'on' === get_option('wpr-parallax-background', 'on') ) {

        $element->add_control(
            'parallax_video_tutorial',
            [
                'raw' => '<br><a href="https://www.youtube.com/watch?v=DcDeQ__lJbw" target="_blank">Watch Video Tutorial <span class="dashicons dashicons-video-alt3"></span></a>',
                'type' => Controls_Manager::RAW_HTML,
            ]
        );

        $element->add_control(
            'wpr_enable_jarallax',
            [
                'type'  => Controls_Manager::SWITCHER,
                'label' => __('Enable Background Parallax', 'wpr-addons'),
                'default' => 'no',
                'label_on' => __('Yes', 'wpr-addons'),
                'label_off' => __('No', 'wpr-addons'),
                'return_value' => 'yes',
                'render_type' => 'template',
                'prefix_class' => 'wpr-jarallax-'
            ]
        );

		// $element->add_control(
		// 	'parallax_item_bg_size',
		// 	[
		// 		'label' => esc_html__( 'Size', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SELECT,
		// 		'default' => 'cover',
		// 		'options' => [
		// 			'cover' => esc_html__( 'Cover', 'wpr-addons' ),
		// 			'contain' => esc_html__( 'Contain', 'wpr-addons' ),
		// 			'auto' => esc_html__( 'Auto', 'wpr-addons' ),
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}}.wpr-jarallax-yes' => 'position: relative; background-size: {{VALUE}}; background-repeat: no-repeat background-blend-mode: darken; background-position: left top;',
		// 			'{{WRAPPER}} .wpr-jarallax' => 'position: relative; background-size: {{VALUE}}; background-repeat: no-repeat; background-blend-mode: darken; background-position: left top;',
		// 		],
		// 	]
		// );

        $element->add_control(
            'speed',
            [
                'label' => __( 'Animation Speed', 'wpr-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => -1.0,
                'max' => 2.0,
                'step' => 0.1,
                'default' => 1.4,
                'render_type' => 'template',
                'condition' => [
                    'wpr_enable_jarallax' => 'yes'
                ]
            ]
        );

        if ( wpr_fs()->can_use_premium_code() && defined('WPR_ADDONS_PRO_VERSION') ) {
            \WprAddonsPro\Extensions\Wpr_Parallax_Scroll_Pro::add_control_scroll_effect($element);
        } else {
            $element->add_control(
                'scroll_effect',
                [
                    'label' => __( 'Scrolling Effect', 'wpr-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'scroll',
                    'options' => [
                        'scroll' => esc_html__( 'Scroll', 'wpr-addons' ),
                        'scale'  => esc_html__( 'Zoom', 'wpr-addons' ),
                        'pro-op' => esc_html__( 'Opacity (Pro)', 'wpr-addons' ),
                        'pro-sclo' => esc_html__('Scale Opacity (Pro)', 'wpr-addons'),
                        'pro-scrlo' => esc_html__( 'Scroll Opacity (Pro)', 'wpr-addons' )
                    ],
                    'render_type' => 'template',
                    'condition' => [
                        'wpr_enable_jarallax' => 'yes'
                    ]
                ]
            );

            // Upgrade to Pro Notice
            Utilities::upgrade_pro_notice( $element, Controls_Manager::RAW_HTML, 'parallax-background', 'scroll_effect', ['pro-op','pro-sclo','pro-scrlo'] );
        }

        $element->add_control(
            'bg_image',
            [
                'label' => __( 'Choose Image', 'wpr-addons' ),
                'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'render_type' => 'template',
                'condition' => [
                    'wpr_enable_jarallax' => 'yes'
                ]
            ]
        );

        } // end if ( 'on' === get_option('wpr-parallax-background', 'on') ) {

        $element->add_control(
            'parallax_type_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        if ( 'on' === get_option('wpr-parallax-multi-layer', 'on') ) {

        $element->add_control(
            'parallax_multi_video_tutorial',
            [
                'raw' => '<a href="https://youtu.be/DcDeQ__lJbw?t=121" target="_blank">Watch Video Tutorial <span class="dashicons dashicons-video-alt3"></span></a>',
                'type' => Controls_Manager::RAW_HTML,
            ]
        );

        $element->add_control(
            'wpr_enable_parallax_hover',
            [
                'type'  => Controls_Manager::SWITCHER,
                'label' => __('Enable Multi Layer Parallax', 'wpr-addons'),
                'default' => 'no',
                'label_on' => __('Yes', 'wpr-addons'),
                'label_off' => __('No', 'wpr-addons'),
                'return_value' => 'yes',
                'render_type' => 'template',
                'prefix_class' => 'wpr-parallax-'
            ]
        );

        $element->add_control(
            'invert_direction',
            [
                'type'  => Controls_Manager::SWITCHER,
                'label' => __('Invert Animation Direction', 'wpr-addons'),
                'default' => 'no',
                'label_on' => __('Yes', 'wpr-addons'),
                'label_off' => __('No', 'wpr-addons'),
                'return_value' => 'yes',
                'render_type' => 'template',
                'condition' => [
                    'wpr_enable_parallax_hover' => 'yes'
                ]
            ]
        );

        $element->add_control(
            'scalar_speed',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Animation Speed', 'wpr-addons' ),
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 0.0,
                        'max' => 100.0,
                    ]
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 10.0,
                ],
                'condition' => [
                    'wpr_enable_parallax_hover' => 'yes'
                ]
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'repeater_bg_image',
            [
                'label' => __( 'Choose Image', 'wpr-addons' ),
                'type' => Controls_Manager::MEDIA,
				// 'dynamic' => [
				// 	'active' => true,
				// ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'render_type' => 'template',
            ]
        );

        $repeater->add_control(
            'layer_width',
            [
                'label' => esc_html__( 'Image Width', 'wpr-addons' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 100,
                'min' => 0,
                'max' => 1000,
                'step' => 10,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.wpr-parallax-ml-children' => 'width: {{SIZE}}px !important;',
                ],      
            ]
        );

        $repeater->add_responsive_control(
            'layer_position_hr',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Horizontal Position (%)', 'wpr-addons' ),
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.wpr-parallax-ml-children' => 'left: {{SIZE}}{{UNIT}} !important;',
                ],
                'separator' => 'before',
            ]
        );

        $repeater->add_responsive_control(
            'layer_position_vr',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Vertical Position (%)', 'wpr-addons' ),
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.wpr-parallax-ml-children' => 'top: {{SIZE}}{{UNIT}}!important;',
                ],
            ]
        );

        $repeater->add_control(
            'data_depth',
            [
                'label' => __( 'Data Depth', 'wpr-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => -1.0,
                'max' => 2.0,
                'step' => 0.1,
                'default' => 0.4,
                'render_type' => 'template',
            ]
        );

        $element->add_control(
            'hover_parallax',
            [
                'label' => __( 'Repeater List', 'wpr-addons' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'layer_position_vr' => [
                            'unit' => '%',
                            'size' => 30,
                        ],
                        'layer_position_hr' => [
                            'unit' => '%',
                            'size' => 40,
                        ],
                    ],
                    [
                        'layer_position_vr' => [
                            'unit' => '%',
                            'size' => 60,
                        ],
                        'layer_position_hr' => [
                            'unit' => '%',
                            'size' => 20,
                        ],
                    ],
                ],
                'condition' => [
                    'wpr_enable_parallax_hover' => 'yes'
                ]
            ]
        );

        if ( ! wpr_fs()->can_use_premium_code() ) {
            $element->add_control(
                'paralax_repeater_pro_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => 'More than 2 Layers are available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-parallax-multi-layer-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
                    // 'raw' => 'More than 2 Layers are available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
                    'content_classes' => 'wpr-pro-notice',
                    'condition' => [
                        'wpr_enable_parallax_hover' => 'yes'
                    ]
                ]
            );
        }

        } // end if ( 'on' === get_option('wpr-parallax-multi-layer', 'on') ) {

        $element->end_controls_section();
    }

    public function _before_render( $element ) {
        // bail if any other element but section
        // output buffer controlling functions removed elements from live preview

        if ( $element->get_name() !== 'section' && $element->get_name() !== 'container' ) return;

        $settings = $element->get_settings_for_display();

        // Parallax Background
        if ( 'on' === get_option('wpr-parallax-background', 'on') ) {
            if ( 'yes' === $settings['wpr_enable_jarallax'] ) { 
                $element->add_render_attribute( '_wrapper', [
                    'class' => 'wpr-jarallax',
                    'speed-data' => $settings['speed'],
                    'bg-image' => $settings['bg_image']['url'],
                    'scroll-effect' => $settings['scroll_effect'],
                ] );

                // if ( 'on' === get_option('wpr-parallax-background', 'on') ) {
                //     echo '<div '. $element->get_render_attribute_string( '_wrapper' ) .'></div>';
                // }
            }
        }

        // Parallax Multi Layer
        if ( 'on' === get_option('wpr-parallax-multi-layer', 'on') ) {
            if ( $settings['wpr_enable_parallax_hover'] == 'yes' ) {
                 if ( $settings['hover_parallax'] ) {

                    echo '<div class="wpr-parallax-multi-layer" scalar-speed="'. esc_attr($settings['scalar_speed']['size']) .'" direction="'. esc_attr($settings['invert_direction']) .'" style="overflow: hidden;">';

                    foreach (  $settings['hover_parallax'] as $key => $item ) {
                        if ( $key < 2 || wpr_fs()->can_use_premium_code() ) {
                            echo '<div data-depth="'. esc_attr($item['data_depth']) .'" style-top="'. esc_attr($item['layer_position_vr']['size']) .'%" style-left="'. esc_attr($item['layer_position_hr']['size']) .'%" class="wpr-parallax-ml-children elementor-repeater-item-'. esc_attr($item['_id']) .'">';
                                echo '<img src="'. esc_url($item['repeater_bg_image']['url']) .'">';
                            echo '</div>';
                        }
                    }
                     
                    echo '</div>';
                 }
            }
        }

    }

    public function _print_template( $template, $widget ) {
        ob_start();
        
        if ( 'on' === get_option('wpr-parallax-background', 'on') ) {
            echo '<div class="wpr-jarallax" speed-data-editor="{{settings.speed}}" scroll-effect-editor="{{settings.scroll_effect}}" bg-image-editor="{{settings.bg_image.url}}"></div>';
        }
        // Multi Layer
        if ( 'on' === get_option('wpr-parallax-multi-layer', 'on') ) {
            if ( ! wpr_fs()->can_use_premium_code() ) {
                ?>
                <# if ( settings.hover_parallax.length && settings.wpr_enable_parallax_hover == 'yes') { #>
                    <div class="wpr-parallax-multi-layer" direction="{{settings.invert_direction}}" scalar-speed="{{settings.scalar_speed.size}}" data-relative-input="true" style="overflow: hidden;">
                    <# _.each( settings.hover_parallax, function( item, index ) { #>
                    <# if ( index > 1 ) return; #>
                        <div data-depth="{{item.data_depth}}" class="wpr-parallax-ml-children elementor-repeater-item-{{ item._id }}">  
                            <img src="{{item.repeater_bg_image.url}}">
                        </div>
                    <# }); #>
                    </div>
                <# } #>
                <?php
            } else {
                ?>
                <# if ( settings.hover_parallax.length && settings.wpr_enable_parallax_hover == 'yes') { #>
                    <div class="wpr-parallax-multi-layer" direction="{{settings.invert_direction}}" scalar-speed="{{settings.scalar_speed.size}}" data-relative-input="true" style="overflow: hidden;">
                    <# _.each( settings.hover_parallax, function( item ) { #>
                        <div data-depth="{{item.data_depth}}" class="wpr-parallax-ml-children elementor-repeater-item-{{ item._id }}">  
                            <img src="{{item.repeater_bg_image.url}}">
                        </div>
                    <# }); #>
                    </div>
                <# } #>
                <?php
            }
        }

        $parallax_content = ob_get_contents();

        ob_end_clean();
        return $template . $parallax_content;
    }

    public static function enqueue_scripts() {
        if ( 'on' === get_option('wpr-parallax-background', 'on') ) {
            wp_enqueue_script( 'wpr-jarallax', WPR_ADDONS_URL . 'assets/js/lib/jarallax/jarallax.min.js', ['jquery'], '1.12.7', true );
        }

        if ( 'on' === get_option('wpr-parallax-multi-layer', 'on') ) {
            wp_enqueue_script( 'wpr-parallax-hover', WPR_ADDONS_URL . 'assets/js/lib/parallax/parallax.min.js', ['jquery'], '1.0', true );
        }
    }

}

new Wpr_Parallax_Scroll();
