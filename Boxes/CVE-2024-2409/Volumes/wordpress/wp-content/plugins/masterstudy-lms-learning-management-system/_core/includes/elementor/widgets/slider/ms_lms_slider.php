<?php

namespace StmLmsElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class MsLmsSlider extends Widget_Base {

	use \MsLmsAddControls;

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'ms_lms_slider_vendor', STM_LMS_URL . 'assets/vendors/swiper-bundle.min.css', array(), STM_LMS_VERSION, false );
		wp_register_style( 'ms_lms_slider', STM_LMS_URL . 'assets/css/elementor-widgets/slider/slider.css', array(), STM_LMS_VERSION, false );
	}

	public function get_name() {
		return 'ms_lms_slider';
	}

	public function get_title() {
		return esc_html__( 'MS Slider', 'masterstudy-lms-learning-management-system' );
	}

	public function get_style_depends() {
		return array( 'ms_lms_slider', 'ms_lms_slider_vendor' );
	}

	public function get_icon() {
		return 'stmlms-ms-slider lms-icon';
	}

	public function get_categories() {
		return array( 'stm_lms' );
	}

	protected function register_controls() {
		require STM_LMS_ELEMENTOR_WIDGETS . '/slider/content/type.php';
		require STM_LMS_ELEMENTOR_WIDGETS . '/slider/content/slide.php';
		require STM_LMS_ELEMENTOR_WIDGETS . '/slider/content/options.php';
	}

	protected function get_widget_data( $type ) {
		if ( ! empty( $type ) ) {
			$widgets_data = array(
				'slider-custom' => $this->slider_custom_data(),
			);
			return $widgets_data[ $type ];
		}
	}

	protected function slider_custom_data() {
		$settings = $this->get_settings_for_display();
		$atts     = array(
			'slides' => $settings['slides'],
		);

		return $atts;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( ! Plugin::$instance->editor->is_edit_mode() ) {
			wp_enqueue_script( 'ms_lms_slider_vendor', STM_LMS_URL . 'assets/vendors/swiper-bundle.min.js', array(), STM_LMS_VERSION, true );
			wp_enqueue_script( 'ms_lms_slider', STM_LMS_URL . 'assets/js/elementor-widgets/slider/slider.js', array(), STM_LMS_VERSION, true );
		}

		/* options for templates */
		$atts = array(
			'show_navigation'     => $settings['show_navigation'],
			'navigation_presets'  => $settings['navigation_presets'],
			'navigation_position' => $settings['navigation_position'],
		);

		$widget_atts = $this->get_widget_data( $settings['type'] );
		$atts        = wp_parse_args( $widget_atts, $atts );

		\STM_LMS_Templates::show_lms_template( "elementor-widgets/slider/{$settings['type']}/main", $atts );
	}

	protected function content_template() {
	}
}
