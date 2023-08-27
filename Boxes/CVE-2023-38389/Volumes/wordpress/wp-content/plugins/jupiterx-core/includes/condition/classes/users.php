<?php

/**
 * Jupiterx users condition manager.
 *
 * @since 2.0.0
*/

class Jupiterx_Users_Condition {

	/**
	 * Check condition array.
	 *
	 * Sample conditions array:
	 *
	 * @param array $condition.
	 * @return boolean
	 * @since 2.0.0
	 */
	public function sub_condition( $condition ) {
		if ( 'all' === $condition[1] ) {
			return true;
		}

		if ( 'guests-users' === $condition[1] && ! is_user_logged_in() ) {
			return true;
		}

		if ( 'all-users' === $condition[1] && is_user_logged_in() ) {
			return true;
		}

		if ( is_user_logged_in() ) {
			return $this->role_check( $condition );
		}

		return false;
	}

	/**
	 * Checks if current user role match the condition.
	 *
	 * @param [array] $condition
	 * @return boolean
	 * @since 2.0.0
	 */
	private function role_check( $condition ) {
		$requested_role = $condition[1];
		$user           = wp_get_current_user();

		if ( in_array( $requested_role, (array) $user->roles, true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Retrieve users roles in cases we need it
	 *
	 * @return array
	 * @since 2.0.0
	 */
	public static function list_user_role() {
		global $wp_roles;

		$all_roles      = $wp_roles->roles;
		$editable_roles = apply_filters( 'editable_roles', $all_roles );
		$roles          = [];

		foreach ( $editable_roles as $key => $details ) {
			$roles[ $key ] = $details['name'];
		}

		return $roles;
	}
}
