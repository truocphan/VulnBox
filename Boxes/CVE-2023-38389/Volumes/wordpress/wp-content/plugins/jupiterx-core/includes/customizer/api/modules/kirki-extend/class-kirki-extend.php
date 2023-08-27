<?php
/**
 * This class handles extending Kirki plugin framework.
 *
 * @package JupiterX\Framework\API\Customizer
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define all customizer utils.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
final class JupiterX_Customizer_Kirki_Extend {

	/**
	 * Control outputs.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $control_outputs = [
		'jupiterx-background' => 'JupiterX_Customizer_Kirki_Extend_Output_Background',
		'jupiterx-box-model'  => 'JupiterX_Customizer_Kirki_Extend_Output_Box_Model',
		'jupiterx-box-shadow' => 'JupiterX_Customizer_Kirki_Extend_Output_Box_Shadow',
		'jupiterx-typography' => 'JupiterX_Customizer_Kirki_Extend_Output_Typography',
		'jupiterx-border'     => 'JupiterX_Customizer_Kirki_Extend_Output_Border',
		'jupiterx-input'      => 'JupiterX_Customizer_Kirki_Extend_Output_Input',
	];

	/**
	 * Module activate condition.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean Class active state.
	 */
	public static function active() {
		return class_exists( 'Kirki' );
	}

	/**
	 * Construct the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'kirki_section_types', [ $this, 'add_section_types' ] );
		add_filter( 'kirki_control_types', [ $this, 'add_control_types' ] );
		add_filter( 'kirki_jupiterx_output_control_classnames', [ $this, 'add_control_outputs' ] );
		add_filter( 'kirki_control_types_exclude', [ __CLASS__, 'exclude_controls' ] );
		add_action( 'init', [ $this, 'register_settings' ], 15 );
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'dequeue_scripts' ], 15 );
	}

	/**
	 * Exclude controls.
	 *
	 * @since 1.0.4
	 *
	 * @return array
	 */
	public static function exclude_controls() {
		return [
			'Kirki_Control_Checkbox',
			'Kirki_Control_Background',
			'Kirki_Control_Code',
			'Kirki_Control_Color',
			'Kirki_Control_Color_Palette',
			'Kirki_Control_Date',
			'Kirki_Control_Dashicons',
			'Kirki_Control_Dimension',
			'Kirki_Control_Dimensions',
			'Kirki_Control_Editor',
			'Kirki_Control_FontAwesome',
			'Kirki_Control_Image',
			'Kirki_Control_Multicolor',
			'Kirki_Control_MultiCheck',
			'Kirki_Control_Number',
			'Kirki_Control_Palette',
			'Kirki_Control_Radio',
			'Kirki_Control_Radio_ButtonSet',
			'Kirki_Control_Radio_Image',
			'Kirki_Control_Repeater',
			'Kirki_Control_Select',
			'Kirki_Control_Slider',
			'Kirki_Control_Sortable',
			'Kirki_Control_Dimensions',
			'Kirki_Control_Switch',
			'Kirki_Control_Generic',
			'Kirki_Control_Toggle',
			'Kirki_Control_Typography',
			'Kirki_Control_Image',
			'Kirki_Control_Cropped_Image',
			'Kirki_Control_Upload',
		];
	}

	/**
	 * Add custom section types to Kirki.
	 *
	 * @since 1.0.0
	 *
	 * @param array $section_types Defined section types from Kirki.
	 *
	 * @return array
	 */
	public function add_section_types( $section_types = [] ) {
		return array_merge( $section_types, JupiterX_Customizer::$section_types );
	}

	/**
	 * Add custom control types to Kirki.
	 *
	 * @since 1.0.0
	 *
	 * @param array $control_types Defined control types from Kirki.
	 *
	 * @return array
	 */
	public function add_control_types( $control_types = [] ) {
		return array_merge( $control_types, JupiterX_Customizer::$control_types, JupiterX_Customizer::$group_control_types );
	}

	/**
	 * Add custom control outputs to Kirki.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function add_control_outputs() {
		$outputs = $this->control_outputs;

		$controls = array_merge( JupiterX_Customizer::$control_types, JupiterX_Customizer::$group_control_types );

		foreach ( $controls as $control => $class ) {
			if ( ! key_exists( $control, $outputs ) ) {
				$outputs[ $control ] = 'JupiterX_Customizer_Kirki_Extend_Base_Output';
			}
		}

		return $outputs;
	}

	/**
	 * Register settings to Kirki.
	 *
	 * @since 1.0.0
	 */
	public function register_settings() {
		/**
		 * Add config.
		 *
		 * @link https://aristath.github.io/kirki/docs/getting-started/config.html
		 *
		 * @since 1.0.0
		 */
		Kirki::add_config(
			JupiterX_Customizer::$config_id, [
				'capability'  => 'edit_theme_options',
				'option_type' => 'theme_mod',
			]
		);

		// Add panels.
		foreach ( JupiterX_Customizer::$panels as $id => $panel ) {
			Kirki::add_panel( $id, $panel );
		}

		// Add sections.
		foreach ( JupiterX_Customizer::$sections as $id => $section ) {
			Kirki::add_section( $id, $section );
		}

		// Add settings.
		foreach ( JupiterX_Customizer::$settings as $id => $setting ) {
			Kirki::add_field( JupiterX_Customizer::$config_id, $setting );
		}
	}

	/**
	 * Dequeue scripts.
	 *
	 * @since 1.0.4
	 */
	public function dequeue_scripts() {
		wp_dequeue_style( 'kirki-selectWoo' );
	}
}
