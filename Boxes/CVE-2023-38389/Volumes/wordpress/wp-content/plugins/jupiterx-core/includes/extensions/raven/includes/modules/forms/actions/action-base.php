<?php
/**
 * Add Action Base.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Actions;

defined( 'ABSPATH' ) || die();

use Elementor\Settings;

/**
 * Action Base.
 *
 * An abstract class to register new form action.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class Action_Base {

	/**
	 * Action base constructor.
	 *
	 * Initializing the action base class by hooking in widgets controls.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'elementor/element/raven-form/section_settings/after_section_end', [ $this, 'update_controls' ] );
		add_action( 'elementor/element/raven-register/section_settings/after_section_end', [ $this, 'update_controls' ] );

		if ( is_admin() ) {
			add_action( 'elementor/admin/after_create_settings/' . Settings::PAGE_ID, [ $this, 'register_admin_fields' ], 20 );
		}
	}

	/**
	 * Get name.
	 *
	 * Get name of this action.
	 *
	 * @since 1.19.0
	 * @access public
	 * @abstract
	 */
	abstract public function get_name();

	/**
	 * Get title.
	 *
	 * Get title of this action.
	 *
	 * @since 1.19.0
	 * @access public
	 * @abstract
	 */
	abstract public function get_title();

	/**
	 * Is private.
	 *
	 * Determine if this action is private.
	 *
	 * @since 2.0.0
	 * @access public
	 * @abstract
	 */
	public function is_private() {
		return false;
	}

	/**
	 * Update controls.
	 *
	 * Add, remove and sort the controls in the widget.
	 *
	 * @since 1.0.0
	 * @access public
	 * @abstract
	 *
	 * @param object $widget Ajax handler instance.
	 *
	 * @return void
	 */
	abstract public function update_controls( $widget );

	/**
	 * Run action.
	 *
	 * Run the main functionality of the action.
	 *
	 * @since 1.0.0
	 * @access public
	 * @abstract
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function run( $ajax_handler ) {}

	/**
	 * Register admin fields.
	 *
	 * Register required admin settings for the field.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param object $settings Settings.
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function register_admin_fields( $settings ) {}

	/**
	 * Replace shortecodes settings.
	 *
	 * Replace shortcodes with the correct content.
	 *
	 * @since 3.3.0
	 * @access public
	 *
	 * @param string $setting Shortcode string.
	 * @param string $record_fields Fields that user filled in the form.
	 * @param string $form_settings_fields Form elementor settings.
	 * @param string $content_type Content type.
	 * @param string $line_break Line break.
	 * @param string $content_field Content field determines that if the shortcode is for the email body or another fields.
	 *
	 * @return string
	 */
	public static function replace_setting_shortcodes( $setting, $record_fields, $form_settings_fields, $content_type, $line_break, $content_field = true ) {
		// Shortcode can be `[field id="fds21fd"]` or `[field title="Email" id="fds21fd"]`, multiple shortcodes are allowed
		return preg_replace_callback( '/(\[field[^]]*id="(\w+)"[^]]*\])/', function( $matches ) use ( $record_fields, $form_settings_fields, $content_type, $line_break, $content_field ) {
			$value    = '';
			$field_id = '';
			$body     = '';

			foreach ( $form_settings_fields as $key => $default ) {
				if ( $default['field_custom_id'] === $matches[2] ) {
					$field_id = $default['_id'];

					break;
				}
			}

			if ( isset( $record_fields[ $field_id ] ) ) {
				$value = $record_fields[ $field_id ];
			}

			if ( ! $content_field ) {
				return $value;
			}

			foreach ( $form_settings_fields as $field ) {
				$title   = $field['label'];
				$content = $record_fields[ $field['_id'] ];

				if ( 'textarea' === $field['type'] && 'html' === $content_type ) {
					$value = str_replace( [ "\r\n", "\n", "\r" ], '<br>', $value );
				}

				if ( $content === $value ) {
					$body = $title . ': ' . $value . $line_break;
				}
			}

			return $body;
		}, $setting );
	}
}
