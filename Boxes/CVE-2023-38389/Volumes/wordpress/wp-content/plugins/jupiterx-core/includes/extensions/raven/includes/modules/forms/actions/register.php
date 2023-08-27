<?php
/**
 * Add form register action.
 *
 * @package JupiterX_Core\Raven
 * @since 2.0.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Actions;

defined( 'ABSPATH' ) || die();

/**
 * Register Action.
 *
 * Initializing the register action by extending action base.
 *
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @since 2.0.0
 */
class Register extends Action_Base {
	/**
	 * Get name.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function get_name() {
		return 'register';
	}

	/**
	 * Get title.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function get_title() {
		return __( 'Register', 'jupiterx-core' );
	}

	/**
	 * Is private.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function is_private() {
		return true;
	}

	/**
	 * Update controls.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param object $widget Widget instance.
	 */
	public function update_controls( $widget ) {}

	/**
	 * Check if user subscribed( checkbox ) for third party subscriptions.
	 *
	 * @param array $actions
	 * @param object $object
	 * @return void
	 * @since 2.0.0
	 */
	public static function exclude_third_party_subscription( $actions, $object ) {
		if ( ! array_key_exists( 'register_acceptance', $object->record['fields'] ) ) {
			$unset_actions = [ 'slack', 'mailchimp', 'hubspot', 'webhook' ];
			$actions       = array_diff( $actions, $unset_actions );
		}

		return $actions;
	}

	/**
	 * Run action.
	 *
	 * Register.
	 *
	 * @since 2.0.0
	 * @access public
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 */
	public static function run( $ajax_handler ) {
		$settings = $ajax_handler->form['settings'];
		$fields   = $settings['fields'];
		$form     = $ajax_handler->record;

		self::check_user_password_and_email( $form, $fields, $ajax_handler );
		self::create_user( $fields, $form, $ajax_handler );
	}

	/**
	 * Checks if password field exists.
	 * Checks if emails fields exists.
	 * Checks if password field and confirm password are same.
	 *
	 * @param array $form records
	 * @param array widget $fields
	 * @param object $ajax_handler
	 * @return void
	 * @since 2.0.0
	 */
	private static function check_user_password_and_email( $form, $fields, $ajax_handler ) {
		foreach ( $fields as $field ) {
			if ( 'user_password' === $field['map_to'] ) {
				$password_id = $field['_id'];
			}
			if ( 'user_email' === $field['map_to'] ) {
				$email_id = $field['_id'];
			}
		}

		if ( empty( $password_id ) ) {
			return $ajax_handler->set_success( false )
				->add_response( 'message', esc_html__( 'Password field was not found.', 'jupiterx-core' ) )
				->send_response();
		}

		if ( empty( $email_id ) ) {
			return $ajax_handler->set_success( false )
				->add_response( 'message', esc_html__( 'Email field was not found.', 'jupiterx-core' ) )
				->send_response();
		}

		if ( ! array_key_exists( 'confirm-password', $form ) ) {
			return;
		}

		$password         = $form['fields'][ $password_id ];
		$confirm_password = $form['confirm-password'];
		$settings         = $ajax_handler->form['settings'];

		$error = esc_html__( 'Your password and confirmation password do not match.', 'jupiterx-core' );

		if ( 'yes' === $settings['enable_custom_messages'] && ! empty( $settings['custom_message_error_not_same_password'] ) ) {
			$error = $settings['custom_message_error_not_same_password'];
		}

		if ( strcmp( $password, $confirm_password ) !== 0 ) {
			return $ajax_handler->set_success( false )
				->add_response( 'message', $error )
				->send_response();
		}
	}

	/**
	 * Insert user and meta data.
	 *
	 * @param array widget $fields
	 * @param array form records $form
	 * @param object $ajax_handler
	 * @return void
	 * @since 2.0.0
	 */
	private static function create_user( $fields, $form, $ajax_handler ) {
		$first_name = '';
		$last_name  = '';
		$full_name  = '';
		$phone      = '';
		$settings   = $ajax_handler->form['settings'];

		foreach ( $fields as $field ) {
			if ( 'first_name' === $field['map_to'] ) {
				$first_name = $form['fields'][ $field['_id'] ];
			}

			if ( 'last_name' === $field['map_to'] ) {
				$last_name = $form['fields'][ $field['_id'] ];
			}

			if ( 'full_name' === $field['map_to'] ) {
				$full_name = $form['fields'][ $field['_id'] ];
			}

			if ( 'phone' === $field['map_to'] ) {
				$phone = $form['fields'][ $field['_id'] ];
			}

			if ( 'user_email' === $field['map_to'] ) {
				$email = $form['fields'][ $field['_id'] ];
			}

			if ( 'user_password' === $field['map_to'] ) {
				$password = $form['fields'][ $field['_id'] ];
			}

			if ( 'newsletter' === $field['map_to'] ) {
				$newsletter = $form['fields']['register_acceptance'];
			}
		}

		// Here to customize error message we check if email already exists.
		$error_email_msg = __( 'Email already exists.', 'jupiterx-core' );
		if ( 'yes' === $settings['enable_custom_messages'] && ! empty( $settings['custom_message_email_exist'] ) ) {
			$error_email_msg = $settings['custom_message_email_exist'];
		}

		$check_email = email_exists( $email );
		if ( false !== $check_email && 'yes' === $settings['enable_custom_messages'] ) {
			return $ajax_handler
				->set_success( false )
				->add_response( 'message', $error_email_msg )
				->send_response();
		}

		if ( ! empty( $full_name ) && empty( $first_name ) ) {
			$splitted   = explode( ' ', $full_name );
			$first_name = $splitted[0];
		}

		if ( ! empty( $full_name ) && empty( $last_name ) ) {
			$splitted  = explode( ' ', $full_name );
			$last_name = $splitted[1];
		}

		$user_data = [
			'user_pass'    => $password,
			'user_login'   => $email,
			'user_email'   => $email,
			'first_name'   => $first_name,
			'last_name'    => $last_name,
			'display_name' => $full_name,
			'nickname'     => $full_name,
		];

		$user_id = wp_insert_user( $user_data );

		if ( is_wp_error( $user_id ) ) {
			if ( $user_id->errors['existing_user_login'] ) {
				$user_id->errors['existing_user_login'][0] = esc_html__( 'An account is already registered with this email address. Please sign in to access your existing account.', 'jupiterx-core' );
			}

			return $ajax_handler
				->set_success( false )
				->add_response( 'message', $user_id->get_error_message() )
				->send_response();
		}

		if ( ! empty( $phone ) ) {
			update_user_meta( $user_id, 'billing_phone', $phone );
		}

		if ( 'on' === $newsletter ) {
			update_user_meta( $user_id, 'jupiterx_raven_register_newsletter', 'on' );
		}

		foreach ( $fields as $field ) {
			if ( 'custom_meta' === $field['map_to'] ) {
				$meta_id  = $field['meta_id'];
				$field_id = $field['_id'];
				$value    = $form['fields'][ $field_id ];

				update_user_meta( $user_id, $meta_id, $value );
			}
		}

		$success_msg = __( 'You have been registered successfully.', 'jupiterx-core' );
		if ( 'yes' === $settings['enable_custom_messages'] && ! empty( $settings['custom_message_success'] ) ) {
			$success_msg = $settings['custom_message_success'];
		}

		return $ajax_handler
			->set_success( true )
			->add_response( 'message', $success_msg );
	}
}
