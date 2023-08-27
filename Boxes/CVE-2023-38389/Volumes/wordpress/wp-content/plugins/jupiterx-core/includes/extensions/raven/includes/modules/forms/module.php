<?php
namespace JupiterX_Core\Raven\Modules\Forms;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;
use JupiterX_Core\Raven\Modules\Forms\Fields;
use JupiterX_Core\Raven\Modules\Forms\Actions;
use JupiterX_Core\Raven\Modules\Forms\Classes\Ajax_Handler;
use JupiterX_Core\Raven\Utils;

class Module extends Module_Base {

	public static $field_types = [];

	public static $action_types = [];

	public static $messages = [];

	public function __construct() {
		parent::__construct();

		$this->register_field_types();

		$this->register_action_types();

		$this->set_messages();

		// Download hooks.
		add_action( 'admin_post_raven_download_file', [ Utils::class, 'handle_file_download' ] );
		add_action( 'admin_post_nopriv_raven_download_file', [ Utils::class, 'handle_file_download' ] );

		new Ajax_Handler();
	}

	public function get_widgets() {
		return [ 'form', 'reset-password', 'login', 'register', 'social-login' ];
	}

	public static function get_field_types( $widget = 'form' ) {
		$types = [
			'text' => [
				'label'           => __( 'Text', 'jupiterx-core' ),
				'exclude_widgets' => [ 'reset-password' ],
			],
			'email' => [
				'label'           => __( 'Email', 'jupiterx-core' ),
				'exclude_widgets' => [],
			],
			'select' => [
				'label'           => __( 'Select', 'jupiterx-core' ),
				'exclude_widgets' => [ 'reset-password' ],
			],
			'textarea' => [
				'label'           => __( 'Textarea', 'jupiterx-core' ),
				'exclude_widgets' => [ 'reset-password' ],
			],
			'tel' => [
				'label'           => __( 'Tel', 'jupiterx-core' ),
				'exclude_widgets' => [ 'reset-password' ],
			],
			'number' => [
				'label'           => __( 'Number', 'jupiterx-core' ),
				'exclude_widgets' => [ 'reset-password' ],
			],
			'date' => [
				'label'           => __( 'Date', 'jupiterx-core' ),
				'exclude_widgets' => [ 'reset-password' ],
			],
			'time' => [
				'label'           => __( 'Time', 'jupiterx-core' ),
				'exclude_widgets' => [ 'reset-password' ],
			],
			'checkbox' => [
				'label'           => __( 'Checkbox', 'jupiterx-core' ),
				'exclude_widgets' => [ 'reset-password' ],
			],
			'radio' => [
				'label'           => __( 'Radio', 'jupiterx-core' ),
				'exclude_widgets' => [ 'reset-password' ],
			],
			'acceptance' => [
				'label'           => __( 'Acceptance', 'jupiterx-core' ),
				'exclude_widgets' => [ 'reset-password' ],
			],
			'recaptcha' => [
				'label'           => __( 'reCAPTCHA', 'jupiterx-core' ),
				'exclude_widgets' => [],
			],
			'recaptcha_v3' => [
				'label'           => __( 'reCAPTCHA v3', 'jupiterx-core' ),
				'exclude_widgets' => [],
			],
			'address' => [
				'label'           => __( 'Address', 'jupiterx-core' ),
				'exclude_widgets' => [ 'reset-password' ],
			],
			'file' => [
				'label'           => __( 'File Upload', 'jupiterx-core' ),
				'exclude_widgets' => [ 'reset-password' ],
			],
			'hidden' => [
				'label'           => __( 'Hidden', 'jupiterx-core' ),
				'exclude_widgets' => [ 'reset-password' ],
			],
			'password' => [
				'label'           => __( 'Password', 'jupiterx-core' ),
				'exclude_widgets' => [ 'reset-password' ],
			],
		];

		foreach ( $types as $key => $value ) {
			if ( in_array( $widget, $value['exclude_widgets'], true ) ) {
				unset( $types[ $key ] );
				continue;
			}

			$types[ $key ] = $value['label'];
		}

		return $types;
	}

	/**
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	private function register_field_types() {
		foreach ( self::get_field_types() as $field_key => $field_value ) {
			$class_name = __NAMESPACE__ . '\Fields\\' . ucfirst( $field_key );

			self::$field_types[ $field_key ] = new $class_name();
		}
	}

	public static function get_action_types() {
		$action_types = [];

		foreach ( self::$action_types as $action ) {
			if ( $action->is_private() ) {
				continue;
			}

			$action_types[ $action->get_name() ] = $action->get_title();
		}

		return $action_types;
	}

	private function register_action_types() {
		// Load CRM trait.
		require_once __DIR__ . '/actions/crm-trait.php';

		$default_actions = [
			'email',
			'email2',
			'activecampaign',
			'convertkit',
			'drip',
			'getresponse',
			'hubspot',
			'mailchimp',
			'mailerlite',
			'discord',
			'slack',
			'download',
			'redirect',
			'webhook',
			'reset_password',
			'login',
			'register',
			'social_login',
		];

		foreach ( $default_actions as $action ) {
			$class_name = __NAMESPACE__ . '\Actions\\' . ucfirst( $action );

			self::$action_types[ $action ] = new $class_name();
		}
	}

	public static function register_custom_action( $action ) {
		self::$action_types[ $action->get_name() ] = $action;
	}

	public function set_messages() {
		self::$messages = [
			'success' => __( 'The form was sent successfully!', 'jupiterx-core' ),
			'error' => __( 'Please check the errors.', 'jupiterx-core' ),
			'required' => __( 'Required', 'jupiterx-core' ),
			'subscriber' => __( 'Subscriber already exists.', 'jupiterx-core' ),
		];
	}

	public static function render_field( $widget, $field ) {
		self::$field_types[ $field['type'] ]->render( $widget, $field );
	}

	public static function find_element_recursive( $elements, $form_id ) {
		foreach ( $elements as $element ) {
			if ( $form_id === $element['id'] ) {
				return $element;
			}

			if ( ! empty( $element['elements'] ) ) {
				$element = self::find_element_recursive( $element['elements'], $form_id );

				if ( $element ) {
					return $element;
				}
			}
		}

		return false;
	}

	public function translations() {
		return [
			'validation' => [
				'required' => __( 'Please fill in this field', 'jupiterx-core' ),
				'invalidEmail' => __( 'The value is not a valid email address', 'jupiterx-core' ),
				'invalidPhone' => __( 'The value should only consist numbers and phone characters (-, +, (), etc)', 'jupiterx-core' ),
				'invalidNumber' => __( 'The value is not a valid number', 'jupiterx-core' ),
				'invalidMaxValue' => __( 'Value must be less than or equal to MAX_VALUE', 'jupiterx-core' ),
				'invalidMinValue' => __( 'Value must be greater than or equal to MIN_VALUE', 'jupiterx-core' ),
			],
			// Validation messages specific to Intelligent Tel Input plugin.
			'itiValidation' => [
				'invalidCountryCode' => esc_html__( 'Invalid country code', 'jupiterx-core' ),
				'tooShort'           => esc_html__( 'Phone number is too short', 'jupiterx-core' ),
				'tooLong'            => esc_html__( 'Phone number is too long', 'jupiterx-core' ),
				'areaCodeMissing'    => esc_html__( 'Area code is required.', 'jupiterx-core' ),
				'invalidLength'      => esc_html__( 'Phone number has an invalid length', 'jupiterx-core' ),
				'invalidGeneral'     => esc_html__( 'Invalid phone number', 'jupiterx-core' ),
				'forceMinLength'     => esc_html__( 'Phone number must be more than X digits', 'jupiterx-core' ),
				'typeMismatch'       => [
					'0'  => esc_html__( 'Phone number must be of type: ', 'jupiterx-core' ) . esc_html__( 'Fixed Line', 'jupiterx-core' ),
					'1'  => esc_html__( 'Phone number must be of type: ', 'jupiterx-core' ) . esc_html__( 'Mobile', 'jupiterx-core' ),
					'2'  => esc_html__( 'Phone number must be of type: ', 'jupiterx-core' ) . esc_html__( 'Fixed Line or Mobile', 'jupiterx-core' ),
					'3'  => esc_html__( 'Phone number must be of type: ', 'jupiterx-core' ) . esc_html__( 'Toll Free', 'jupiterx-core' ),
					'4'  => esc_html__( 'Phone number must be of type: ', 'jupiterx-core' ) . esc_html__( 'Premium Rate', 'jupiterx-core' ),
					'5'  => esc_html__( 'Phone number must be of type: ', 'jupiterx-core' ) . esc_html__( 'Shared Cost', 'jupiterx-core' ),
					'6'  => esc_html__( 'Phone number must be of type: ', 'jupiterx-core' ) . esc_html__( 'VOIP', 'jupiterx-core' ),
					'7'  => esc_html__( 'Phone number must be of type: ', 'jupiterx-core' ) . esc_html__( 'Personal Number', 'jupiterx-core' ),
					'8'  => esc_html__( 'Phone number must be of type: ', 'jupiterx-core' ) . esc_html__( 'Pager', 'jupiterx-core' ),
					'9'  => esc_html__( 'Phone number must be of type: ', 'jupiterx-core' ) . esc_html__( 'UAN', 'jupiterx-core' ),
					'10' => esc_html__( 'Phone number must be of type: ', 'jupiterx-core' ) . esc_html__( 'Voicemail', 'jupiterx-core' ),
				],
			],
		];
	}
}
