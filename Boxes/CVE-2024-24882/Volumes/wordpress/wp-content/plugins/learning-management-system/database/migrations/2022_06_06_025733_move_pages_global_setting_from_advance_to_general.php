<?php
/**
 * Migration class template used by the wp cli to create migration classes.
 */

use Masteriyo\Database\Migration;

class MovePagesGlobalSettingFromAdvanceToGeneral extends Migration {
	/**
	 * Run the migration.
	 */
	public function up() {
		$settings = get_option( 'masteriyo_settings' );
		$pages    = masteriyo_array_get( $settings, 'advance.pages' );

		if ( ! empty( $pages ) ) {
			masteriyo_array_set( $settings, 'advance.pages', array() );
			masteriyo_array_set( $settings, 'general.pages', $pages );
			update_option( 'masteriyo_settings', $settings );
		}
	}

	/**
	 * Reverse the migrations.
	 */
	public function down() {
		$settings = get_option( 'masteriyo_settings' );
		$pages    = masteriyo_array_get( $settings, 'general.pages' );

		if ( ! empty( $pages ) ) {
			masteriyo_array_set( $settings, 'general.pages', array() );
			masteriyo_array_set( $settings, 'advance.pages', $pages );
			update_option( 'masteriyo_settings', $settings );
		}
	}
}
