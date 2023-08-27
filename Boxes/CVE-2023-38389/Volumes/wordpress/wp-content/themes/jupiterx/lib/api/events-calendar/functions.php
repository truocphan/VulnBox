<?php
/**
 * Make The Events Calendar plugin theme compatible.
 *
 * @package JupiterX\Framework\API\The_Events_Calendar
 *
 * @since 1.10.0
 */

add_action( 'admin_enqueue_scripts', 'jupiterx_tribe_select2_conflict_fix', 11 );

if ( ! function_exists( 'jupiterx_tribe_select2_conflict_fix' ) ) {
	/**
	 * Disable Tribe select2 function when not in tribe admin screens.
	 *
	 * @since 1.10.0
	 *
	 * @return void
	 */
	function jupiterx_tribe_select2_conflict_fix() {
		if ( ! class_exists( 'Tribe__Admin__Helpers' ) ) {
			return;
		}

		$admin_helpers = Tribe__Admin__Helpers::instance();

		if ( ! $admin_helpers->is_screen() ) {
			wp_scripts()->remove( 'tribe-select2' );
			wp_deregister_style( 'tribe-select2-css' );
		}
	}
}
