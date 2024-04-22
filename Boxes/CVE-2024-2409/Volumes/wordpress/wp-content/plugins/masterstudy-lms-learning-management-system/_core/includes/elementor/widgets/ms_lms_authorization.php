<?php
namespace StmLmsElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class MsLmsAuthorization extends Widget_Base {

	public function get_name() {
		return 'ms_lms_authorization';
	}

	public function get_title() {
		return esc_html__( 'Authorization Form', 'masterstudy-lms-learning-management-system' );
	}

	public function get_icon() {
		return 'stmlms-authorization lms-icon';
	}

	public function get_style_depends() {
		return array(
			'masterstudy-authorization',
			'masterstudy-button',
			'masterstudy-form-builder-fields',
			'masterstudy-file-attachment',
			'masterstudy-hint',
			'masterstudy-alert',
		);
	}

	public function get_categories() {
		return array( 'stm_lms' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'presets_section',
			array(
				'label' => esc_html__( 'Presets', 'masterstudy-lms-learning-management-system' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'widget_presets',
			array(
				'label'   => esc_html__( 'Form type', 'masterstudy-lms-learning-management-system' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'general',
				'options' => array(
					'general'         => esc_html__( 'General', 'masterstudy-lms-learning-management-system' ),
					'only_instructor' => esc_html__( 'For Instructors Only', 'masterstudy-lms-learning-management-system' ),
				),
			)
		);
		$this->add_control(
			'widget_start_form',
			array(
				'label'     => esc_html__( 'Starting form', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'login',
				'options'   => array(
					'login'    => esc_html__( 'Login', 'masterstudy-lms-learning-management-system' ),
					'register' => esc_html__( 'Sign up', 'masterstudy-lms-learning-management-system' ),
				),
				'condition' => array(
					'widget_presets' => 'general',
				),
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$atts     = array(
			'modal'               => false,
			'type'                => isset( $settings['widget_start_form'] ) ? $settings['widget_start_form'] : 'register',
			'is_instructor'       => \STM_LMS_Instructor::is_instructor(),
			'only_for_instructor' => 'general' === $settings['widget_presets'] ? false : true,
			'dark_mode'           => false,
		);

		if ( Plugin::$instance->editor->is_edit_mode() ) {
			$atts['is_instructor']    = false;
			$atts['elementor_editor'] = true;
		}

		\STM_LMS_Templates::show_lms_template( 'components/authorization/main', $atts );
	}
}
