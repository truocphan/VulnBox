<?php
/**
 * This class handles API for customizer.
 *
 * @package JupiterX\Framework\API\Customizer
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'JupiterX_Customizer' ) ) {
	/**
	 * Customizer wrapper class.
	 *
	 * @since 1.0.0
	 * @ignore
	 * @access private
	 *
	 * @package JupiterX\Framework\API\Customizer
	 */
	final class JupiterX_Customizer {

		/**
		 * Registered panels.
		 *
		 * @since 1.0.0
		 *
		 * @var array
		 */
		public static $panels = [];

		/**
		 * Registered sections.
		 *
		 * @since 1.0.0
		 *
		 * @var array
		 */
		public static $sections = [];

		/**
		 * Registered settings.
		 *
		 * @since 1.0.0
		 *
		 * @var array
		 */
		public static $settings = [];

		/**
		 * Configuration ID.
		 *
		 * Defined for Kirki.
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		public static $config_id = 'jupiterx';

		/**
		 * Section types.
		 *
		 * @since 1.0.0
		 *
		 * @var array
		 */
		public static $section_types = [
			'kirki-popup'         => 'JupiterX_Customizer_Section_Popup',
			'kirki-pane'          => 'JupiterX_Customizer_Section_Pane',
			'kirki-jupiterx-link' => 'JupiterX_Customizer_Section_Link',
			'kirki-container'     => 'JupiterX_Customizer_Section_container',
		];

		/**
		 * Control types.
		 *
		 * @since 1.0.0
		 *
		 * @var array
		 */
		public static $control_types = [
			'jupiterx-input'       => 'JupiterX_Customizer_Control_Input',
			'jupiterx-text'        => 'JupiterX_Customizer_Control_Text',
			'jupiterx-textarea'    => 'JupiterX_Customizer_Control_Textarea',
			'jupiterx-select'      => 'JupiterX_Customizer_Control_Select',
			'jupiterx-toggle'      => 'JupiterX_Customizer_Control_Toggle',
			'jupiterx-choose'      => 'JupiterX_Customizer_Control_Choose',
			'jupiterx-multicheck'  => 'JupiterX_Customizer_Control_Multicheck',
			'jupiterx-divider'     => 'JupiterX_Customizer_Control_Divider',
			'jupiterx-label'       => 'JupiterX_Customizer_Control_Label',
			'jupiterx-alert'       => 'JupiterX_Customizer_Control_Alert',
			'jupiterx-color'       => 'JupiterX_Customizer_Control_Color',
			'jupiterx-image'       => 'JupiterX_Customizer_Control_Image',
			'jupiterx-radio-image' => 'JupiterX_Customizer_Control_Radio_Image',
			'jupiterx-child-popup' => 'JupiterX_Customizer_Control_Child_Popup',
			'jupiterx-popup'       => 'JupiterX_Customizer_Control_Popup',
			'jupiterx-box-model'   => 'JupiterX_Customizer_Control_Box_Model',
			'jupiterx-font'        => 'JupiterX_Customizer_Control_Font',
			'jupiterx-exceptions'  => 'JupiterX_Customizer_Control_Exceptions',
			'jupiterx-template'    => 'JupiterX_Customizer_Control_Template',
			'jupiterx-pro-box'     => 'JupiterX_Customizer_Control_PRO_Box',
		];

		/**
		 * Group control types.
		 *
		 * @since 1.0.0
		 *
		 * @var array
		 */
		public static $group_control_types = [
			'jupiterx-background' => 'JupiterX_Customizer_Group_Control_Background',
			'jupiterx-box-shadow' => 'JupiterX_Customizer_Group_Control_Box_Shadow',
			'jupiterx-typography' => 'JupiterX_Customizer_Group_Control_Typography',
			'jupiterx-border'     => 'JupiterX_Customizer_Group_Control_Border',
		];

		/**
		 * Responsive devices media query.
		 *
		 * @since 1.0.0
		 *
		 * @var array
		 */
		public static $responsive_devices = [
			'desktop' => 'global',
			'tablet'  => '@media (max-width: 767.98px)',
			'mobile'  => '@media (max-width: 575.98px)',
		];

		/**
		 * Store panel.
		 *
		 * @since 1.0.0
		 *
		 * @param string $id ID of the panel.
		 * @param array  $args Arguments of the panel.
		 */
		public static function add_panel( $id = '', $args = [] ) {
			if ( empty( $id ) ) {
				return;
			}

			/**
			 * Run action before section added.
			 *
			 * @since 1.3.0
			 */
			do_action( "{$id}_before_panel" );

			// Add panel to stack.
			self::$panels[ $id ] = $args;

			/**
			 * Run action after panel added.
			 *
			 * @since 1.3.0
			 */
			do_action( "{$id}_after_panel" );
		}

		/**
		 * Store section.
		 *
		 * @since 1.0.0
		 *
		 * @param string $id ID of the section.
		 * @param array  $args Arguments of the section.
		 */
		public static function add_section( $id = '', $args = [] ) {
			if ( empty( $id ) ) {
				return;
			}

			/**
			 * Run action before section added.
			 *
			 * @since 1.3.0
			 */
			do_action( "{$id}_before_section" );

			/**
			 * Add section to stack.
			 */
			self::$sections[ $id ] = array_merge( [ 'priority' => 160 ], $args );

			/**
			 * Run action after section added.
			 *
			 * @since 1.3.0
			 */
			do_action( "{$id}_after_section", $id );
		}

		/**
		 * Update section.
		 *
		 * @since 1.3.0
		 *
		 * @param string $id Section ID.
		 * @param array  $args Section arguments.
		 */
		public static function update_section( $id, $args = [] ) {
			if ( ! isset( self::$sections[ $id ] ) ) {
				return;
			}

			$section = self::$sections[ $id ];

			self::$sections[ $id ] = array_merge( $section, $args );
		}

		/**
		 * Store settings.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args Arguments of the field.
		 */
		public static function add_field( $args = [] ) {
			if ( ! isset( $args['type'] ) && ! isset( $args['settings'] ) ) {
				return;
			}

			/**
			 * Run action before field added.
			 *
			 * @since 1.3.0
			 */
			do_action( "{$args['settings']}_before_field" );

			/**
			 * Add the field to stack.
			 */
			self::$settings[ $args['settings'] ] = $args;

			/**
			 * Run action after field added.
			 *
			 * @since 1.3.0
			 */
			do_action( "{$args['settings']}_after_field" );
		}

		/**
		 * Add responsive field.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args Arguments of the field.
		 */
		public static function add_responsive_field( $args = [] ) {
			$args['responsive'] = true;

			self::add_field( $args );
		}

		/**
		 * Update field.
		 *
		 * @since 1.3.0
		 *
		 * @param string $id Field ID.
		 * @param array  $args Field arguments.
		 */
		public static function update_field( $id, $args = [] ) {
			if ( ! isset( self::$settings[ $id ] ) ) {
				return;
			}

			$settings = self::$settings[ $id ];

			self::$settings[ $id ] = array_merge( $settings, $args );
		}

		/**
		 * Remove field.
		 *
		 * @since 1.3.0
		 *
		 * @param string $id ID of the field.
		 */
		public static function remove_field( $id ) {
			if ( isset( self::$settings[ $id ] ) ) {
				unset( self::$settings[ $id ] );
			}
		}

		/**
		 * Get all fields.
		 *
		 * @since 1.19.0
		 */
		public static function get_fields() {
			return self::$settings;
		}
	}
}
