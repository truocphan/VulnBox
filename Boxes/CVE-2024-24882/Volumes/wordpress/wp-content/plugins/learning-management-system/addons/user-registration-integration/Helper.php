<?php
/**
 * User Registration integration helper class.
 *
 * @since 1.7.1
 */

namespace Masteriyo\Addons\UserRegistrationIntegration;

/**
 * User Registration Integration helper class.
 *
 * @since 1.7.1
 */
class Helper {

	/**
	 * Check if the User Registration plugin is active.
	 *
	 * @since 1.7.1
	 *
	 * @return boolean
	 */
	public static function is_user_registration_active() {
		return in_array( 'user-registration/user-registration.php', get_option( 'active_plugins', array() ), true );
	}

	/**
	 * Checks if a specific registration form is replaceable by a user-defined registration form.
	 *
	 * The function looks for a setting that allows overriding the default registration form.
	 * If the override is enabled and the custom form is set, it returns true indicating
	 * that the custom form should be used instead of the default form.
	 *
	 * @since 1.7.1
	 *
	 * @param string $form_name The name of the form to check, defaults to 'student'.
	 *
	 * @return bool Returns true if the form is replaceable, otherwise false.
	 */
	public static function is_registration_form_replaceable( $form_name = 'student' ) {
		$option_key = "override_{$form_name}_registration";
		$form_key   = "{$form_name}_registration_form";

		$enabled = Setting::get( $option_key );

		if ( $enabled ) {
			$form = Setting::get( $form_key );
			if ( ! empty( $form ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Retrieves all published user registration forms in an associative array format, efficiently.
	 *
	 * @since 1.7.1
	 *
	 * @global wpdb $wpdb WordPress database abstraction object.
	 *
	 * @return array Associative array with post IDs as keys and post titles as values.
	 */
	public static function get_all_published_user_registration_forms() {
		global $wpdb;

		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT ID, post_title
        FROM {$wpdb->posts}
        WHERE post_type = %s AND post_status = %s
        ORDER BY ID DESC",
				'user_registration',
				'publish'
			)
		);

		$forms_assoc_array = wp_list_pluck( $results, 'post_title', 'ID' );

		return $forms_assoc_array;
	}
}
