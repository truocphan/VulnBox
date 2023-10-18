<?php
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Controls_Stack;
use Elementor\Element_Base;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Core\Base\Module;
use Elementor\Core\Kits\Documents\Tabs\Settings_Layout;
use Elementor\Core\Responsive\Files\Frontend;
use Elementor\Plugin;
use Elementor\Core\Responsive\Responsive;
use Elementor\Core\Breakpoints\Manager;
use Elementor\Core\Breakpoints;
use Elementor\Group_Control_Box_Shadow;
use WprAddons\Classes\Utilities;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Sticky_Section {

    public function __construct() {
		add_action( 'elementor/element/section/section_background/after_section_end', [ $this, 'register_controls' ], 10 );
		add_action( 'elementor/section/print_template', [ $this, '_print_template' ], 10, 2 );
		add_action( 'elementor/frontend/section/before_render', [ $this, '_before_render' ], 10, 1 );

        // FLEXBOX
        add_action('elementor/element/container/section_layout/after_section_end', [$this, 'register_controls'], 10);
        add_action( 'elementor/container/print_template', [ $this, '_print_template' ], 10, 2 );
        add_action('elementor/frontend/container/before_render', [$this, '_before_render'], 10, 1);
    }

	public static function add_control_group_sticky_advanced_options($element) {

		$element->add_control(
			'sticky_advanced_pro_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<strong>Advanced Options</strong> are available in the <br><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-advanced-stiky-upgrade-pro#purchasepro" target="_blank">Pro Version.</a> You\'ll have the ability to create impressive menu effects. Preview some examples: <strong><a href="https://demosites.royal-elementor-addons.com/fashion-v2/?ref=rea-plugin-panel-advanced-stiky-preview" target="_blank">Demo 1, </a><a href="https://demosites.royal-elementor-addons.com/digital-marketing-agency-v2/?ref=rea-plugin-panel-advanced-stiky-preview" target="_blank">Demo 2, </a><a href="https://demosites.royal-elementor-addons.com/personal-blog-v1/?ref=rea-plugin-panel-advanced-stiky-preview" target="_blank">Demo 3, </a><a href="https://demosites.royal-elementor-addons.com/digital-marketing-agency-v1/?ref=rea-plugin-panel-advanced-stiky-preview" target="_blank">Demo 4, </a><a href="https://demosites.royal-elementor-addons.com/construction-v3/?ref=rea-plugin-panel-advanced-stiky-preview" target="_blank">Demo 5</a></strong><a href="https://www.youtube.com/watch?v=ORay3VWrWuc" target="_blank" style="display: block; color: #f51f3d; margin-top: 10px; margin-bottom: 10px;">Watch Video Tutorial</a><img src="'. WPR_ADDONS_ASSETS_URL .'img/pro-options/sticky-section-pro-features.jpg" style="border: 1px solid #93003C;">',
				'content_classes' => 'wpr-pro-notice',
                'condition' => [
                    'enable_sticky_section' => 'yes'
                ]
			]
		);
	}

    public function register_controls( $element ) {

		if ( ( 'section' === $element->get_name() || 'container' === $element->get_name() ) ) {

			$element->start_controls_section (
				'wpr_section_sticky_section',
				[
					'tab'   => Controls_Manager::TAB_ADVANCED,
					'label' =>  sprintf(esc_html__('Sticky Section - %s', 'wpr-addons'), Utilities::get_plugin_name()),
				]
			);

			$element->add_control(
				'wpr_sticky_apply_changes',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<div class="elementor-update-preview editor-wpr-preview-update"><span>Update changes to Preview</span><button class="elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply</button>',
					'separator' => 'after'
				]
			);

			$element->add_control (
				'enable_sticky_section',
				[
					'type' => Controls_Manager::SWITCHER,
					'label' => esc_html__( 'Make This Section Sticky', 'wpr-addons' ),
					'default' => 'no',
					'return_value' => 'yes',
					'prefix_class' => 'wpr-sticky-section-',
					'render_type' => 'template',
				]
			);

			$element->add_control(
				'enable_on_devices',
				[
					'label' => esc_html__( 'Enable on Devices', 'wpr-addons' ),
					'label_block' => true,
					'type' => Controls_Manager::SELECT2,
					'default' => ['desktop_sticky'],
					'options' => $this->breakpoints_manager(),
					'multiple' => true,
					'separator' => 'before',
					'condition' => [
						'enable_sticky_section' => 'yes'
					],

				]
			);
            
			$element->add_control (
				'position_type',
				[
					'label' => __( 'Position Type', 'wpr-addons' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'sticky',
					'options' => [
						'sticky'  => __( 'Stick on Scroll', 'wpr-addons' ),
						'fixed' => __( 'Fixed by Default', 'wpr-addons' ),
					],
                    // 'selectors' => [
					// 	'{{WRAPPER}}' => 'position: {{VALUE}};',
                    // ],
					'render_type' => 'template',
					'condition' => [
						'enable_sticky_section' => 'yes'
					],
				]
			);
            
			$element->add_control (
				'sticky_type',
				[
					'label' => __( 'Sticky Relation', 'wpr-addons' ),
					'type' => Controls_Manager::SELECT,
					'description' => __('Please switch to *Window* if you are going to use <span style="color: red;">*Advanced Options*</span>.', 'wpr-addons'),
					'default' => 'sticky',
					'options' => [
						'sticky'  => __( 'Parent', 'wpr-addons' ),
						'fixed' => __( 'Window', 'wpr-addons' ),
					],
					'render_type' => 'template',
					'condition' => [
						'enable_sticky_section' => 'yes',
						'position_type' => 'sticky'
					],
				]
			);
            
			$element->add_control (
				'position_location',
				[
					'label' => __( 'Location', 'wpr-addons' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'top',
					'render_type' => 'template',
					'options' => [
						'top' => __( 'Top', 'wpr-addons' ),
						'bottom'  => __( 'Bottom', 'wpr-addons' ),
					],
					// 'selectors_dictionary' => [
					// 	'top' => 'top: {{position_offset.VALUE}}px; bottom: auto;',
					// 	'bottom' => 'bottom: {{position_offset.VALUE}}px; top: auto;'
					// ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'top: auto; bottom: auto; {{VALUE}}: {{position_offset.VALUE}}px;',
                    ],
					'condition' => [
						'enable_sticky_section' => 'yes'
					]
				]
			);
			
			$element->add_responsive_control(
				'position_offset',
				[
					'label' => __( 'Offset', 'wpr-addons' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 500,
					'required' => true,
					'frontend_available' => true,
					'render_type' => 'template',
					'default' => 0,
					'widescreen_default' => 0,
					'laptop_default' => 0,
					'tablet_extra_default' => 0,
					'tablet_default' => 0,
					'mobile_extra_default' => 0,
					'mobile_default' => 0,
                    'selectors' => [
                        '{{WRAPPER}}' => 'top: auto; bottom: auto; {{position_location.VALUE}}: {{VALUE}}px;',
                        '{{WRAPPER}} + .wpr-hidden-header' => 'top: {{VALUE}}px;'
                    ],
					'condition' => [
						'enable_sticky_section' => 'yes'
					],
				]
			);
                
            $element->add_control(
                'wpr_z_index',
                [
                    'label' => esc_html__( 'Z-Index', 'wpr-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => -99,
					'max' => 99999,
					'step' => 1,
                    'default' => 10,
                    'selectors' => [
						'{{WRAPPER}}' => 'z-index: {{VALUE}};',
                        '.wpr-hidden-header' => 'z-index: {{VALUE}};'
                    ],
					'condition' => [
						'enable_sticky_section' => 'yes'
					]
                ]
            );

			$element->add_control(
				'custom_breakpoints',
				[
					'label' => __( 'Breakpoints', 'wpr-addons' ),
					'type' => \Elementor\Controls_Manager::HIDDEN,
					'default' => get_option('elementor_experiment-additional_custom_breakpoints'),
					'condition' => [
						'enable_sticky_section' => 'yes'
					]
				]
			);

			$element->add_control(
				'active_breakpoints',
				[
					'label' => __( 'Active Breakpoints', 'wpr-addons' ),
					'type' => \Elementor\Controls_Manager::HIDDEN,
					'default' => $this->breakpoints_manager_active(),
					'condition' => [
						'enable_sticky_section' => 'yes'
					]
				]
			);

			if ( wpr_fs()->can_use_premium_code() && defined('WPR_ADDONS_PRO_VERSION') ) {
				if ( class_exists('WprAddonsPro\Extensions\Wpr_Sticky_Section_Pro') ) {
					\WprAddonsPro\Extensions\Wpr_Sticky_Section_Pro::add_control_group_sticky_advanced_options($element);
				}
			} else {
				$this->add_control_group_sticky_advanced_options($element);
			}

            $element->end_controls_section();            
        }
    }

	public function breakpoints_manager() {
		$active_breakpoints = [];
		foreach ( \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints() as $key => $value ) {
			$active_breakpoints[$key . '_sticky'] = esc_html__(ucwords(preg_replace('/_/i', ' ', $key)), 'wpr-addons');
		}

		$active_breakpoints['desktop_sticky'] = esc_html__('Desktop', 'wpr-addons');
		return $active_breakpoints;
	}

	public function breakpoints_manager_active() {
		$active_breakpoints = [];

		foreach ( $this->breakpoints_manager() as $key => $value ) {
			array_push($active_breakpoints, $key);
		}

		return $active_breakpoints;
	}
    
    public function _before_render( $element ) {
        if ( $element->get_name() !== 'section' && $element->get_name() !== 'container' ) {
            return;
        }
		
        $settings = $element->get_settings_for_display();

		if ($settings['enable_sticky_section'] !== 'yes') return;

		$wpr_sticky_effects_offset_widescreen = isset($settings['wpr_sticky_effects_offset_widescreen']) && !empty($settings['wpr_sticky_effects_offset_widescreen']) ? $settings['wpr_sticky_effects_offset_widescreen'] : 0;
		$wpr_sticky_effects_offset_desktop = isset($settings['wpr_sticky_effects_offset']) && !empty($settings['wpr_sticky_effects_offset']) ? $settings['wpr_sticky_effects_offset'] : $wpr_sticky_effects_offset_widescreen;
		$wpr_sticky_effects_offset_laptop =  isset($settings['wpr_sticky_effects_offset_laptop']) && !empty($settings['wpr_sticky_effects_offset_laptop']) ? $settings['wpr_sticky_effects_offset_laptop'] : $wpr_sticky_effects_offset_desktop;
		$wpr_sticky_effects_offset_tablet_extra =  isset($settings['wpr_sticky_effects_offset_tablet_extra']) && !empty($settings['wpr_sticky_effects_offset_tablet_extra']) ? $settings['wpr_sticky_effects_offset_tablet_extra'] : $wpr_sticky_effects_offset_laptop;
		$wpr_sticky_effects_offset_tablet =  isset($settings['wpr_sticky_effects_offset_tablet']) && !empty($settings['wpr_sticky_effects_offset_tablet']) ? $settings['wpr_sticky_effects_offset_tablet'] : $wpr_sticky_effects_offset_tablet_extra;
		$wpr_sticky_effects_offset_mobile_extra =  isset($settings['wpr_sticky_effects_offset_mobile_extra']) && !empty($settings['wpr_sticky_effects_offset_mobile_extra']) ? $settings['wpr_sticky_effects_offset_mobile_extra'] : $wpr_sticky_effects_offset_tablet;
		$wpr_sticky_effects_offset_mobile =  isset($settings['wpr_sticky_effects_offset_mobile']) && !empty($settings['wpr_sticky_effects_offset_mobile']) ? $settings['wpr_sticky_effects_offset_mobile'] : $wpr_sticky_effects_offset_mobile_extra;
		
        if ( $settings['enable_sticky_section'] === 'yes' ) {
            $element->add_render_attribute( '_wrapper', [
                'data-wpr-sticky-section' => $settings['enable_sticky_section'],
                'data-wpr-position-type' => $settings['position_type'],
                'data-wpr-position-offset' => $settings['position_offset'],
                'data-wpr-position-location' => $settings['position_location'],
				'data-wpr-sticky-devices' => $settings['enable_on_devices'],
				'data-wpr-custom-breakpoints' => $settings['custom_breakpoints'],
				'data-wpr-active-breakpoints' => $this->breakpoints_manager_active(),
				'data-wpr-z-index' => $settings['wpr_z_index'],
				'data-wpr-sticky-hide' => isset($settings['sticky_hide']) ? $settings['sticky_hide'] : '',
				'data-wpr-replace-header' => isset($settings['sticky_replace_header']) ? $settings['sticky_replace_header'] : '',
				'data-wpr-animation-duration' => isset($settings['sticky_animation_duration']) ? $settings['sticky_animation_duration'] : '',
				'data-wpr-sticky-type' => isset($settings['sticky_type']) ? $settings['sticky_type'] : '',
				// 'data-wpr-offset-settings' => wp_json_encode([
				// 	'widescreen' => $wpr_sticky_effects_offset_widescreen,
				// 	'desktop' => $wpr_sticky_effects_offset_desktop,
				// 	'laptop' => $wpr_sticky_effects_offset_laptop,
				// 	'tablet_extra' => $wpr_sticky_effects_offset_tablet_extra,
				// 	'tablet' => $wpr_sticky_effects_offset_tablet,
				// 	'mobile_extra' => $wpr_sticky_effects_offset_mobile_extra,
				// 	'mobile' => $wpr_sticky_effects_offset_mobile
				// ])
            ] );
        }
    }

    public function _print_template( $template, $widget ) {
		if ( $widget->get_name() !== 'section' && $widget->get_name() !== 'container' ) {
			return $template;
		}

		ob_start();

		?>

		<# if ( 'yes' === settings.enable_sticky_section) { #>
			<div class="wpr-sticky-section-yes-editor" data-wpr-z-index={{{settings.wpr_z_index}}} data-wpr-sticky-section={{{settings.enable_sticky_section}}} data-wpr-position-type={{{settings.position_type}}} data-wpr-position-offset={{{settings.position_offset}}} data-wpr-position-location={{{settings.position_location}}} data-wpr-sticky-devices={{{settings.enable_on_devices}}} data-wpr-custom-breakpoints={{{settings.custom_breakpoints}}} data-wpr-active-breakpoints={{{settings.active_breakpoints}}} data-wpr-sticky-animation={{{settings.sticky_animation}}}  data-wpr-offset-settings={{{settings.wpr_sticky_effects_offset}}} data-wpr-sticky-type={{{settings.sticky_type}}}></div>
		<# } #>   

		<?php
		
		// how to render attributes without creating new div using view.addRenderAttributes
		$particles_content = ob_get_contents();

		ob_end_clean();

		return $template . $particles_content;
	}
}

new Wpr_Sticky_Section();