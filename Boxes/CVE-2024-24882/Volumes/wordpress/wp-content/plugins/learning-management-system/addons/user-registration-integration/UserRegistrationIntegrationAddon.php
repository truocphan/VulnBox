<?php
/**
 * UserRegistration Integration addon main class.
 *
 * Handles the integration of user registration functionality.
 *
 * @since 1.7.1
 */

namespace Masteriyo\Addons\UserRegistrationIntegration;

use Masteriyo\Enums\UserStatus;
use Masteriyo\Roles;

/**
 * UserRegistration Integration addon main class.
 *
 * Manages the integration of User Registration forms and settings
 *
 * @since 1.7.1
 */
class UserRegistrationIntegrationAddon {

	/**
	 * Initialize.
	 *
	 * @since 1.7.1
	 */
	public function init() {
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.7.1
	 */
	public function init_hooks() {

		// Registration form content hooks.
		add_action(
			'masteriyo_before_registration_form_content',
			function() {
				$this->override_registration_form( 'student' );
			}
		);
		add_action(
			'masteriyo_before_instructor_registration_form_content',
			function() {
				$this->override_registration_form( 'instructor' );
			}
		);

		// User registration hooks.
		add_action( 'user_registration_after_register_user_action', array( $this, 'create_masteriyo_user' ), 10, 3 );
		add_action( 'ur_user_status_updated', array( $this, 'update_user_status' ), 10, 3 );

		// Settings hooks.
		add_filter( 'masteriyo_rest_response_setting_data', array( $this, 'append_setting_in_response' ), 10, 4 );
		add_action( 'masteriyo_new_setting', array( $this, 'save_user_registration_integration_settings' ), 10, 1 );

		// Script localization hook.
		add_filter( 'masteriyo_localized_admin_scripts', array( $this, 'localize_admin_scripts' ) );

	}

		/**
	 * Localize admin scripts.
	 *
	 * @since 1.7.1
	 *
	 * @param array $scripts Array of scripts.
	 *
	 * @return array
	 */
	public function localize_admin_scripts( $scripts ) {
		$ur_forms = Helper::get_all_published_user_registration_forms();

		$scripts['backend']['data']['user_registration'] = array(
			'ur_forms' => $ur_forms,
		);

		return $scripts;
	}

	/**
	 * Updates the status of a user within the Masteriyo plugin when their status is updated in User Registration.
	 *
	 * This method listens to the status update hook of the User Registration plugin and synchronizes
	 * the user's status with the Masteriyo user profile, ensuring consistent user status across plugins.
	 *
	 * @since 1.7.1
	 *
	 * @param string $status The new status of the user.
	 * @param int    $user_id The ID of the user whose status is being updated.
	 * @param bool   $alert_user Whether the user should be alerted about the status change.
	 */
	public function update_user_status( $status, $user_id, $alert_user ) {
		$user = masteriyo_get_user( $user_id );

		if ( is_wp_error( $user ) || is_null( $user ) ) {
			return;
		}

		if ( in_array( Roles::INSTRUCTOR, $user->get_roles(), true ) ) {
			if ( '1' === $status ) {
				$user->set_status( UserStatus::ACTIVE );
			} else {
				$user->set_status( UserStatus::INACTIVE );
			}
		}
		$user->save();
	}

	/**
	 * Creates a new user profile in the Masteriyo plugin after registration through User Registration.
	 *
	 * @since 1.7.1
	 *
	 * @param array $form_data Data from the registration form.
	 * @param int   $form_id The ID of the form being submitted.
	 * @param int   $user_id The ID of the newly registered user.
	 */
	public function create_masteriyo_user( $form_data, $form_id, $user_id ) {

		$user = masteriyo_get_user( $user_id );

		if ( is_wp_error( $user ) || is_null( $user ) ) {
			return;
		}
	}

	/**
	 * Overrides the default registration form with a custom User Registration form.
	 *
	 * If the addon settings allow, this method replaces the default Masteriyo registration
	 * form with a custom form from the User Registration plugin, enhancing the user registration
	 * process with additional fields and customization options.
	 *
	 * @since 1.7.1
	 *
	 * @param string $form_name The identifier for the type of form to override, defaults to 'student'.
	 */
	private function override_registration_form( $form_name = 'student' ) {

		if ( Helper::is_registration_form_replaceable( $form_name ) ) {
			$form_key = "{$form_name}_registration_form";
			$form_id  = Setting::get( $form_key );

			$form_shortcode = '[user_registration_form id="' . $form_id . '"]';

			echo do_shortcode( $form_shortcode );
		}
	}

	/**
	 * Append setting to response.
	 *
	 * @since 1.7.1
	 *
	 * @param array $data Setting data.
	 * @param \Masteriyo\Models\Setting $setting Setting object.
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @param \Masteriyo\RestApi\Controllers\Version1\SettingsController $controller REST settings controller object.
	 *
	 * @return array
	 */
	public function append_setting_in_response( $data, $setting, $context, $controller ) {
		$data['advance']['user_registration_integration'] = Setting::all();

		return $data;
	}

	/**
	 * Save global UR Integration settings.
	 *
	 * @since 1.7.1
	 *
	 * @param \Masteriyo\Models\Setting $setting Setting object.
	 */
	public function save_user_registration_integration_settings( $setting ) {
		$request = masteriyo_current_http_request();

		if ( ! masteriyo_is_rest_api_request() ) {
			return;
		}

		if ( ! isset( $request['advance']['user_registration_integration'] ) ) {
			return;
		}

		$settings = masteriyo_array_only( $request['advance']['user_registration_integration'], array_keys( Setting::all() ) );
		$settings = masteriyo_parse_args( $settings, Setting::all() );

		// Sanitization.
		$settings['override_student_registration']    = masteriyo_string_to_bool( $settings['override_student_registration'] );
		$settings['override_instructor_registration'] = masteriyo_string_to_bool( $settings['override_instructor_registration'] );
		$settings['student_registration_form']        = sanitize_text_field( $settings['student_registration_form'] );
		$settings['instructor_registration_form']     = sanitize_textarea_field( $settings['instructor_registration_form'] );

		Setting::set_props( $settings );

		Setting::save();
	}
}
