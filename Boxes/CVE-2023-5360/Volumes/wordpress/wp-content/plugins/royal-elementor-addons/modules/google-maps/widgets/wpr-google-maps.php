<?php
namespace WprAddons\Modules\GoogleMaps\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Repeater;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Google_Maps extends Widget_Base {
	
	public function get_name() {
		return 'wpr-google-maps';
	}

	public function get_title() {
		return esc_html__( 'Google Maps', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-google-maps';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'google maps', 'location', 'gmap', 'cluster' ];
	}

	public function get_script_depends() {
		return [ 'wpr-google-maps', 'wpr-google-maps-clusters' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-google-maps-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_google_map_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		if ( '' == get_option('wpr_google_map_api_key') ) {
			$this->add_control(
				'gm_api_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf( __( 'Please enter <strong>Google Map API Key</strong> from <br><a href="%s" target="_blank">Dashboard > %s > Settings</a> tab to get this widget working.', 'wpr-addons' ), admin_url( 'admin.php?page=wpr-addons&tab=wpr_tab_settings' ), Utilities::get_plugin_name() ),
					'separator' => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}

		$this->add_control(
			'gm_type',
			[
				'label' => esc_html__( 'Select Map Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'roadmap' => esc_html__( 'Road Map', 'wpr-addons' ),
					'satellite' => esc_html__( 'Satellite', 'wpr-addons' ),
					'hybrid' => esc_html__( 'Hybrid', 'wpr-addons' ),
					'terrain' => esc_html__( 'Terrain', 'wpr-addons' ),
				],
				'default' => 'roadmap',
			]
		);

		$this->add_control(
			'gm_color_scheme',
			[
				'label' => esc_html__( 'Color Scheme', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'simple' => esc_html__( 'Simple', 'wpr-addons' ),
					'white-black' => esc_html__( 'White Black', 'wpr-addons' ),
					'light-silver' => esc_html__( 'Light Silver', 'wpr-addons' ),
					'light-grayscale' => esc_html__( 'Light Grayscale', 'wpr-addons' ),
					'subtle-grayscale' => esc_html__( 'Subtle Grayscale', 'wpr-addons' ),
					'mostly-white' => esc_html__( 'Mostly White', 'wpr-addons' ),
					'mostly-green' => esc_html__( 'Mostly Green', 'wpr-addons' ),
					'neutral-blue' => esc_html__( 'Neutral Blue', 'wpr-addons' ),
					'blue-water' => esc_html__( 'Blue Water', 'wpr-addons' ),
					'blue-essense' => esc_html__( 'Blue Essense', 'wpr-addons' ),
					'golden-brown' => esc_html__( 'Golden Brown', 'wpr-addons' ),
					'midnight-commander' => esc_html__( 'Midnight Commander', 'wpr-addons' ),
					'shades-of-grey' => esc_html__( 'Shades of Grey', 'wpr-addons' ),
					'yellow-black' => esc_html__( 'Yellow Black', 'wpr-addons' ),
					'custom' => esc_html__( 'Custom', 'wpr-addons' ),
				],
				'default' => 'default',
				'condition' => [
					'gm_type!' => 'satellite',
				]
			]
		);

		$this->add_control(
			'gm_custom_color_scheme',
			[
				'label' => esc_html__( 'Custom Style', 'wpr-addons' ),
				'description' => esc_html__( 'Get custom map style code from <a href="https://snazzymaps.com/explore" target="_blank">Snazzy Maps</a> or <a href="https://mapstyle.withgoogle.com/" target="_blank">GM Styling Wizard</a> and copy/paste in this field.', 'wpr-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'gm_color_scheme' => 'custom',
				]
			]
		);

		$this->add_responsive_control(
			'gm_height',
			[
				'label' => esc_html__( 'Map Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 500,
				],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-google-map' => 'height: {{SIZE}}px;',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'gm_zoom_depth',
			[
				'label' => esc_html__( 'Zoom Depth', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
			]
		);

		$this->add_control(
			'gm_zoom_on_scroll',
			[
				'label' => esc_html__( 'Disable Zoom on Scroll', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'cooperative',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'gm_cluster_markers',
			[
				'label' => esc_html__( 'Cluster Markers', 'wpr-addons' ),
				'description' => esc_html__( 'Combine markers of close proximity into clusters, and simplify the display of markers on the map.', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Locations --------
		$this->start_controls_section(
			'section_google_map_locations',
			[
				'label' => esc_html__( 'Locations', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'gm_location_helper',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => '<a href="https://www.latlong.net/" target="_blank">'. esc_html__( 'Click Here', 'wpr-addons' ) .'</a> '. esc_html__( 'to find Coordinates of your location.', 'wpr-addons' ),
				'separator' => 'after'
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'gm_latitude',
			[
				'label' => esc_html__( 'Latitude', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'gm_longtitude',
			[
				'label' => esc_html__( 'Longtitude', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'gm_show_info_window',
			[
				'label' => esc_html__( 'Show Info Window', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'load' => esc_html__( 'on Load', 'wpr-addons' ),
					'click' => esc_html__( 'on Click', 'wpr-addons' ),
				],
				'default' => 'load',
				'separator' => 'before'
			]
		);

		$repeater->add_control(
			'gm_location_title',
			[
				'label' => esc_html__( 'Location Title', 'wpr-addons' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'gm_show_info_window!' => 'none',
				]
			]
		);

		$repeater->add_control(
			'gm_location_description',
			[
				'label' => esc_html__( 'Location Description', 'wpr-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'gm_show_info_window!' => 'none',
				]
			]
		);

		$repeater->add_control(
			'gm_info_window_width',
			[
				'label' => esc_html__( 'Info Window Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 300,
				],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 500,
					],
				],
				'condition' => [
					'gm_show_info_window!' => 'none',
				]
			]
		);

		$repeater->add_control(
			'gm_marker_animation',
			[
				'label' => esc_html__( 'Marker Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'DROP' => esc_html__( 'Drop', 'wpr-addons' ),
					'BOUNCE' => esc_html__( 'Bounce', 'wpr-addons' ),
				],
				'default' => 'none',
				'separator' => 'before'
			]
		);

		$repeater->add_control(
			'gm_custom_marker',
			[
				'label' => esc_html__( 'Use Custom Marker', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before'
			]
		);

		$repeater->add_control(
			'gm_marker_icon',
			[
				'label' => esc_html__( 'Upload Marker Icon', 'wpr-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'gm_custom_marker' => 'yes',
				]
			]
		);

		$repeater->add_control(
			'gm_marker_icon_size_width',
			[
				'label' => esc_html__( 'Marker Icon Size Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 35,
				],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 150,
					],
				],
				'condition' => [
					'gm_custom_marker' => 'yes',
				]
			]
		);

		$repeater->add_control(
			'gm_marker_icon_size_height',
			[
				'label' => esc_html__( 'Marker Icon Size Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 35,
				],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 150,
					],
				],
				'condition' => [
					'gm_custom_marker' => 'yes',
				]
			]
		);

		$this->add_control(
			'google_map_locations',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'gm_location_title' => 'Central Park, New York, USA',
						'gm_latitude' => '40.782864',
						'gm_longtitude' => '-73.965355',
					],
				],
				'title_field' => '{{{ gm_location_title }}}',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Controls ---------
		$this->start_controls_section(
			'section_google_map_controls',
			[
				'label' => esc_html__( 'Controls', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'gm_controls_map_type',
			[
				'label' => esc_html__( 'Show Map Type Control', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'gm_controls_fullscreen',
			[
				'label' => esc_html__( 'Show FullScreen Control', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'gm_controls_zoom',
			[
				'label' => esc_html__( 'Show Zoom Control', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'gm_controls_street_view',
			[
				'label' => esc_html__( 'Show Street View Control', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Styles ====================
		// Section: Info Window ------
		$this->start_controls_section(
			'section_style_info_window',
			[
				'label' => esc_html__( 'Info Window', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'infow_window_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'wpr-addons' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .wpr-google-map .gm-style-iw-c' => 'text-align: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'infow_window_title_color',
			[
				'label'  => esc_html__( 'Title Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-google-map .gm-style-iw-c .wpr-gm-iwindow h3' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'infow_window_description_color',
			[
				'label'  => esc_html__( 'Description Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-google-map .gm-style-iw-c .wpr-gm-iwindow p' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'infow_window_background_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-google-map .gm-style-iw-d' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-google-map .gm-style-iw-t:after' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'infow_window_title_typography',
				'label' => esc_html__( 'Title Typography', 'wpr-addons' ),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-google-map .gm-style-iw-c .wpr-gm-iwindow h3'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'infow_window_desc_typography',
				'label' => esc_html__( 'Description Typography', 'wpr-addons' ),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-google-map .gm-style-iw-c .wpr-gm-iwindow p'
			]
		);

		$this->add_responsive_control(
			'infow_window_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-google-map .gm-style-iw-c .wpr-gm-iwindow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'infow_window_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-google-map .gm-style-iw-c' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'infow_window_distance',
			[
				'label' => esc_html__( 'Distance from Marker', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-google-map .gm-style-iw-a' => 'transform: translateY(-{{SIZE}}px);',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section(); // End Controls Section

	}

	public function get_map_settings( $settings ) {
		return [
			'type' => $settings['gm_type'],
			'style' => $settings['gm_color_scheme'],
			'custom_style' => preg_replace( '/\s/', '', strip_tags($settings['gm_custom_color_scheme']) ),
			'zoom_depth' => $settings['gm_zoom_depth']['size'],
			'zoom_on_scroll' => $settings['gm_zoom_on_scroll'],
			'cluster_markers' => $settings['gm_cluster_markers'],
			'clusters_url' => WPR_ADDONS_URL . 'assets/js/lib/gmap/clusters/m',
		];
	}

	public function get_map_controls( $settings ) {
		return [
			'type' => $settings['gm_controls_map_type'],
			'fullscreen' => $settings['gm_controls_fullscreen'],
			'zoom' => $settings['gm_controls_zoom'],
			'streetview' => $settings['gm_controls_street_view'],
		];
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		$attributes  = ' data-settings="'. esc_attr( json_encode($this->get_map_settings( $settings )) ) .'"';
		$attributes .= ' data-locations="'. esc_attr( json_encode($settings['google_map_locations']) ) .'"';
		$attributes .= ' data-controls="'. esc_attr( json_encode($this->get_map_controls( $settings )) ) .'"';

		echo '<div class="wpr-google-map" '. $attributes .'></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( current_user_can('manage_options') && '' == get_option('wpr_google_map_api_key') ) {
			echo '<p class="wpr-api-key-missing">Please go to plugin <a href='. admin_url( 'admin.php?page=wpr-addons&tab=wpr_tab_settings' ) .' target="_blank">Settings</a> and Insert Google Map API Key in order to make Google Maps work</p>';
		}

	}
	
}